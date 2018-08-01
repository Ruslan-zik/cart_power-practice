<?php
/***************************************************************************
*                                                                          *
*   (c) 2004 Vladimir V. Kalynyak, Alexey V. Vinokurov, Ilya M. Shalnev    *
*                                                                          *
* This  is  commercial  software,  only  users  who have purchased a valid *
* license  and  accept  to the terms of the  License Agreement can install *
* and use this program.                                                    *
*                                                                          *
****************************************************************************
* PLEASE READ THE FULL TEXT  OF THE SOFTWARE  LICENSE   AGREEMENT  IN  THE *
* "copyright.txt" FILE PROVIDED WITH THIS DISTRIBUTION PACKAGE.            *
****************************************************************************/

use Tygh\Registry;

if (!defined('BOOTSTRAP')) {
    die('Access denied');
}

if (fn_allowed_for('ULTIMATE') && !empty($_REQUEST['doc_id'])) {
    $doc_comp_id = fn_get_company_id('cp_documents', 'doc_id', $_REQUEST['doc_id']);

    if (
        $doc_comp_id != Registry::get('runtime.company_id')
        && $doc_comp_id != 0
        && db_get_field('SELECT type FROM ?:cp_documents WHERE doc_id = ?i', $_REQUEST['doc_id']) == 'I'
    ) {
        return [CONTROLLER_STATUS_NO_PAGE];
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (
        $mode == 'doc_view'
        && !empty($_REQUEST['getfile'])
        && !empty($_REQUEST['file'])
        && !empty($_REQUEST['doc_id'])
    ) {
        $path = fn_get_files_dir_path('cp_documents');
        $path .= $_REQUEST['doc_id'] . '/' . CART_LANGUAGE . '/';
        fn_get_file($path . fn_basename($_REQUEST['file']));

        return [CONTROLLER_STATUS_OK, 'cp_documents.doc_view&doc_id=' . $_REQUEST['doc_id']];
    }
}

if ($mode == 'view') {
    $_REQUEST['status'] = 'A';
    list($documents, $search) = fn_get_cp_documents($_REQUEST, Registry::get('settings.Appearance.products_per_page'), CART_LANGUAGE);
    Registry::get('view')->assign('documents', $documents);
    Registry::get('view')->assign('search', $search);

    $categories = !empty($_REQUEST['company_id']) ? fn_get_cp_categories_docs_for_search(CART_LANGUAGE, $_REQUEST['company_id']) : fn_get_cp_categories_docs_for_search(CART_LANGUAGE);

    Registry::get('view')->assign('categories', $categories);

    $sorting = [
        'name' => [
            '?:cp_documents_description.name' => 'asc'
        ],
        'category' => [
            '?:cp_categories_docs_description.category_name' => 'asc'
        ],
        'create_date' => [
            '?:cp_documents.create_date ' => 'asc'
        ]
    ];
    Registry::get('view')->assign('sorting', $sorting);

    $sorting_orders = ['asc', 'desc'];
    Registry::get('view')->assign('sorting_orders', $sorting_orders);
    
    $curl = 'cp_documents.view';
    Registry::get('view')->assign('curl', $curl);

    if (fn_allowed_for('MULTIVENDOR') && !empty($_REQUEST['company_id'])) {
        $vendor_info = fn_get_company_data($_REQUEST['company_id']);
        fn_add_breadcrumb($vendor_info['company'], 'companies.products&company_id=' . $vendor_info['company_id'], true);
    }

    fn_add_breadcrumb(__("cp_documents"));
} elseif ($mode == 'doc_view') {
    $_REQUEST['status'] = ['A', 'H'];
    list($documents, ) = fn_get_cp_documents($_REQUEST, Registry::get('settings.Appearance.admin_elements_per_page'), CART_LANGUAGE);

    if (empty($documents)) {
        return [CONTROLLER_STATUS_NO_PAGE];
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
