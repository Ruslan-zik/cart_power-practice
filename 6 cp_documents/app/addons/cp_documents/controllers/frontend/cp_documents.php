<?php

use Tygh\Registry;

if (!defined('BOOTSTRAP')) {
    die('Access denied');
}

if (fn_allowed_for('ULTIMATE') && !empty($_REQUEST['doc_id'])) {
    $doc_comp_id = fn_get_company_id('cp_documents', 'doc_id', $_REQUEST['doc_id']);

    if ($doc_comp_id != Registry::get('runtime.company_id') && !empty($doc_comp_id)) {
        return array(CONTROLLER_STATUS_NO_PAGE);
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (
        $mode == 'doc_view'
        && !empty($_REQUEST['getfile'])
        && !empty($_REQUEST['file'])
        && !empty($_REQUEST['doc_id'])
    ) {
        if (
            (fn_allowed_for('MULTIVENDOR') 
                && Registry::get('user_info.user_type') == 'V')
            || (fn_allowed_for('ULTIMATE')
                && Registry::get('user_info.user_type') == 'A'
                && Registry::get('user_info.company_id'))
            && !db_get_field("SELECT 1 FROM ?:cp_documents WHERE doc_id = ?i AND (type = 'G' OR (company_id = ?i OR company_id = 0))", $_REQUEST['doc_id'], Registry::get('user_info.company_id'))
        ) {
            return array(CONTROLLER_STATUS_NO_PAGE);
        } elseif (Registry::get('user_info.user_type') != 'A' && !db_get_field("SELECT 1 FROM ?:cp_documents WHERE doc_id = ?i AND type = 'G'", $_REQUEST['doc_id'])) {
            return array(CONTROLLER_STATUS_NO_PAGE);
        }

        $path = fn_get_files_dir_path('cp_documents');
        $path .= $_REQUEST['doc_id'] . '/' . CART_LANGUAGE . '/';
        fn_get_file($path . fn_basename($_REQUEST['file']));

        return array(CONTROLLER_STATUS_OK, 'cp_documents.doc_view&doc_id=' . $_REQUEST['doc_id']);
    }
}

if ($mode == 'view') {
    $_REQUEST['status'] = 'A';
    list($documents, $search) = fn_get_cp_documents($_REQUEST, Registry::get('settings.Appearance.products_per_page'), CART_LANGUAGE);
    Registry::get('view')->assign('documents', $documents);
    Registry::get('view')->assign('search', $search);

    $categories = !empty($_REQUEST['company_id']) ? fn_get_cp_categories_docs_for_search(CART_LANGUAGE, $_REQUEST['company_id']) : fn_get_cp_categories_docs_for_search(CART_LANGUAGE);

    Registry::get('view')->assign('categories', $categories);

    $sorting = array(
        'name' => array(
            '?:cp_documents_description.name' => 'asc'
        ),
        'category' => array(
            '?:cp_categories_docs_description.category_name' => 'asc'
        ),
        'create_date' => array(
            '?:cp_documents.create_date ' => 'asc'
        )
    );
    Registry::get('view')->assign('sorting', $sorting);

    $sorting_orders = array('asc', 'desc');
    Registry::get('view')->assign('sorting_orders', $sorting_orders);
    
    $curl = 'cp_documents.view';
    Registry::get('view')->assign('curl', $curl);

    if (fn_allowed_for('MULTIVENDOR') && !empty($_REQUEST['company_id'])) {
        $vendor_info = fn_get_company_data($_REQUEST['company_id']);
        fn_add_breadcrumb($vendor_info['company'], 'companies.products&company_id=' . $vendor_info['company_id'], true);
    }

    fn_add_breadcrumb(__("cp_documents"));
} elseif ($mode == 'doc_view') {
    $_REQUEST['status'] = array('A', 'H');
    list($documents, ) = fn_get_cp_documents($_REQUEST, Registry::get('settings.Appearance.admin_elements_per_page'), CART_LANGUAGE);

    if (empty($documents)) {
        return array(CONTROLLER_STATUS_NO_PAGE);
    }

    $files = fn_get_cp_documents_files_info($_REQUEST['doc_id'], CART_LANGUAGE);
    $document = array_shift($documents);
    Registry::get('view')->assign('document', $document);
    Registry::get('view')->assign('files', $files);

    if (fn_allowed_for('MULTIVENDOR') && !empty($document['company_id'])) {
        $vendor_info = fn_get_company_data($document['company_id']);

        fn_add_breadcrumb($vendor_info['company'], 'companies.products&company_id=' . $vendor_info['company_id'], true);
        fn_add_breadcrumb(__("cp_documents"), 'cp_documents.view&company_id=' . $vendor_info['company_id'], true);
    } else {
        fn_add_breadcrumb(__("cp_documents"), 'cp_documents.view', true);
    }

    fn_add_breadcrumb($document['name']);
}
