<?php

$schema['controllers']['cp_documents'] = array(
    'permissions' => true,
);

$schema['controllers']['cp_categories_docs'] = array(
    'permissions' => true,
);

$schema['controllers']['tools']['modes']['update_status']['param_permissions']['table'] = array(
    'cp_documents' => true,
    'cp_categories_docs' => true,
);

return $schema;
