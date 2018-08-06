<?php


if (!defined('BOOTSTRAP')) {
    die('Access denied');
}

fn_register_hooks(
    'update_user_pre',
    'update_profile'
);
