<?php

/**
 * @package     Joomla.User
 * @subpackage  plg_user_miniorangesendotp
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined( '_JEXEC' ) or die( 'Restricted access' );


/**
 * This is the constant class which lists all the messages
 * to be shown in the plugin. 
 */
class MoMessages
{
	function __construct()
	{
		//created an array instead of messages instead of constant variables for Translation reasons.
		define("MO_MESSAGES", serialize( array(			
			//General Messages
			"OTP_SENT_PHONE" 		 => "An OTP (One Time Passcode) has been sent to ##phone##. Please enter the OTP in the field below to verify your phone." ,
			"OTP_SENT_EMAIL" 		 => "A One Time Passcode has been sent to ##email##. Please enter the OTP below to verify your Email Address. If you cannot see the email in your inbox, make sure to check your SPAM folder." ,
			"ERROR_OTP_EMAIL" 		 => "There was an error in sending the OTP. Please enter a valid email id or contact site Admin." ,
			"ERROR_OTP_PHONE" 		 => "There was an error in sending the OTP to the given Phone Number. Please Try Again or contact site Admin." ,
			"ERROR_PHONE_FORMAT" 	 => "##phone## is not a valid phone number. Please enter a valid Phone Number. E.g:+1XXXXXXXXXX" ,
			"CHOOSE_METHOD" 		 => "Please select one of the methods below to verify your account. A One time passcode will be sent to the selected method." ,
			"PLEASE_VALIDATE" 		 => "You need to verify yourself in order to submit this form" ,
			"ERROR_PHONE_BLOCKED"	 => "Your country code has been blocked by the user. Please Try a different number or Contact site Admin." ,
			"ERROR_EMAIL_BLOCKED"	 => "Your domain has been blocked by the user. Please Try a different email or Contact site Admin." ,
			"EMAIL_FORMAT"	         => "##email## is not a valid email address. Please enter a valid Email Address. E.g:abc@abc.abc" ,
			"COMMON_MESSAGES"        => "Invalid one time passcode. Please enter a valid passcode.",

			//ToolTip Messages
			"FORM_NOT_AVAIL_HEAD" 	 => "MY FORM IS NOT IN THE LIST" ,
			"FORM_NOT_AVAIL_BODY" 	 => "We are actively adding support for more forms. Please contact us using the support form on your right or email us at info@xecurify.com. While contacting us please include enough information about your registration form and how you intend to use this plugin. We will respond promptly." ,
			"CHANGE_SENDER_ID_BODY"  => "SenderID/Number is gateway specific. You will need to use your own SMS gateway for this." ,
			"CHANGE_SENDER_ID_HEAD"  => "CHANGE SENDER ID / NUMBER" ,
			"CHANGE_EMAIL_ID_BODY"   => "Sender Email is gateway specific. You will need to use your own Email gateway for this." ,
			"CHANGE_EMAIL_ID_HEAD"   => "CHANGE SENDER EMAIL ADDRESS" ,
			"INFO_HEADER" 			 => "WHAT DOES THIS MEAN?" ,
			"META_KEY_HEADER"		 => "WHAT IS A META KEY?" ,
			"META_KEY_BODY"		 	 => "WordPress stores addtional user data like phone number, age etc in the usermeta table in a key value pair. MetaKey is the key against which the additional value is stored in the usermeta table." ,
			"ENABLE_BOTH_BODY"		 => "New users can validate their Email or Phone Number using either Email or Phone Verification.s They will be prompted during registration to choose one of the two verification methods." ,
			"COUNTRY_CODE_HEAD" 	 => "DON'T WANT USERS TO ENTER THEIR COUNTRY CODE?" ,
			"COUNTRY_CODE_BODY" 	 => "Choose the default country code that will be appended to the phone number entered by the users. This will allow your users to enter their phone numbers in the phone field without a country code." ,

			//Support Query Messages			
			"SUPPORT_FORM_VALUES" 	 => "Please submit your query along with email." ,
			"SUPPORT_FORM_SENT" 	 => "Thanks for getting in touch! We shall get back to you shortly." ,
			"SUPPORT_FORM_ERROR" 	 => "Your query could not be submitted. Please try again." ,
			



			//UserPro Registration Form
			"USERPRO_CHOOSE" 		 => "Please choose a Verification Method for UserPro Registration Form." ,
			"USERPRO_VERIFY" 		 => "Please verify yourself before submitting the form." ,
			

			//License Messages
			"UPGRADE_MSG" 			 => "Thank you. You have upgraded to {{plan}}." ,
			"FREE_PLAN_MSG" 		 => "You are on our FREE plan. Check Licensing Tab to learn how to upgrade." ,


		)));
	}


	/** 
	 * This function is used to fetch and process the Messages to 
	 * be shown to the user. It was created to mostly show dynamic
	 * messages to the user.
	 */
	public static function showMessage($messageKeys , $data=array())
	{
		$displayMessage = "";
		$messageKeys = explode(" ",$messageKeys);
		$messages = unserialize(MO_MESSAGES);
		foreach ($messageKeys as $messageKey) 
		{
			if(MoUtility::isBlank($messageKey)) return $displayMessage;
			$formatMessage = $messages[$messageKey];
		    foreach($data as $key => $value)
		    {
		        $formatMessage = str_replace("{{" . $key . "}}", $value ,$formatMessage);
		    }
		    $displayMessage.=$formatMessage;
		}
	    return $displayMessage;
	}
}	
new MoMessages;


/** 
 * This function is used to DisplayMessages in WordPress. You
 * can decide the HTML code to show your message based on the 
 * type of the message you want to show.
 */
class MoDisplayMessages
{
	private $message;
	private $type;

	function __construct( $message,$type ) 
	{
        $this->_message = $message;
        $this->_type = $type;
        add_action( 'admin_notices', array( $this, 'render' ) );
    }

    function render() 
    {
    	switch ($this->_type) 
    	{
    		case 'CUSTOM_MESSAGE':
    			echo  $this->_message;																										break;
    		case 'NOTICE':
    			echo '	<div style="margin-top:1%;" class="is-dismissible notice notice-warning"> <p>'.$this->_message.'</p> </div>';		break;
    		case 'ERROR':
    			echo '	<div style="margin-top:1%;" class="notice notice-error is-dismissible"> <p>'.$this->_message.'</p> </div>';		break;
    		case 'SUCCESS':
    			echo '	<div style="margin-top:1%;" class="notice notice-success is-dismissible"> <p>'.$this->_message.'</p> </div>';		break;
    	}
    }
}