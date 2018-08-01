<?php

if (!defined('BOOTSTRAP')) {
    die('Access denied');
}

fn_register_hooks(
    'place_order',
    'get_orders',
    'calculate_cart_items',
    'generate_cart_id'
);
