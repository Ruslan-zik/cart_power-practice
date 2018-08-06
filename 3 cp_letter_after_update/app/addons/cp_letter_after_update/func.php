<?php

use Tygh\Registry;

if (!defined('BOOTSTRAP')) {
    die('Access denied');
}

function fn_cp_letter_after_update_update_user_pre($user_id, &$user_data, &$auth, $ship_to_another, $notify_user)
{
    if (AREA != 'A') {
        $user_data['old_user_data'] = fn_get_user_info($auth['user_id'], true);
    }
}

function fn_cp_letter_after_update_update_profile(&$action, &$user_data, $current_user_data)
{
    if ($action == 'update' && AREA != 'A') {
        $diff = fn_array_recursive_diff($user_data['old_user_data'], $user_data);
        $condition = '';

        if (!empty($diff)) {
            if (isset($diff['fields'])) {
                foreach ($diff['fields'] as $k => $v) {
                    $condition .= ' OR ?:profile_fields.field_id ='.$k;
                }

                $fields = db_get_hash_single_array("SELECT `field_id`, `field_name` FROM ?:profile_fields WHERE 0 $condition", ['field_id', 'field_name']);

                foreach ($fields as $k => $v) {
                    $diff[$v] = $diff['fields'][$k];
                    $user_data[$v] = $user_data['fields'][$k];
                }

                unset($diff['fields']);
            }

            $profile_fields = db_get_hash_single_array("SELECT ?:profile_fields.field_name, ?:profile_field_descriptions.description FROM ?:profile_field_descriptions LEFT JOIN ?:profile_fields ON ?:profile_field_descriptions.object_id = ?:profile_fields.field_id WHERE ?:profile_fields.field_name IN (?a) AND ?:profile_field_descriptions.object_type = 'F' AND ?:profile_field_descriptions.lang_code = ?s $condition", ['field_name', 'description'], array_keys($diff), Registry::get('settings.Appearance.backend_default_language'));

            if (isset($profile_fields['b_country'])) {
                $profile_fields['b_country_descr'] = $profile_fields['b_country'];
            }

            if (isset($profile_fields['s_country'])) {
                $profile_fields['s_country_descr'] = $profile_fields['s_country'];
            }

            $mes_b = '';
            $mes_m = '';
            $mes_s = '';

            foreach ($diff as $k => $v) {
                if ($k == 'password_change_timestamp') {
                    $mes_m[] = __("password").': '.__("changed");
                } elseif (
                    isset($profile_fields[$k])
                    && $k != 'b_state'
                    && $k != 's_state'
                    && $k != 's_country'
                    && $k != 'b_country'
                ) {
                    if (preg_match('/(b_)/', $k)) {
                        if ($k == 'b_country_descr' || $k == 'b_state_descr') {
                            $mes_b[] = $profile_fields[$k == 'b_country_descr' ? 'b_country' : 'b_state'].': '.$diff[$k].' => '.$user_data[$k];
                        } else {
                            $mes_b[] = $profile_fields[$k].': '.$diff[$k].' => '.$user_data[$k];
                        }
                    } elseif (preg_match('/(s_)/', $k)) {
                        if ($k == 's_country_descr' || $k == 's_state_descr') {
                            $mes_s[] = $profile_fields[$k == 's_country_descr' ? 's_country' : 's_state'].': '.$diff[$k].' => '.$user_data[$k];
                        } else {
                            $mes_s[] = $profile_fields[$k].': '.$diff[$k].' => '.$user_data[$k];
                        }
                    } else {
                        $mes_m[] = $profile_fields[$k].': '.$diff[$k].' => '.$user_data[$k];
                    }
                }
            }

            $to_mail = Registry::get('settings.Company.company_site_administrator');
            $mailer = Tygh::$app['mailer'];
            $mailer->send(array(
                'to' => $to_mail,
                'from' => 'company_users_department',
                'data' => array(
                    'mes_b' => $mes_b,
                    'mes_m' => $mes_m,
                    'mes_s' => $mes_s,
                    'url' => fn_url('profiles.update?user_id=' . $user_data['user_id'], 'A')
                ),
                'template_code' => 'cp_letter_after_update',
                'tpl' => 'addons/cp_letter_after_update/cp_letter_after_update.tpl',
                'company_id' => $user_data['company_id']
            ), 'A', Registry::get('settings.Appearance.backend_default_language'));
        }
    }
}


function fn_array_recursive_diff($array_1, $array_2)
{
    $return = array();

    foreach ($array_1 as $key => $value) {
        if (array_key_exists($key, $array_2)) {
            if (is_array($value)) {
                $diff = fn_array_recursive_diff($value, $array_2[$key]);

                if (count($diff)) {
                    $return[$key] = $diff;
                }
            } else {
                if ($value != $array_2[$key]) {
                    $return[$key] = $value;
                }
            }
        } else {
            $return[$key] = $value;
        }
    }

    return $return;
}
