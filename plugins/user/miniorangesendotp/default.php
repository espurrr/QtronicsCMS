<?php
/**
 * @package     Joomla.User
 * @subpackage  plg_user_miniorangesendotp
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Restricted access');

echo '<html>
        <head>
	    	<link rel="stylesheet" type="text/css" href="' . JURI::root() . 'plugins/user/miniorangesendotp/media/css/mo_customer_validation_style.min.css"/>			
		   		<meta http-equiv="X-UA-Compatible" content="IE=edge">
				<meta name="viewport" content="width=device-width, initial-scale=1">
				<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
		</head>
		<body>
			<div class="mo-modal-backdrop">
				<div class="mo_customer_validation-modal" tabindex="-1" role="dialog" id="mo_site_otp_form">
					<div class="mo_customer_validation-modal-backdrop"></div>
						<div class="mo_customer_validation-modal-dialog mo_customer_validation-modal-md">
							<div class="login mo_customer_validation-modal-content">
								<div class="mo_customer_validation-modal-header">
									<b>' . "Validate OTP (One Time Passcode)" . '</b>
									<a class="close" href="#" onclick="mo_validation_goback();" >' . '&larr; Go Back' . '</a>
								</div>
								<div class="mo_customer_validation-modal-body center">
					    			<div>' . $message . '</div><br /> ';

if (!MoUtility::isBlank($user_email)) {
    echo '<div class="mo_customer_validation-login-container">
			    <form id="mo_validate_form" name="f" method="post" action="">
			    	<input type="hidden" name="option1" value="miniorange-validate-otp-form" />';
    echo JHtml::_('form.token');
    echo '<input type="number" name="mo_customer_validation_otp_token" autofocus="true" placeholder="" id="mo_customer_validation_otp_token" 
				required="true" class="mo_customer_validation-textbox" autofocus="true" 
														pattern="[0-9]{4,8}" 
													title="' . "Only digits within range 4-8 are allowed." . '"/>
														<br />
													<input type="submit" name="miniorange_otp_token_submit" 
														id="miniorange_otp_token_submit" class="miniorange_otp_token_submit"  
														value="' . "Validate OTP" . '" />
													<input type="hidden" name="otp_type" value="' . $otp_type . '">';
    if (!$from_both) {
        echo '											<input type="hidden" id="from_both" name="from_both" value="false">
														<a style="float:right"  onclick="mo_otp_verification_resend();" class="miniorange_otp_token_submit">
															' . "Resend OTP" . '</a>';
    } else {
        echo '											<input type="hidden" id="from_both" name="from_both" value="true">
														<a style="float:right"  onclick="mo_select_goback();">
															' . "Resend OTP" . '</a>';
    }
	extra_post_data($postdata);	
    echo '									</form>
												<div id="mo_message" hidden 
													style="background-color: #f7f6f7;padding: 1em 2em 1em 1.5em;color:black;">' . $img . '</div>
											</div>';
}
echo '						</div>
								</div>
							</div>
						</div>
					</div>
					<form name="f" method="post" action="" id="validation_goBack_form">
						<input id="validation_goBack" name="option1" value="validation_goBack" type="hidden"></input>';
echo JHtml::_('form.token');
echo '	</form>
					
					<form name="f" method="post" action="" id="verification_resend_otp_form">
						<input id="verification_resend_otp" name="option1" value="verification_resend_otp_' . $otp_type . '" type="hidden"></input>';
echo JHtml::_('form.token');
if (!$from_both)
    echo '				<input type="hidden" id="from_both" name="from_both" value="false">';
else
    echo '				<input type="hidden" id="from_both" name="from_both" value="true">';

extra_post_data($postdata);


echo '		</form>

					<form name="f" method="post" action="" id="goBack_choice_otp_form">
						<input id="verification_resend_otp" name="option1" value="verification_resend_otp_both" type="hidden"></input>
						<input type="hidden" id="from_both" name="from_both" value="true">';

extra_post_data($postdata);

echo '		</form>

					<style> .mo_customer_validation-modal{ display: block !important; } </style>
					<script>
						function mo_validation_goback(){
							document.getElementById("validation_goBack_form").submit();
						}
						
						function mo_otp_verification_resend(){
							document.getElementById("verification_resend_otp_form").submit();
						}

						function mo_select_goback(){
							document.getElementById("goBack_choice_otp_form").submit();
						}
						jQuery(document).ready(function() {
							$mo = jQuery;
							$mo("#mo_validate_form").submit(function(){
								$mo(this).hide();
								$mo("#mo_message").show();
							});
						});
					</script>
				</body>
		    </html>';