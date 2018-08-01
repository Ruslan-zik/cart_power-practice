<?php

if (!defined('BOOTSTRAP')) {
    die('Access denied');
}

function fn_max_delivery_time_update_product_pre(&$product_data, $product_id, $lang_code, $can_update)
{
    if (isset($product_data['max_delivery_time'])) {
        $product_data['max_delivery_time'] = preg_replace("/[^0-9]/", '', $product_data['max_delivery_time']);
    }
}

function fn_max_delivery_time_add_to_cart(&$cart, &$product_id, &$_id)
{
    $cart['products'][$_id]['max_delivery_time'] = db_get_field('SELECT `max_delivery_time` FROM ?:products WHERE product_id = ?i', $product_id);
}

function fn_max_delivery_time_create_order(&$order)
{
    $order['max_delivery_time'] = 0;

    foreach ($order['products'] as $product_id => $product_data) {
        foreach ($product_data as $k => $v) {
            if ($k == 'max_delivery_time') {
                if (
                    $v != 0
                    && ($order['max_delivery_time'] > $v
                        || $order['max_delivery_time'] == 0)
                ) {
                    $order['max_delivery_time'] = $v;
                }
            }
        }
    }
    
    if (!empty($order['max_delivery_time'])) {
        $order['max_delivery_time'] = fn_working_days($order['max_delivery_time']);
    }
}

function fn_max_delivery_time_get_orders(&$params, &$fields, &$sortings, &$condition, &$join, &$group)
{
    $fields[] = "?:orders.max_delivery_time";
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

function fn_settings_variants_addons_max_delivery_time_order_status()
{
    $data = [];
    $statuses = fn_get_statuses(STATUSES_ORDER, '', '', '', CART_LANGUAGE);

    foreach ($statuses as $status) {
        $data[$status['status']] = $status['description'];
    }
    
    return $data;
}

function fn_max_delivery_time_settings_info_handler()
{
    return "<h4 class='center'><strong>".__('max_delivery_time.text_settings_info')."</strong></h4>";
}
