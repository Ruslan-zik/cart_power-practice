<?php

use Tygh\Languages\Languages;
use Tygh\Languages\Values as LanguageValues;

if (!defined('BOOTSTRAP')) {
    die('Access denied');
}

function fn_cp_delivery_date_generate_cart_id(&$_cid, &$extra, $only_selectable)
{
    if (!empty($extra['cp_delivery_date'])) {
        $_cid[] = $extra['cp_delivery_date'];
    }
}

function fn_cp_delivery_date_calculate_cart_items(&$cart, &$cart_products, $auth, $apply_cart_promotions)
{
    foreach ($cart['products'] as $key => $data) {
        if (!empty($data['extra']['cp_delivery_date'])) {
            $cart_products[$key]['extra']['cp_delivery_date'] = fn_parse_date($data['extra']['cp_delivery_date']);
        }
    }
}

function fn_cp_delivery_date_place_order(&$order_id, $action, $order_status, &$cart, $auth)
{
    foreach ($cart['products'] as $k => $v) {
        if (!empty($v['extra']['cp_delivery_date'])) {
            db_query("UPDATE ?:order_details SET cp_delivery_date = ?i WHERE item_id = ?i AND order_id = ?i", fn_parse_date($v['extra']['cp_delivery_date']), $k, $order_id);
        }
    }
}

function fn_cp_delivery_date_get_orders(&$params, &$fields, &$sortings, &$condition, &$join, &$group)
{
    $fields[] = "?:order_details.cp_delivery_date";

    if (empty($params['custom_files']) || $params['custom_files'] != 'Y') {
        $join .= " INNER JOIN ?:order_details ON ?:order_details.order_id = ?:orders.order_id";
    }

    if (!empty($params['sort_order']) && $params['sort_by'] == 'cp_delivery_date') {
        $comparison = $params['sort_order'] == 'desc' ? '<' : '>';
    } else {
        $comparison = '>';
    }

    $join .= " LEFT OUTER JOIN ?:order_details as b_order_details ON ?:order_details.order_id = b_order_details.order_id AND ?:order_details.cp_delivery_date $comparison b_order_details.cp_delivery_date";

    $condition .= " AND b_order_details.cp_delivery_date IS NULL";

    $sortings['cp_delivery_date'] = array(
        "?:order_details.cp_delivery_date", 
        "?:order_details.order_id"
    );

    $group .= " GROUP BY ?:order_details.order_id";
}

function fn_install_cp_delivery_date_column()
{
    if (version_compare(PRODUCT_VERSION, '4.4.1', '>=')) {
        $snippet_id = db_get_field("SELECT snippet_id FROM ?:template_snippets WHERE code = ?s AND type = ?s", 'products_table', 'order_invoice');

        if ($snippet_id) {
            $data = array (
                'code' => 'cp_delivery_date',
                'name' => 'Delivery date',
                'template' => '{% if p.cp_delivery_date %}<p style="text-align: center; font-family: Helvetica, Arial, sans-serif;"><strong>{{ p.cp_delivery_date }}</strong></p>{% else %}<p style="text-align: center; font-family: Helvetica, Arial, sans-serif;">{{__("cp_delivery_date.not_indicated")}}</p>{% endif %}',
                'status' => 'A',
                'addon' => 'cp_delivery_date'
            );

            /** @var \Tygh\Template\Snippet\Repository $snippet_repository */
            $snippet_repository = Tygh::$app['template.snippet.repository'];

            /** @var \Tygh\Template\Snippet\Table\ColumnRepository $column_repository */
            $column_repository = Tygh::$app['template.snippet.table.column_repository'];

            // * @var \Tygh\Template\Snippet\Table\ColumnService $column_service
            $column_service = Tygh::$app['template.snippet.table.column_service'];

            $snippet = $snippet_repository->findById($snippet_id);
            $data['snippet_type'] = $snippet->getType();
            $data['snippet_code'] = $snippet->getCode();
            
            $column_service->createColumn($data);
            $column_id = db_get_field("SELECT column_id FROM ?:template_table_columns WHERE addon = 'cp_delivery_date'");
            $column = $column_repository->findById($column_id);

            $lang_codes = Languages::getAll();
            $lang_val = LanguageValues::getByName('cp_delivery_date.cp_delivery_date', '');
            
            foreach ($lang_val as $val) {
                if (array_key_exists($val['lang_code'], $lang_codes)) {
                    $data['name'] = $val['value'];
                    $column_service->updateColumn($column, $data, $val['lang_code']);
                }
            }
        }
    }
}

function fn_uninstall_cp_delivery_date_column()
{
    if (version_compare(PRODUCT_VERSION, '4.4.1', '>=')) {
        /** @var \Tygh\Template\Snippet\Table\ColumnRepository $column_repository */
        $column_repository = Tygh::$app['template.snippet.table.column_repository'];

        // * @var \Tygh\Template\Snippet\Table\ColumnService $column_service
        $column_service = Tygh::$app['template.snippet.table.column_service'];

        $column_id = db_get_field("SELECT column_id FROM ?:template_table_columns WHERE addon = 'cp_delivery_date'");
        $column = $column_repository->findById($column_id);
        $column_service->removeColumn($column);
    }
}
