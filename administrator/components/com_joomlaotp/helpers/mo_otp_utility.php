<?php
/** miniOrange enables user to log in using otp credentials.
 * Copyright (C) 2015  miniOrange
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>
 * @package        miniOrange OAuth
 * @license        http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 */
/**
 * This class contains all the utility functions
 **/
defined('_JEXEC') or die('Restricted access');

class MoOtpUtility
{

    public static function is_customer_registered()
    {
        $result = self::__getDBValuesWOArray('#__miniorange_otp_customer');
        $email = $result['email'];
        $customerKey = $result['customer_key'];
        $status = $result['registration_status'];
        if ($email && $customerKey && is_numeric(trim($customerKey)) && $status == 'SUCCESS') {
            return 1;
        } else {
            return 0;
        }
    }

    public static function _check_country_code_blocked($phone_number)
    {
        $result = self::_get_list_blocked_country_code();
        $blocked_list = isset($result['mo_block_country_code']) ? $result['mo_block_country_code'] : '';

        if (!empty($blocked_list) && $blocked_list != '') {
            $blocked_list = unserialize($blocked_list);

            for ($i = 0; $i < count($blocked_list); $i++) {
                if (isset($blocked_list[$i]) && !empty($blocked_list[$i])) {
                    $val = $blocked_list[$i];
                    if (strpos($phone_number, $val) !== false) {
                        return 1;
                    }
                }
            }
            return 0;
        }
    }

    public static function _is_country_code_blocked($country_code)
    {
        $result = self::_get_list_blocked_country_code();
        $blocked_list = isset($result['mo_block_country_code']) ? $result['mo_block_country_code'] : '';
        if (!empty($blocked_list)) {
            $blocked_list = unserialize($blocked_list);
        }
        if (in_array($country_code, $blocked_list)) {
            return 1;
        } else {
            return 0;
        }
    }

    public static function _get_list_blocked_country_code()
    {
        $config = self::_get_custom_message();
        return $config;
    }

    public static function _is_default_selected($post)
    {
        $country_code = isset($post['mo_block_country_code']) ? $post['mo_block_country_code'] : '';
        $country_code = trim($country_code);
        $results = MoOtpUtility::getCustomerDetails();
        $default_country_code = isset($results['mo_default_country_code']) && !empty($results['mo_default_country_code'])
            ? $results['mo_default_country_code']
            : '';

        $default_country_code = '+' . $default_country_code;
        $country_code = explode(';', $country_code);

        if (in_array($default_country_code, $country_code)) {
            return true;
        } else {
            return false;
        }
    }

    public static function _block_country_code($post)
    {
        $country_code = isset($post['mo_block_country_code']) ? $post['mo_block_country_code'] : '';
        $country_code = trim($country_code);
        $country_code = explode(';', $country_code);
        $country_code = serialize($country_code);

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $fields = array(
            $db->quoteName('mo_block_country_code') . ' = ' . $db->quote($country_code),
        );
        $conditions = array(
            $db->quoteName('id') . ' = 1'
        );
        $query->update($db->quoteName('#__miniorange_otp_custom_message'))->set($fields)->where($conditions);
        $db->setQuery($query);
        $db->execute();
    }

    public static function _save_custom_message($post)
    {
        $message         = "A One Time Passcode has been sent to ##email##. Please enter the OTP below to verify your Email Address. If you cannot see the email in your inbox, make sure to check your SPAM folder.";
        $error_otp       = "There was an error in sending the OTP. Please enter a valid email id or contact site Admin.";
        $blocked_email   = "You are not allowed to register. Your Domain may be blocked. Please contact your administrator";
        $email_success   = isset($post['mo_custom_email_success_message_send']) ? $post['mo_custom_email_success_message_send'] : $message;
        $email_fail      = isset($post['mo_custom_email_error_message']) ? $post['mo_custom_email_error_message'] : $error_otp;
        $invalid_email   = isset($post['mo_custom_email_invalid_format_message']) ? $post['mo_custom_email_invalid_format_message'] : '';
        $blocked_message = isset($post['mo_custom_email_blocked_message']) ? $post['mo_custom_email_blocked_message'] : $blocked_email;

        $email_success   = trim($email_success);
        $email_fail      = trim($email_fail);
        $invalid_email   = trim($invalid_email);
        $blocked_message = trim($blocked_message);

        $db = JFactory::getDbo();

        $query = $db->getQuery(true);
        // Fields to update.

        $fields = array(
            $db->quoteName('mo_custom_email_success_message') . ' = ' . $db->quote($email_success),
            $db->quoteName('mo_custom_email_error_message') . ' = ' . $db->quote($email_fail),
            $db->quoteName('mo_custom_email_invalid_format_message') . ' = ' . $db->quote($invalid_email),
            $db->quoteName('mo_custom_email_blocked_message') . ' = ' . $db->quote($blocked_message),
        );

        // Conditions for which records should be updated.
        $conditions = array(
            $db->quoteName('id') . ' = 1'
        );

        $query->update($db->quoteName('#__miniorange_otp_custom_message'))->set($fields)->where($conditions);
        $db->setQuery($query);
        $db->execute();
    }

