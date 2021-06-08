<?php
/**
 * @package     Joomla.User
 * @subpackage  plg_user_miniorangesendotp
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Restricted access');
require_once 'miniorangesendotp.php';

/*
	add_action(	'init', 'miniorange_customer_validation_handle_form' , 1 );
	add_action( 'mo_validate_otp', '_handle_validation_form_action' , 1, 2);
	add_filter('mo_filter_phone_before_api_call','_filter_phone_before_api_call',1,1);
	*/

/**
 * This function is called from every form handler class to start the OTP
 * Verification process. Keeps certain variables in session and start the
 * OTP Verification process.
 *
 * @param $user_login - username submitted by the user
 * @param $user_email - email submitted by the user
 * @param $errors - error variable ( currently not being used )
 * @param $phone_number - phone number submitted by the user
 * @param $otp_type - email or sms verification
 * @param $password - password submitted by the user
 * @param $extra_data - an array containing all the extra data submitted by the user
 * @param $from_both - denotes if user has a choice between email and phone verification
 */

function miniorange_site_challenge_otp($user_login, $user_email, $errors, $phone_number = null, $otp_type, $password = "", $extra_data = null, $from_both = false)
{
    //MoUtility::checkSession();
    //$phone_number = MoUtility::processPhoneNumber($phone_number);
    $session = JFactory::getSession();
    $session->set('current_url', MoUtility::currentPageUrl());
    $session->set('user_email', $user_email);
    $session->set('user_login', $user_login);
    $session->set('user_password', $password);
    $session->set('phone_number_mo', $phone_number);
    $session->set('extra_data', $extra_data);

    _handle_otp_action($user_login, $user_email, $phone_number, $otp_type, $from_both, $extra_data);
}


/**
 * This function is called to handle the resend OTP Verification process.
 *
 * @param $otp_type - email or sms verification
 * @param $from_both - denotes if user has a choice between email and phone verification
 */
function _handle_verification_resend_otp_action($otp_type, $from_both = false)
{
    MoUtility::checkSession();
    $session = JFactory::getSession();
    $user_email = $session->get('user_email');
    $user_login = $session->get('user_login');
    $password = $session->get('user_password');
    $phone_number = $session->get('phone_number_mo');
    $extra_data = $session->get('extra_data');
    _handle_otp_action($user_login, $user_email, $phone_number, $otp_type, $from_both, $extra_data);
}


/**
 * This function starts the email or sms verification depending on the otp type.
 *
 * @param $user_login - username submitted by the user
 * @param $user_email - email submitted by the user
 * @param $phone_number - phone number submitted by the user
 * @param $otp_type - email or sms verification
 * @param $from_both - denotes if user has a choice between email and phone verification
 * @param $extra_data - an array containing all the extra data submitted by the user
 */
function _handle_otp_action($user_login, $user_email, $phone_number, $otp_type, $from_both, $extra_data)
{
    global $phoneLogic, $emailLogic;
    switch ($otp_type) {
        case 'phone':
            $phoneLogic->_handle_logic($user_login, $user_email, $phone_number, $otp_type, $from_both);
            break;
        case 'email':
            $emailLogic->_handle_logic($user_login, $user_email, $phone_number, $otp_type, $from_both);
            break;
        case 'both':
            miniorange_verification_user_choice($user_login, $user_email, $phone_number, MoMessages::showMessage('CHOOSE_METHOD'), $otp_type);
            break;
        /*case 'external':
            mo_external_phone_validation_form($extra_data['curl'], $user_email, $extra_data['message'], $extra_data['form'], $extra_data['data']);
            break;*/
    }
}


/**
 * This function handles which page to redirect the user to when he
 * clicks on the go back link on the OTP Verification pop up.
 */
