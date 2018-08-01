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

if (!defined('BOOTSTRAP')) {
    die('Access denied');
}

if (empty(Tygh::$app['session']['cart'])) {
    fn_clear_cart(Tygh::$app['session']['cart']);
}

$cart = & Tygh::$app['session']['cart'];

if (!isset($cart['user_data']['max_order_price'])) {
    $max_total = $cart['user_data']['max_order_price'];
} elseif (!empty($cart['user_data']['user_id'])) {
    $max_total = db_get_field("SELECT max_order_price FROM ?:users WHERE user_id = ?i", $cart['user_data']['user_id']);
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
