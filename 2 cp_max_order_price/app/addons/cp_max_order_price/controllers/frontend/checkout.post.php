<?php

if (!defined('BOOTSTRAP')) {
    die('Access denied');
}

if (empty(Tygh::$app['session']['cart'])) {
    fn_clear_cart(Tygh::$app['session']['cart']);
}

$cart = & Tygh::$app['session']['cart'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    return;
}

if (isset($cart['user_data']['cp_max_order_price'])) {
    $max_total = $cart['user_data']['cp_max_order_price'];
}

if (
    $mode == 'cart'
    && !empty((int)$max_total)
    && $cart['total'] > $max_total
) {
    $correct_total = $cart['total'] - $max_total;
    fn_set_notification('E', __('error'), fn_message_cp_max_order_price($max_total, $correct_total, 'cart'));
}