function _handle_validation_goBack_action()
{

    MoUtility::checkSession();
    $session = JFactory::getSession();
    $current_url = $session->get('current_url');

    $url = isset($current_url) ? $current_url : '';
    echo $url;
    PlgUserMiniorangesendotp::unsetOTPSessionVariables();

    //do_action('unset_session_variable');
    header("location:" . $url);
}


/**
 * This function is called from each form class to validate the otp entered by the
 * user.
 *
 * @param $requestVariable - the request variable to fetch OTP from
 * @param $otp_token - the otp token itself
 * @param $from_both - if user has option to choose between email or phone verification
 */
function _handle_validation_form_action($requestVariable = 'mo_customer_validation_otp_token', $otp_token = NULL)
{
    MoUtility::checkSession();
    $session = JFactory::getSession();

    //$current_url= $session->get('current_url');
    $user_login = $session->get('user_login');
    $user_email = $session->get('user_email');
    $phone_number_mo = $session->get('phone_number_mo');
    $user_password = $session->get('user_password');
    $extra_data = $session->get('extra_data');
    $user_login = !MoUtility::isBlank($user_login) ? $user_login : null;
    $user_email = !MoUtility::isBlank($user_email) ? $user_email : null;
    $phone_number = !MoUtility::isBlank($phone_number_mo) ? $phone_number_mo : null;
    $password = !MoUtility::isBlank($user_password) ? $user_password : null;
    $extra_data = !MoUtility::isBlank($extra_data) ? $extra_data : null;
    $session = JFactory::getSession();
    $txID = $session->get('test');
    $otp_token = !is_null($requestVariable) && array_key_exists($requestVariable, $_REQUEST)
    && !MoUtility::isBlank($_REQUEST[$requestVariable]) ? $_REQUEST[$requestVariable] : $otp_token;

    if (!is_null($otp_token)) {
        $content = json_decode(MocURLOTP::validate_otp_token($txID, $otp_token),true);
        switch ($content['status']) {
            case 'SUCCESS':
                _handle_success_validated($user_login, $user_email, $password, $phone_number, $extra_data);
                $user_exist = JUserHelper::getUserId($user_login);
    
               /* if($user_exist != null){
                    $user = JUser::getInstance($user_login);
                    $session->set('user', $user);
                    $app = JFactory::getApplication();
                    $app->checkSession();
                    $sessionId = $session->getId();
                    MoUtility::updateUsernameToSessionId($user->id, $user->username, $sessionId);
                    $user->setLastVisit();

                    $result = MoOtpUtility::__getDBValuesWOArray('#__miniorange_otp_customer');

                    if(isset($result['redirect_after_login']) && ($result['redirect_after_login'] != ""))
                            $login_redirect_url = $result['redirect_after_login'];
                        else
                            $login_redirect_url = JURI::root().'index.php';    
                        $app->redirect($login_redirect_url);
                }*/
                break;
            default:
                _handle_error_validated($user_login, $user_email, $phone_number);
                break;
        }
    }
}

/**
 * This function is called to handle what needs to be done if OTP
 * entered by the user is validated successfully. Calls an action
 * which could be hooked into to process this elsewhere. Check each
 * handle_post_verification of each form handler.
 *
 * @param $user_login - username submitted by the user
 * @param $user_email - email submitted by the user
 * @param $phone_number - phone number submitted by the user
 * @param $password - password submitted by the user
 * @param $extra_data - an array containing all the extra data submitted by the user
 */
function _handle_success_validated($user_login, $user_email, $password, $phone_number, $extra_data)
{
    if (isset($phone_number)) {
        $userId = JUserHelper::getUserId($user_login);

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $fields = array(
            $db->quoteName('profile_value') . ' = ' . $db->quote($phone_number),
            $db->quoteName('ordering') . ' = 2'
        );
        $conditions = array(
            $db->quoteName('user_id') . ' = ' . $db->quote($userId),
            $db->quoteName('profile_key') . ' = ' . $db->quote('profile.phone')
        );
        $query->update($db->quoteName('#__user_profiles'))->set($fields)->where($conditions);
        $db->setQuery($query);
        $result = $db->execute();
    }

    $redirect_to = array_key_exists('redirect_to', JFactory::getApplication()->input->get->post) ? JFactory::getApplication()->input->get->post(['redirect_to']) : '';
    MoUtility::checkSession();
    $session = JFactory::getSession();
    $session->set('formvalidation', 'success');
}

