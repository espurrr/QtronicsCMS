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
 * Checks if the customer is registered or not and shows a message on the page 
 * to the user so that they can register or login themselves to use the plugin.
 */
function is_customer_registered()
{
	$registration_url = add_query_arg( array('page' => 'otpaccount'), $_SERVER['REQUEST_URI'] );
	if(MoUtility::micr())  return;
	echo '<div style="display:block;margin-top:10px;color:red;background-color:rgba(251, 232, 0, 0.15);
						padding:5px;border:solid 1px rgba(255, 0, 9, 0.36);">
	 <a href="'.$registration_url.'">'."Register or Login with miniOrange" .'</a> 
	 	'. "to enable OTP Verification".'</div>';
}


/**
 * This displays a link next to the name of each of the forms under the 
 * forms tab so that user can see if the form in question is the correct 
 * form.
 * 
 * @param  $formalink - the link to the forms main page.
 */
function get_plugin_form_link($formalink)
{
	echo '<a class="dashicons dashicons-admin-page" href="'.$formalink.'" title="'.$formalink.'" ></a>';
}


/**
 * Display a tooltip with the appropriate header and message on the page
 * 
 * @param  $header  - the header of the tooltip
 * @param  $message - the body of the tooltip message
 */
function mo_draw_tooltip($header,$message)
{
	echo '<span class="tooltip">
			<span class="dashicons dashicons-editor-help"></span>
			<span class="tooltiptext"><span class="header"><b><i>'.  $header.'</i></b></span><br/><br/>
			<span class="body">'.$message.'</span></span>
		  </span>';
}


/**
 * This is used to display extra post data as hidden fields in the verification 
 * page so that it can used later on for processing form data after verification
 * is complete and successful.
 * 
 * @param  $data - the data posted by the user using the form
 * 
*/

function extra_post_data($data=null)
{

	$mo_fields 		= array('option1','mo_customer_validation_otp_token','miniorange_otp_token_submit',
							'miniorange-validate-otp-choice-form','submit','mo_customer_validation_otp_choice');
	foreach ($data as $key => $value)
	{
        if($key != 'phone_number_mo') {
            if (!in_array($key, $mo_fields))
                show_hidden_fields($key, $value);
            if ($key == 'g-recaptcha-response' && isset($_REQUEST['g-recaptcha-response']))
                echo '<input type="hidden" name="g-recaptcha-response" value="' . $data['g-recaptcha-response'] . '" />';
            if (isset($data['attendee'])) {
                $i = 0;
                while ($i < count($data['attendee'])) {
                    echo ' <input type="hidden" name="attendee[' . $i . '][first_name]" value="' . $data["attendee"][$i]["first_name"] . '">';
                    echo ' <input type="hidden" name="attendee[' . $i . '][last_name]" value="' . $data["attendee"][$i]["last_name"] . '">';
                    $i++;
                }
            }
        }
	}
}


/**
 * Show hidden fields. Makes hidden input fields on the page.
 * @param  $key   - the name attribute of the hidden field
 * @param  $value - the value of the input field
 */
function show_hidden_fields($key,$value)
{
	if(is_array($value))
		foreach ($value as $t => $val)
			show_hidden_fields($key.'['.$t.']',$val);
	else	
		echo '<input type="hidden" name="'.$key.'" value="'.$value.'" />';
}


/**
 * The HTML code to display the OTP Verification pop up with appropriate messaging
 * and hidden fields for later processing. 
 */
function miniorange_site_otp_validation_form($user_login,$user_email,$phone_number,$message,$otp_type,$from_both)
{
	if(!headers_sent()) header('Content-Type: text/html; charset=utf-8');
	$postdata=JFactory::getApplication()->input->get->post->getArray();
	//$img = "<div style='display:table;text-align:center;'><img src='".MOV_LOADER_URL."'></div>";
	$img= '<div style="display:table;text-align:center;"><img src="' . JURI::root() . 'plugins/user/miniorangesendotp/media/images/loader.gif"></div>';
	include 'default.php';
	die();
}

/**
 * Display the user choice popup where user can choose between email or
 * sms verification. 
 */
function miniorange_verification_user_choice($user_login, $user_email,$phone_number,$message,$otp_type)
{
	include 'userchoice.php';
	die();
}