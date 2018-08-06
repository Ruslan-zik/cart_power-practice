<?php

if (!defined('BOOTSTRAP')) { die('Access denied'); }

fn_register_hooks(
    'update_product_pre',
    'create_order',
    'add_to_cart',
    'get_orders'
);
