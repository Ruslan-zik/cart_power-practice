<?php

if (!defined('BOOTSTRAP')) {
    die('Access denied');
}

function fn_max_order_price_pre_add_to_cart($product_data, &$cart, $auth, $update)
{
    if (!empty($cart['user_data']['user_id'])) {
        $cart['user_data']['max_order_price'] = db_get_field("SELECT max_order_price FROM ?:users WHERE user_id = ?i", $cart['user_data']['user_id']);

        if (!empty($cart['subtotal']) && $cart['subtotal'] > $cart['user_data']['max_order_price']) {
            $cart['old_cart'] = true;
        } else {
            $cart['old_cart'] = '';
        }
    }
}

function fn_max_order_price_calculate_cart_items(&$cart, $cart_products, $auth, $apply_cart_promotions)
{
    if (
        isset($cart['user_data']['max_order_price'])
        && !isset($cart['old_cart'])
        && $cart['subtotal'] > $cart['user_data']['max_order_price']
    ) {
        $cart['old_cart'] = true;
    }
}

function fn_max_order_price_add_product_to_cart_get_price($product_data, &$cart, $auth, $update, $_id, $data, $product_id, &$amount, $price, $zero_price_action, &$allow_add)
{
    if (isset($cart['user_data']['max_order_price']) && !empty((int)$cart['user_data']['max_order_price'])) {
        $max_total = $cart['user_data']['max_order_price'];

        if (isset($cart['products'][$_id])) {
            $cart_total = $cart['total'] - $cart['products'][$_id]['amount'] * $cart['products'][$_id]['display_price'] + $amount * $cart['products'][$_id]['display_price'];
        }

        if (isset($cart['total']) && $cart['total'] > $max_total) {
            $allow_add = false;

            if (
                isset($cart_total)
                && $cart['products'][$_id]['amount'] > $amount
                && !empty($update)
            ) {
                $correct_total = $cart_total - $max_total;
                $allow_add = true;
            } else {
                $correct_total = $cart['total'] - $max_total;
            }

            if ($correct_total > 0 && empty($update)) {
                fn_set_notification('E', __('error'), fn_message_max_order_price($max_total, $correct_total, 'cart'));
            }
        } elseif (isset($cart_total) && $cart_total > $max_total) {
            $allow_add = false;
            $correct_total = $max_total - $cart['total'];
            fn_set_notification('E', __('error'), fn_message_max_order_price($max_total, $correct_total, 'add'));
        }

        $cart['last_added_product'] = $_id;
    }
}


function fn_max_order_price_calculate_cart_post(&$cart, &$auth, $calculate_shipping, $calculate_taxes, $options_style, $apply_cart_promotions, $cart_products, $product_groups)
{
    if (isset($cart['user_data']['max_order_price']) && !empty((int)$cart['user_data']['max_order_price'])) {
        $max_total = $cart['user_data']['max_order_price'];

        if (
            $cart['subtotal'] > $max_total
            && empty($cart['old_cart'])
            && isset($cart['last_added_product'])
        ) {
            $last_added_product =& $cart['products'][$cart['last_added_product']];
            $price = $last_added_product['price'];
            $old_amount = $last_added_product['amount'];
            $last_added_product['amount'] = floor(($max_total - ($cart['subtotal'] - ($price * $old_amount))) / $price);
            $new_amount = $last_added_product['amount'];

            if ($new_amount == 0) {
                fn_delete_cart_product($cart, $cart['last_added_product']);
            } else {
                fn_set_notification('W', __('warning'), fn_message_max_order_price($old_amount, $new_amount, 'amount', $last_added_product['product']));
            }

            fn_calculate_cart_content($cart, $auth, 'S', true, 'F', true);

            $correct_total = $max_total - $cart['subtotal'];
            $cart['skip_notification'] = true;

            fn_set_notification('E', __('error'), fn_message_max_order_price($max_total, $correct_total, 'add'));
        } else {
            $cart['skip_notification'] = false;
        }
    }
}

function fn_message_max_order_price($max_total, $correct_total, $error, $extra = '')
{
    if ($error == 'add') {
        $mes = str_replace('[max_total]', fn_format_price_by_currency_depricated($max_total), __('error_add_max_order_price'));
        $mes = str_replace('[correct_total]', fn_format_price_by_currency_depricated($correct_total), $mes);
    } elseif ($error == 'cart') {
        $mes = str_replace('[max_total]', fn_format_price_by_currency_depricated($max_total), __('error_cart_max_order_price'));
        $mes = str_replace('[correct_total]', fn_format_price_by_currency_depricated($correct_total), $mes);
    } elseif ($error == 'amount') {
        $mes = str_replace('[max_total]', $max_total, __('error_amount_max_order_price'));
        $mes = str_replace('[correct_total]', $correct_total, $mes);
        $mes = str_replace('[product]', $extra, $mes);
    }
    
    return $mes;
}
