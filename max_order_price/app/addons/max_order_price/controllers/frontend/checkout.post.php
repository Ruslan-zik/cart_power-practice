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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    return;
}

if (isset($cart['user_data']['max_order_price'])) {
    $max_total = $cart['user_data']['max_order_price'];
}

if (
    $mode == 'cart'
    && !empty((int)$max_total)
    && $cart['total'] > $max_total
) {
    $correct_total = $cart['total'] - $max_total;
    fn_set_notification('E', __('error'), fn_message_max_order_price($max_total, $correct_total, 'cart'));
}
