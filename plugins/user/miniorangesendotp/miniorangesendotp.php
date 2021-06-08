<?php
/**
 * @package     Joomla.User
 * @subpackage  plg_user_miniorangesendotp
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// no direct access

jimport('joomla.user.helper');

defined('_JEXEC') or die('Restricted access');

/**
 * miniOrange OTP Plugin plugin
 */

require_once 'common-elements.php';
require_once 'messages.php';
require_once 'miniorange_logic_interface.php';
require_once 'miniorange_form_handler.php';
require_once 'miniorange_email_logic.php';
require_once 'miniorange_phone_logic.php';
require_once 'constants.php';
require_once 'moutility.php';
require_once 'curl.php';

class PlgUserMiniorangesendotp extends JPlugin
{
    //private $formSessionVar = MoConstants::Joomla_REG;

    /*OTP verification During Registration time*/

    public function onUserBeforeSave($oldUser, $isnew, $newuser)
    {

        require_once JPATH_ROOT . DIRECTORY_SEPARATOR . 'administrator' . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR .
            'com_joomlaotp' . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'mo_otp_utility.php';

        $customer_details           = MoOtpUtility::getCustomerDetails();
        $login_status               = $customer_details['login_status'];
        $registration_status        = $customer_details['registration_status'];
        $registration_otp_type      = $customer_details['registration_otp_type'];
        $enable_during_registration = $customer_details['enable_during_registration'];
        $default_country_code       = self::getCustomerConfig();
        
        if ($enable_during_registration != '1') return;

        if (MoOtpUtility::is_customer_registered()) {
            if ($this->checkIfVerificationIsComplete()) return $errors;
            $phone_number = NULL;
            $errors = NULL;
            foreach ($newuser as $key => $value) {
                if ($key == "username")
                    $username = $value;
                elseif ($key == "email1")
                    $email = $value;
                elseif ($key == "password1")
                    $password = $value;
                elseif ($key == "profile")
                    $phone_number = $value['phone'];
                else
                    $extra_data[$key] = $value;
            }
            $tab = JFactory::getApplication()->input->get->get('task');
            if (isset($tab) && $tab == 'registration.register') {

                $phone_number = str_replace(" ", "", $phone_number);
                $phno = strlen($phone_number);
                $phbr = substr($phone_number, 0, 1);

                if ($phbr != '+') {
                    if (!empty($default_country_code)) {
                        $phone_number = '+'.$default_country_code.$phone_number;
                        $phbr = '+';
                    }
                }

                if ($phone_number != '') {
                    if ($phno <= 4 || $phno >= 18 || $phbr != '+') {
                        $result = MoOtpUtility::_get_custom_message();
                        $invalid_format = isset($result['mo_custom_phone_invalid_format_message']) ? $result['mo_custom_phone_invalid_format_message'] : '';
                        if(!empty($invalid_format)){
                            $invalid_format = str_replace("##phone##",$phone_number,$invalid_format);
                            $app = JFactory::getApplication();
                            $app->enqueueMessage($invalid_format, 'error');
                            $app->redirect(JRoute::_('index.php'));
                        }else{
                            $app = JFactory::getApplication();
                            $app->enqueueMessage('Enter a valid phone number with country code e.g.+1XXXXXXXXXXX', 'error');
                            $app->redirect(JRoute::_('index.php'));
                        }
                    }
                }
                $this->startVerificationProcess($registration_otp_type, $username, $email, $errors, $phone_number, $password, $extra_data);
                //MoCurlOTP::mo_send_otp_token('EMAIL',$newuser["email1"],'');
            }
        }
    }

    /*OTP verification During Login time*/

    public function onUserLogin($user, $options = array())
    {

    }

    /*Allow the administrator to bypass OTP verification during login */

    public function adminBypass($user)
    {
        $userId = JUserHelper::getUserId($user['username']);
        $groups = JAccess::getGroupsByUser($userId);
        foreach ($groups as $key => $value) {
            if ($value == 7 || $value == 8) {
                return true;
            }
        }
    }

    function checkIfVerificationIsComplete()
    {
        $session = JFactory::getSession();
        $formvalidation = $session->get('formvalidation');
        if (isset($formvalidation) && $formvalidation == 'success') {
            $this->unsetOTPSessionVariables();
            return TRUE;
        }
        return FALSE;
    }

    public static function unsetOTPSessionVariables()
    {
        $session = JFactory::getSession();
        $formvalidation = $session->get('formvalidation');
        $test = $session->get('test');
        $form = $session->set('formvalidation', 'Done');
        unset($test);
        unset($form);
    }

    public static function startVerificationProcess($_otp_type, $username, $email, $errors, $phone_number, $password, $extra_data)
    {
        $default_country_code       = self::getCustomerConfig();
       
        if (empty($phone_number) && $_otp_type == 2) {
            $app = JFactory::getApplication();
            $app->enqueueMessage('Phone number is required by the administrator for OTP Verification.', 'error');
            $app->redirect(JRoute::_('index.php/component/users/?view=registration&Itemid=101'));
        } else if (!empty($phone_number) && $_otp_type == 2){
            $phone_number = str_replace(" ", "", $phone_number);
            $phno = strlen($phone_number);
            $phbr = $firstCharacter = substr($phone_number, 0, 1);
            if ($phbr != '+') {
                if (!empty($default_country_code)) {
                    $phone_number = '+'.$default_country_code.$phone_number;
                    $phbr = '+';
                }
            }

            if ($phone_number != '') {
                if ($phno <= 4 || $phno >= 18 || $phbr != '+') {
                    $result = MoOtpUtility::_get_custom_message();
                    $invalid_format = isset($result['mo_custom_phone_invalid_format_message']) ? $result['mo_custom_phone_invalid_format_message'] : '';
                    if (!empty($invalid_format)) {
                        $invalid_format = str_replace("##phone##",$phone_number,$invalid_format);
                        $app = JFactory::getApplication();
                        $app->enqueueMessage($invalid_format, 'error');
                        $app->redirect(JRoute::_('index.php'));
                    } else {
                        $app = JFactory::getApplication();
                        $app->enqueueMessage('Enter a valid phone number with country code e.g.+1XXXXXXXXXXX', 'error');
                        $app->redirect(JRoute::_('index.php'));
                    }
                }
            }
        }

        if ($_otp_type == '1') //email
            miniorange_site_challenge_otp($username, $email, $errors, $phone_number, 'email', $password, $extra_data);
        elseif ($_otp_type == '2') //phone
            miniorange_site_challenge_otp($username, $email, $errors, $phone_number, 'phone', $password, $extra_data);
    }

    public static function getCustomerConfig(){
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('mo_default_country_code');
        $query->from($db->quoteName('#__miniorange_otp_customer'));
        $query->where($db->quoteName('id') . " = 1");
        $db->setQuery($query);
        $result = $db->loadAssoc();
        $default_cont_code = $result['mo_default_country_code'];
        return $default_cont_code;
    }
}