/**
 * This function is called to handle what needs to be done if OTP
 * entered by the user is not a valid OTP and fails the verification.
 * Calls an action which could be hooked into to process this elsewhere.
 * Check each handle_post_verification of each form handler.
 *
 * @param $otp_type - email or sms verification
 * @param $from_both - denotes if user has a choice between email and phone verification
 */
function _handle_error_validated($user_login, $user_email, $phone_number)
{
    MoUtility::checkSession();

    $customer_details = MoOtpUtility::getCustomerDetails();
    $otpVerType = $customer_details['login_otp_type'];
    $fromBoth = strcasecmp($otpVerType, "both") == 0 ? TRUE : FALSE;
    miniorange_site_otp_validation_form($user_login, $user_email, $phone_number, MoUtility::_get_invalid_otp_method(), $otpVerType, $fromBoth);
}


/**
 * This function starts the OTP verification process based on user input.
 * starts Email or Phone Verification based on user input.
 *
 * @param $postdata - the data posted
 */
function _handle_validate_otp_choice_form($postdata)
{
    MoUtility::checkSession();
    $session = JFactory::getSession();
    //$current_url= $session->get('current_url');
    $user_login = $session->get('user_login');
    $user_email = $session->get('user_email');
    $phone_number_mo = $session->get('phone_number_mo');
    $user_password = $session->get('user_password');
    $extra_data = $session->get('extra_data');

    if (strcasecmp($postdata['mo_customer_validation_otp_choice'], 'user_email_verification') == 0)
        miniorange_site_challenge_otp($user_login, $user_email, null, $phone_number_mo, "email", $user_password, $extra_data, true);
    else
        miniorange_site_challenge_otp($user_login, $user_email, null, $phone_number_mo, "phone", $user_password, $extra_data, true);
}


/**
 * This function filters the phone number before making any api calls.
 * This is mostly used in the on-prem plugin to filter the phone number
 * before the api call is made to send OTPs.
 *
 * @param $phone - the phone number to be processed
 */
function _filter_phone_before_api_call($phone)
{
    return str_replace("+", "", $phone);
}


/**
 * This function hooks into the init joomla hook. This function processes the
 * form post data and calls the correct function to process the posted data.
 * This mostly handles all the plugin related functionality.
 */
function miniorange_customer_validation_handle_form()
{
    if (array_key_exists('option1', $_REQUEST)) {
        switch (trim($_REQUEST['option1'])) {
            case "validation_goBack":
                _handle_validation_goBack_action();
                break;
            case "miniorange-validate-otp-form":
                _handle_validation_form_action();
                break;
            case "verification_resend_otp_phone":
                _handle_verification_resend_otp_action("phone");
                break;
            case "verification_resend_otp_email":
                $from_both = JFactory::getApplication()->input->get->post(['from_both']) == 'true' ? true : false;
                _handle_verification_resend_otp_action("email", $from_both);
                break;
            case "verification_resend_otp_both":
                $from_both = JFactory::getApplication()->input->get->post(['from_both']) == 'true' ? true : false;
                _handle_verification_resend_otp_action("both", $from_both);
                break;
            case "miniorange-validate-otp-choice-form":
                _handle_validate_otp_choice_form(JFactory::getApplication()->input->get->post);
                break;
            case "check_mo_ln":
                MoUtility::_handle_mo_check_ln(true,
                    "mo_customer_validation_admin_customer_key",
                    "mo_customer_validation_admin_api_key"
                );
                break;
        }
    }
} 