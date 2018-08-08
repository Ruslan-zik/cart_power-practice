<?php

use Tygh\Registry;

if (!defined('BOOTSTRAP')) {
    die('Access denied');
}

if (
    Registry::get('runtime.company_id')
    && (fn_allowed_for('ULTIMATE')
        || fn_allowed_for('MULTIVENDOR'))
) {
    if (!empty($_REQUEST['doc_id'])) {
        $doc_company_id = fn_get_company_id('cp_documents', 'doc_id', $_REQUEST['doc_id']);
        $doc_status = db_get_field('SELECT status FROM ?:cp_documents WHERE doc_id = ?i', $_REQUEST['doc_id']);
    }

    $run_company_id = Registry::get('runtime.company_id');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($doc_company_id)) {
        if (!empty($run_company_id) && $run_company_id != $doc_company_id) {
            if (
                $mode != 'getfile'
                || ($mode == 'getfile'
                    && empty($doc_company_id)
                    && $doc_status == 'D')
            ) {
                fn_company_access_denied_notification();

                return array(CONTROLLER_STATUS_REDIRECT, 'cp_documents.manage');
            }
        }

        if (!empty($_REQUEST['document_data']['doc_category_id'])) {
            if (!empty($run_company_id) && $run_company_id != fn_get_company_id('cp_categories_docs', 'doc_category_id', $_REQUEST['document_data']['doc_category_id'])) {
                fn_company_access_denied_notification();

                return array(CONTROLLER_STATUS_REDIRECT, 'cp_documents.manage');
            }
        }
    }

    if ($mode == 'update') {
        if (!empty($_REQUEST['document_description']['name']) && !empty($_REQUEST['document_data']['doc_category_id'])) {
            $doc_id = fn_update_cp_document($_REQUEST['document_data'], $_REQUEST['document_description'], $_REQUEST['doc_id'], DESCR_SL);
            $return_url = 'cp_documents.update&doc_id=' . $doc_id;
            $files = fn_filter_uploaded_data('filename');

            if (!empty($files)) {
                foreach ($files as $file) {
                    $path = fn_get_files_dir_path('cp_documents');
                    $path .= $doc_id . '/' . DESCR_SL . '/';
                    $base_name = fn_basename($file['name']);
                    $path_file = $path . $base_name;

                    if (!is_dir($path)) {
                        fn_mkdir($path);
                    }

                    if (fn_check_path($path_file)) {
                        fn_set_notification('E', __('error'), __('error_file_already_exists', ['[file]' => $base_name]));

                        return array(CONTROLLER_STATUS_OK, $return_url);
                    }

                    if (fn_copy($file['path'], $path . $file['name'])) {
                        $file_info['doc_id'] = $doc_id;
                        $file_info['file_name'] = $file['name'];
                        $file_info['path_file'] = $path . $file['name'];
                        $file_info['lang_code'] = DESCR_SL;
                        db_query("INSERT INTO ?:cp_files_docs ?e", $file_info);
                    }
                }
            }
        }
    }


    if ($mode == 'm_delete') {
        if (!empty($_REQUEST['doc_ids'])) {
            $doc_ids = (array) $_REQUEST['doc_ids'];

            foreach ($doc_ids as $doc_id) {
                if (!empty($run_company_id) && $run_company_id != fn_get_company_id('cp_documents', 'doc_id', $doc_id)) {
                    fn_company_access_denied_notification();

                    return array(CONTROLLER_STATUS_REDIRECT, 'cp_documents.manage');
                }

                fn_delete_cp_document($doc_id);
            }
        }
    }

    if ($mode == 'delete' && !empty($_REQUEST['doc_id'])) {
        fn_delete_cp_document($_REQUEST['doc_id']);
    }

    if (
        $mode == 'f_delete'
        && !empty($_REQUEST['file_id'])
        && !empty($_REQUEST['doc_id'])
        && !empty($_REQUEST['file'])
        && defined('AJAX_REQUEST')
    ) {
        if (fn_delete_cp_documents_files($_REQUEST)) {
            fn_set_notification('N', __('notice'), __('deleted'));
        } else {
            $base_name = fn_basename($_REQUEST['file']);
            fn_set_notification('E', __('error'), __('text_cannot_delete_file', ['[file]' => $base_name]));
        }

        if (!fn_get_cp_documents_files_info($_REQUEST['doc_id'], DESCR_SL)) {
            return array(CONTROLLER_STATUS_OK, 'cp_documents.update&doc_id=' . $_REQUEST['doc_id']);
        }
    }

    if (
        $mode == 'getfile'
        && !empty($_REQUEST['file'])
        && !empty($_REQUEST['doc_id'])
    ) {
        $path = fn_get_files_dir_path('cp_documents');
        $path .= $_REQUEST['doc_id'] . '/' . DESCR_SL . '/';

        fn_get_file($path . fn_basename($_REQUEST['file']));

        $return_url = 'cp_documents.update&doc_id=' . $_REQUEST['doc_id'];
    }

    if (!isset($return_url)) {
        $return_url = 'cp_documents.manage';
    }
    
    return array(CONTROLLER_STATUS_OK, $return_url);
}

if ($mode == 'update') {
    if (isset($doc_company_id) && !empty($run_company_id)) {
        if (
            (!empty($doc_company_id)
                && $run_company_id != $doc_company_id)
            || (empty($doc_company_id) 
                && $doc_status == 'D')
        ) {
            fn_company_access_denied_notification();

            return array(CONTROLLER_STATUS_REDIRECT, 'cp_documents.manage');
        }
    }

    if (!empty($_REQUEST['doc_id'])) {
        list($documents, ) = fn_get_cp_documents($_REQUEST, Registry::get('settings.Appearance.admin_elements_per_page'), DESCR_SL);
        $files = fn_get_cp_documents_files_info($_REQUEST['doc_id'], DESCR_SL);

        Registry::get('view')->assign('document', array_shift($documents));
        Registry::get('view')->assign('files', $files);

        if (!empty($_REQUEST['in_popup'])) {
            Registry::get('view')->assign('in_popup', 1);
        }
    }
}

if ($mode == 'manage') {
    $navigation_sections = array(
        'documents' => array(
            'title' => __('cp_documents'),
            'href' => fn_url('cp_documents.manage'),
        ),
        'categories' => array(
            'title' => __('documents_categories'),
            'href' => fn_url('cp_categories_docs.manage'),
        ),
    );

    list($documents, $search) = fn_get_cp_documents($_REQUEST, Registry::get('settings.Appearance.admin_elements_per_page'), DESCR_SL);
    $categories = fn_get_cp_categories_docs_for_search(DESCR_SL);
    
    Registry::set('navigation.dynamic.sections', $navigation_sections);
    Registry::set('navigation.dynamic.active_section', 'documents');
    Registry::get('view')->assign('documents', $documents);
    Registry::get('view')->assign('search', $search);
    Registry::get('view')->assign('categories', $categories);

    if (!empty($_REQUEST['categories'])) {
        Registry::get('view')->assign('selected_categories', $_REQUEST['categories']);
    }
}

if ($mode == 'picker') {
    $categories = fn_get_cp_categories_docs(DESCR_SL, '', true);

    Registry::get('view')->assign('categories', $categories);
    Registry::get('view')->display('addons/cp_documents/views/cp_documents/pickers/categories/picker_contents.tpl');

    exit;
}
