<?php
/**
 * @package     Joomla.User
 * @subpackage  plg_user_miniorangesendotp
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined( '_JEXEC' ) or die( 'Restricted access' );

class MoUtility
{

	public static function get_hidden_phone($phone)
	{
		return 'xxxxxxx' . substr($phone,strlen($phone) - 3);
	}

	public static function isBlank( $value )
	{
		if( ! isset( $value ) || empty( $value ) ) return TRUE;
		return FALSE;
	}

	public static function _create_json_response($message,$type)
	{
		return array( 'message' => $message, 'result' => $type);
	}

	public static function mo_is_curl_installed()
	{
		if  (in_array  ('curl', get_loaded_extensions()))
			return 1;
		else 
			return 0;
	}

	public static function currentPageUrl()
	{
		$pageURL = 'http';

		if ((isset($_SERVER["HTTPS"])) && ($_SERVER["HTTPS"] == "on"))
			$pageURL .= "s";

		$pageURL .= "://";

		if ($_SERVER["SERVER_PORT"] != "80")
			$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];

		else
			$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];

		if ( function_exists('apply_filters') ) apply_filters('wppb_curpageurl', $pageURL);

        return $pageURL;
	}

	public static function getDomain($email)
	{
		return $domain_name = substr(strrchr($email, "@"), 1);
	}

	public static function validatePhoneNumber($phone)
	{
		return preg_match(MoConstants::PATTERN_PHONE,MoUtility::processPhoneNumber($phone),$matches);
	}

	public static function isCountryCodeAppended($phone)
	{
		return preg_match(MoConstants::PATTERN_COUNTRY_CODE,$phone,$matches) ? true : false;
	}

	public static function processPhoneNumber($phone)
	{
		$phone = preg_replace(MoConstants::PATTERN_SPACES_HYPEN,"", ltrim(trim($phone),'0'));
		$defaultCountryCode = CountryList::getDefaultCountryCode();
		$phone = !isset($defaultCountryCode) || MoUtility::isCountryCodeAppended($phone) ? $phone : $defaultCountryCode.$phone;
		return $phone;	
	}

	public static function areFormOptionsBeingSaved()
	{
		$option = JFactory::getApplication()->input->get->post(['option']);
		return current_user_can('manage_options') 
				&& MoUtility::micr() 
				&& isset($option)
				&& 'mo_customer_validation_settings'==JFactory::getApplication()->input->get->post(['option']);
	}

	public static function micr()
	{
		$email 			= get_mo_option('mo_customer_validation_admin_email');
		$customerKey 	= get_mo_option('mo_customer_validation_admin_customer_key');
		if( ! $email || ! $customerKey || ! is_numeric( trim( $customerKey ) ) )
			return 0;
		else
			return 1;
	}

	public static function rand()
	{
		$length =  rand(0,15);
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, strlen($characters) - 1)];
		}
		return $randomString;
	}

	public static function micv()
	{
		$email 			= get_mo_option('mo_customer_validation_admin_email');
		$customerKey 	= get_mo_option('mo_customer_validation_admin_customer_key');
		if( ! $email || ! $customerKey || ! is_numeric( trim( $customerKey ) ) )
			return 0;
		else
			return get_mo_option('mo_customer_check_ln') ? get_mo_option('mo_customer_check_ln') :  0;
	}

	public static function checkSession()
	{
		$session = JFactory::getSession();
		if (session_id() == '' || !isset($session)) session_start();
	}

	public static function _handle_mo_check_ln($showMessage,$customerKey,$apiKey)
	{
		$msg = 'FREE_PLAN_MSG';
		$plan = array();
		$content = json_decode(MocURLOTP::check_customer_ln($customerKey,$apiKey), true);
		if(strcasecmp($content['status'], 'SUCCESS') == 0)
		{
			array_key_exists('licensePlan', $content) && isset($content['licensePlan']) 
				? update_mo_option('mo_customer_check_ln',$content['licensePlan']) : update_mo_option('mo_customer_check_ln','');
			if(array_key_exists('licensePlan', $content) && isset($content['licensePlan']))
			{
				$msg = 'UPGRADE_MSG';
				$plan = array('plan'=>$content['licensePlan']);
			}
			$emailRemaining = isset($content['emailRemaining']) ? $content['emailRemaining'] : 0;
			$smsRemaining = isset($content['smsRemaining']) ? $content['smsRemaining'] : 0;
			update_mo_option('mo_customer_email_transactions_remaining',$emailRemaining);
			update_mo_option('mo_customer_phone_transactions_remaining',$smsRemaining);
		} 
		if($showMessage)
			do_action('mo_registration_show_message', MoMessages::showMessage($msg,$plan),'SUCCESS');
	}

	public static function _get_invalid_otp_method()
	{

        $result = MoOtpUtility::_get_custom_message();
        $invalid_otp = "Invalid one time passcode. Please enter a valid passcode.";
        if (isset($result['mo_custom_invalid_otp_message'])){
            if (!empty($result['mo_custom_invalid_otp_message'])){
                $invalid_otp = $result['mo_custom_invalid_otp_message'];
            }
        }

        if(!empty($invalid_otp)){
            return $invalid_otp;
        }else{
            return MoMessages::showMessage('ERROR_OTP_PHONE');
        }
	}

	public static function updateUsernameToSessionId($userID, $username, $sessionId)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $fields = array(
            $db->quoteName('username') . ' = ' . $db->quote($username),
            $db->quoteName('guest') . ' = ' . $db->quote('0'),
            $db->quoteName('userid') . ' = ' . $db->quote($userID),
        );

        $conditions = array(
            $db->quoteName('session_id') . ' = ' . $db->quote($sessionId)
        );

        $query->update($db->quoteName('#__session'))->set($fields)->where($conditions);
        $db->setQuery($query);
        $result = $db->execute();
    }

	public static function _is_polylang_installed()
	{
		return function_exists('pll__') && function_exists('pll_register_string');
	}

	public static function checkIfValidSubmission()
	{
		return ( !current_user_can( 'manage_options' ) || !self::micr()
		|| !check_admin_referer( MoConstants::FORM_NONCE )) ? FALSE : TRUE; 
	}

	public static function mclv()
	{
		return FALSE;
	}
}