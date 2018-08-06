<?php

use Tygh\Registry;
use Tygh\Enum\ProductTracking;

if (!defined('BOOTSTRAP')) {
    die('Access denied');
}

function fn_cp_out_stock_get_products(&$params, $fields, $sortings, &$condition, &$join, $sorting, $group_by, $lang_code, $having)
{
    if (!empty(Tygh::$app['session']['auth']['user_id']) && $params['area'] == 'C') {
        $cp_out_stock = db_get_field("SELECT cp_out_stock FROM ?:users WHERE user_id = ?i", Tygh::$app['session']['auth']['user_id']);

        if (Registry::get('settings.General.show_out_of_stock_products') != 'N' && $cp_out_stock == 'N') {
            $condition .= db_quote(
                    ' AND (CASE products.tracking' .
                    '   WHEN ?s THEN inventory.amount > 0' .
                    '   WHEN ?s THEN products.amount > 0' .
                    '   ELSE 1' .
                    ' END)',
                    ProductTracking::TRACK_WITH_OPTIONS,
                    ProductTracking::TRACK_WITHOUT_OPTIONS
            );
            $join .= " LEFT JOIN ?:product_options_inventory as inventory ON inventory.product_id = products.product_id";
        }
        
        if (Registry::get('settings.General.show_out_of_stock_products') == 'N' && $cp_out_stock != 'N') {
            $condition = preg_replace('/( AND \(CASE products.tracking' .
                                        '   WHEN)([\W|\w]+)(THEN inventory.amount > 0' .
                                        '   WHEN)([\W|\w]+)(THEN products.amount > 0' .
                                        '   ELSE 1' .
                                        ' END\))/', '', $condition);

            $join = preg_replace('/( LEFT JOIN \?:product_options_inventory as inventory ON inventory.product_id = products.product_id )/', '', $join);
        }
    }
}
