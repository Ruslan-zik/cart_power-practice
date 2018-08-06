<?php

$scheme = array(
    'product' => array(
        'class' => '\Tygh\Addons\cp_delivery_date\Template\Snippet\Table\ProductVariable',
        'arguments' => array('#context', '#config', '@view', '@formatter'),
        'alias' => 'p',
        'null_display' => '-'
    )
);

return $scheme;