    public static function _save_custom_phone_message($post)
    {
        $success_message = "An OTP (One Time Passcode) has been sent to ##phone##. Please enter the OTP in the field below to verify your phone.";
        $error_message = "There was an error in sending the OTP to the given Phone Number. Please Try Again or contact site Admin.";

        $phone_success = isset($post['mo_custom_phone_success_message']) ? $post['mo_custom_phone_success_message'] : $success_message;
        $phone_error = isset($post['mo_custom_phone_error_message']) ? $post['mo_custom_phone_error_message'] : $error_message;
        $invalid_format = isset($post['mo_custom_phone_invalid_format_message']) ? $post['mo_custom_phone_invalid_format_message'] : '';
        $phone_blocked = isset($post['mo_custom_phone_blocked_message']) ? $post['mo_custom_phone_blocked_message'] : '';

        $phone_success = trim($phone_success);
        $phone_error = trim($phone_error);
        $invalid_format = trim($invalid_format);
        $phone_blocked = trim($phone_blocked);

        $db = JFactory::getDbo();

        $query = $db->getQuery(true);
        // Fields to update.

        $fields = array(
            $db->quoteName('mo_custom_phone_success_message') . ' = ' . $db->quote($phone_success),
            $db->quoteName('mo_custom_phone_error_message') . ' = ' . $db->quote($phone_error),
            $db->quoteName('mo_custom_phone_invalid_format_message') . ' = ' . $db->quote($invalid_format),
            $db->quoteName('mo_custom_phone_blocked_message') . ' = ' . $db->quote($phone_blocked),
        );

        // Conditions for which records should be updated.
        $conditions = array(
            $db->quoteName('id') . ' = 1'
        );

        $query->update($db->quoteName('#__miniorange_otp_custom_message'))->set($fields)->where($conditions);
        $db->setQuery($query);
        $db->execute();
    }

    public static function _save_com_message($post)
    {
        $invalid_otp = isset($post['mo_custom_invalid_otp_message']) ? $post['mo_custom_invalid_otp_message'] : '';

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $fields = array(
            $db->quoteName('mo_custom_invalid_otp_message') . ' = ' . $db->quote($invalid_otp),
        );
        $conditions = array(
            $db->quoteName('id') . ' = 1'
        );
        $query->update($db->quoteName('#__miniorange_otp_custom_message'))->set($fields)->where($conditions);
        $db->setQuery($query);
        $db->execute();
    }


    public static function _get_custom_message()
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from($db->quoteName('#__miniorange_otp_custom_message'));
        $query->where($db->quoteName('id') . " = 1");
        $db->setQuery($query);
        $result = $db->loadAssoc();
        return $result;
    }

    public static function check_empty_or_null($value)
    {
        if (!isset($value) || empty($value)) {
            return true;
        }
        return false;
    }

    public static function is_curl_installed()
    {
        if (in_array('curl', get_loaded_extensions())) {
            return 1;
        } else
            return 0;
    }

    public static function getHostname()
    {
        return 'https://login.xecurify.com';
    }

    public static function getCustomerDetails()
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from($db->quoteName('#__miniorange_otp_customer'));
        $query->where($db->quoteName('id') . " = 1");
        $db->setQuery($query);
        $customer_details = $db->loadAssoc();
        return $customer_details;
    }


    public static function __getDBValuesWOArray($table)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from($db->quoteName($table));
        $query->where($db->quoteName('id') . " = 1");
        $db->setQuery($query);
        $mresults = $db->loadAssoc();
        return $mresults;
    }


    public static function __getDBValuesWColumn($columnName, $tableName)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select($columnName);
        $query->from($db->quoteName($tableName));
        $query->where($db->quoteName('id') . " = 1");
        $db->setQuery($query);
        $result = $db->loadAssoc();
        return $result;
    }

    public static function __getDBValuesUsingColumns($type, $table, $fbkey)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select($type);
        $query->from($table);
        $query->where($db->quoteName('extension_id') . " = " . $db->quote($fbkey));
        $db->setQuery($query);
        $result = $db->loadColumn();
        return $result;
    }

    public static function __getDBLoadResult($col_name, $table_name)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select($col_name);
        $query->from($db->quoteName($table_name));
        $query->where($db->quoteName('id') . " = 1");
        $db->setQuery($query);
        $result = $db->loadResult();
        return $result;
    }

    public static function __loadObjectList($table)
    {
        $db2 = JFactory::getDbo();
        $query2 = $db2->getQuery(true);
        $query2->select('*');
        $query2->from($db2->quoteName($table));
        $db2->setQuery($query2);
        $rows = $db2->loadObjectList();
        return $rows;
    }

    public static function __getDBValuesArray($table)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select(array('*'));
        $query->from($db->quoteName($table));
        $query->where($db->quoteName('id') . " = 1");
        $db->setQuery($query);
        $customerResult = $db->loadAssoc();
        return $customerResult;
    }

    public static function __genDBUpdate($db_table, $db_coloums)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        foreach ($db_coloums as $key => $value) {
            $database_values[] = $db->quoteName($key) . ' = ' . $db->quote($value);
        }

        $query->update($db->quoteName($db_table))->set($database_values)->where($db->quoteName('id') . " = 1");
        $db->setQuery($query);
        $db->execute();
    }
}