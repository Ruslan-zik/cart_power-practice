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

$schema['controllers']['cp_documents'] = [
    'permissions' => true,
];

$schema['controllers']['cp_categories_docs'] = [
    'permissions' => true,
];

$schema['controllers']['tools']['modes']['update_status']['param_permissions']['table'] = [
    'cp_documents' => true,
    'cp_categories_docs' => true,
];

return $schema;
