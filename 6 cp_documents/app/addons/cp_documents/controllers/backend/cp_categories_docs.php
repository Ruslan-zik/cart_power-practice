<?php

use Tygh\Registry;

if (!defined('BOOTSTRAP')) {
    die('Access denied');
}

$user_company_id = Registry::get('user_info.company_id');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (
        Registry::get('runtime.company_id')
        && (fn_allowed_for('ULTIMATE')
            || fn_allowed_for('MULTIVENDOR'))
    ) {
        if (!empty($_REQUEST['doc_category_id'])) {
            if ($user_company_id != 0 && $user_company_id != fn_get_company_id('cp_categories_docs', 'doc_category_id', $_REQUEST['doc_category_id'])) {
                fn_company_access_denied_notification();

                return array(CONTROLLER_STATUS_REDIRECT, 'cp_documents.manage');
            }
        }
    }

    if ($mode == 'update') {
        $doc_category_id = fn_update_cp_documents_category($_REQUEST['category_data'], $_REQUEST['category_description'], $_REQUEST['doc_category_id'], DESCR_SL);
    }

    if ($mode == 'delete' && !empty($_REQUEST['doc_category_id'])) {
        fn_delete_cp_category($_REQUEST['doc_category_id']);
    }

    return array(CONTROLLER_STATUS_OK, "cp_categories_docs.manage");
}

if ($mode == 'update') {
    if (!empty($_REQUEST['doc_category_id'])) {
        if (
            Registry::get('runtime.company_id')
            && (fn_allowed_for('ULTIMATE')
                || fn_allowed_for('MULTIVENDOR'))
            && ($user_company_id != fn_get_company_id('cp_categories_docs', 'doc_category_id', $_REQUEST['doc_category_id'])
                || ($user_company_id == 0
                    && db_get_field('SELECT status FROM ?:cp_categories_docs WHERE doc_category_id = ?i', $_REQUEST['doc_category_id']) == 'D'))
        ) {
            fn_company_access_denied_notification();

            return array(CONTROLLER_STATUS_REDIRECT, 'cp_documents.manage');
        }

        $category = fn_get_cp_categories_docs(DESCR_SL, $_REQUEST['doc_category_id']);

        if (!empty($_REQUEST['in_popup'])) {
            Registry::get('view')->assign('in_popup', 1);
        }

        Registry::get('view')->assign('category', array_shift($category));
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
    Registry::set('navigation.dynamic.sections', $navigation_sections);
    Registry::set('navigation.dynamic.active_section', 'categories');

    $categories = fn_get_cp_categories_docs(DESCR_SL);

    Registry::get('view')->assign('categories', $categories);
}
