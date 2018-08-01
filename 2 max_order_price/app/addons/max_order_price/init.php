<?php


if (!defined('BOOTSTRAP')) {
    die('Access denied');
}

fn_register_hooks(
    'pre_add_to_cart',
    'calculate_cart_items',
    'add_product_to_cart_get_price',
    'calculate_cart_post'
);
