<?php

if (!defined('BOOTSTRAP')) {
    die('Access denied');
}

function fn_cp_max_delivery_time_update_product_pre(&$product_data, $product_id, $lang_code, $can_update)
{
    if (isset($product_data['cp_max_delivery_time'])) {
        $product_data['cp_max_delivery_time'] = preg_replace("/[^0-9]/", '', $product_data['cp_max_delivery_time']);
    }
}

function fn_cp_max_delivery_time_add_to_cart(&$cart, &$product_id, &$_id)
{
    $cart['products'][$_id]['cp_max_delivery_time'] = db_get_field('SELECT `cp_max_delivery_time` FROM ?:products WHERE product_id = ?i', $product_id);
}

function fn_cp_max_delivery_time_create_order(&$order)
{
    $order['cp_max_delivery_time'] = 0;

    foreach ($order['products'] as $product_id => $product_data) {
        foreach ($product_data as $k => $v) {
            if ($k == 'cp_max_delivery_time') {
                if (
                    $v != 0
                    && ($order['cp_max_delivery_time'] > $v
                        || $order['cp_max_delivery_time'] == 0)
                ) {
                    $order['cp_max_delivery_time'] = $v;
                }
            }
        }
    }
    
    if (!empty($order['cp_max_delivery_time'])) {
        $order['cp_max_delivery_time'] = fn_working_days($order['cp_max_delivery_time']);
    }
}

function fn_cp_max_delivery_time_get_orders(&$params, &$fields, &$sortings, &$condition, &$join, &$group)
{
    $fields[] = "?:orders.cp_max_delivery_time";
}

function fn_working_days($days)
{
    $year = date('Y');
    $calendar = simplexml_load_file('http://xmlcalendar.ru/data/ru/' . $year . '/calendar.xml');

    $i = 1;
    $day = 1;
    while ($i <= $days) {
        $date = strtotime("now + $day days");

        if (date('Y', $date) != $year) {
            $year = date('Y', $date);
            $calendar = simplexml_load_file('http://xmlcalendar.ru/data/ru/' . $year . '/calendar.xml');
        }

        if (
            date('N', $date) != 6
            && date('N', $date) != 7
            && empty($calendar->xpath("//day[@d='".date('m.d', $date)."'][@t='1']"))
        ) {
            $i++;
        }

        $day++;
    }

    return date("U", $date);
}

function fn_settings_variants_addons_cp_max_delivery_time_order_status()
{
    $data = array();
    $statuses = fn_get_statuses(STATUSES_ORDER, '', '', '', CART_LANGUAGE);

    foreach ($statuses as $status) {
        $data[$status['status']] = $status['description'];
    }
    
    return $data;
}

function fn_cp_max_delivery_time_settings_info_handler()
{
    return "<h4 class='center'><strong>".__('cp_max_delivery_time.text_settings_info')."</strong></h4>";
}
