<?php
if (!defined('BOOTSTRAP')) {
    die('Access denied');
}

if (empty(Tygh::$app['session']['cart'])) {
    fn_clear_cart(Tygh::$app['session']['cart']);
}

$cart = & Tygh::$app['session']['cart'];

if (!isset($cart['user_data']['cp_max_order_price'])) {
    $max_total = $cart['user_data']['cp_max_order_price'];
} elseif (!empty($cart['user_data']['user_id'])) {
    $max_total = db_get_field("SELECT cp_max_order_price FROM ?:users WHERE user_id = ?i", $cart['user_data']['user_id']);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    return;
}

if (
    $mode == 'checkout'
    && !empty((int)$max_total)
    && $cart['total'] > $max_total
) {
    fn_redirect('checkout.cart');
}
