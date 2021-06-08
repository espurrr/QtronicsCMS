<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_joomlaotp
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
JHtml::stylesheet(JURI::base() . 'components/com_joomlaotp/assets/css/miniorange_otp.css', array(), true);

if (MoOtpUtility::is_curl_installed() == 0) { ?>
    <p style="color:red;">(Warning: <a href="http://php.net/manual/en/curl.installation.php" target="_blank">PHP CURL
            extension</a> is not installed or disabled) Please go to Troubleshooting for steps to enable curl.</p>
    <?php
}
$otp_active_tab = 'account';
$tab1 = JFactory::getApplication()->input->get->post(['tab-panel']);
$tab2 = JFactory::getApplication()->input->get->post(['tab-panel']);

if (isset($tab1) && !empty($tab2)) {
    $otp_active_tab = JFactory::getApplication()->input->get(['tab-panel']);
}
$otp_active_tab = JFactory::getApplication()->input->get->getArray();
$otp_active_tab = isset($otp_active_tab['tab-panel']) ? $otp_active_tab['tab-panel'] : 'account';

?>

<div class="nav-tab-wrapper mo_otp_nav-tab-wrapper">
    <a id="mo_descritption" class="mo_nav-tab <?php echo $otp_active_tab == 'account' ? 'mo_nav_tab_active' : ''; ?>"
       href="#description"
       onclick="add_css_tab('#mo_descritption');"
       data-toggle="tab"><?php echo JText::_('COM_JOOMLAOTP_TAB2_ACCOUNT_SETUP'); ?>
    </a>

    <a id="mo_setting_tab" class="mo_nav-tab <?php echo $otp_active_tab == 'setting' ? 'mo_nav_tab_active' : ''; ?>"
       href="#setting"
       onclick="add_css_tab('#mo_setting_tab');"
       data-toggle="tab"><?php echo JText::_('COM_JOOMLAOTP_TAB2_SETTINGS_SETUP'); ?>
    </a>

    <a id="mo_select_custom_setting"
       class="mo_nav-tab <?php echo $otp_active_tab == 'custom_setting' ? 'mo_nav_tab_active' : ''; ?>"
       href="#custom_setting"
       onclick="add_css_tab('#mo_select_custom_setting');"
       data-toggle="tab"><?php echo JText::_('COM_JOOMLAOTP_TAB3_SETTINGS'); ?>
    </a>

    <a id="mo_custom_message"
       class="mo_nav-tab <?php echo $otp_active_tab == 'custom_message' ? 'mo_nav_tab_active' : ''; ?>"
       href="#custom_message"
       onclick="add_css_tab('#mo_custom_message');"
       data-toggle="tab"><?php echo JText::_('COM_JOOMLAOTP_TAB4_MESSAGES'); ?>
    </a>

    <a id="mo_configuration"
       class="mo_nav-tab <?php echo $otp_active_tab == 'configuration' ? 'mo_nav_tab_active' : ''; ?>"
       href="#configuration"
       onclick="add_css_tab('#mo_configuration');"
       data-toggle="tab"><?php echo JText::_('COM_JOOMLAOTP_TAB8_CONFIGURATION'); ?>
    </a>

    <a id="mo_upgrade_tab" class="mo_nav-tab <?php echo $otp_active_tab == 'upgrade' ? 'mo_nav_tab_active' : ''; ?>"
       href="#upgrade"
       onclick="add_css_tab('#mo_upgrade_tab');"
       data-toggle="tab"><?php echo JText::_('COM_JOOMLAOTP_TAB10_UPGRADE_PLANS'); ?>
    </a>

    <a id="mo_help_tab" class="mo_nav-tab <?php echo $otp_active_tab == 'help' ? 'mo_nav_tab_active' : ''; ?>"
       href="#help"
       onclick="add_css_tab('#mo_help_tab');"
       data-toggle="tab"><?php echo JText::_('COM_JOOMLAOTP_TAB7_HELP'); ?>
    </a>
</div>
<style>
    .mo_nav_tab_active, .mo_nav_tab_active > * {
        box-shadow: 3px 4px 3px #888888 !important;
        background-color: #226a8b !important;
        color: white !important;
    }
</style>
<script>
    function add_css_tab(element) {
        jQuery(".mo_nav_tab_active  ").removeClass("mo_nav_tab_active");
        var temp = jQuery("<a>");
        jQuery("body").append(temp);
        jQuery(element).addClass("mo_nav_tab_active");
        temp.val(jQuery(element).text()).select();
        document.execCommand("copy");
        temp.remove();
    }
</script>

<div class="tab-content" id="myTabContent">
    <div id="description" class="tab-pane <?php echo $otp_active_tab == 'account' ? 'active' : ''; ?>">
        <div class="row-fluid">
            <div class="mo_saml_table_layout_1">
                <div class="mo_saml_table_layout mo_saml_container">
                    <?php
                    $customer_details = MoOtpUtility::getCustomerDetails();
                    $login_status = $customer_details['login_status'];
                    $registration_status = $customer_details['registration_status'];

                    if ($login_status) { //Show Login Page
                        mo_otp_login_page();
                    } else { // Show Registration Page
                        if ($registration_status == 'MO_OTP_DELIVERED_SUCCESS' || $registration_status == 'MO_OTP_VALIDATION_FAILURE' || $registration_status == 'MO_OTP_DELIVERED_FAILURE') {
                            mo_otp_show_otp_verification();
                        } else if (!MoOtpUtility::is_customer_registered()) {
                            mo_otp_registration_page();
                        } else {
                            mo_otp_account_page();
                        }
                    }
                    ?>
                </div>
                <div class="mo_saml_table_layout_ad_1">
                    <?php echo mo_saml_otp_2fa(); ?>
                </div>
            </div>
        </div>
    </div>


    <div id="setting" class="tab-pane <?php echo $otp_active_tab == 'setting' ? 'active' : ''; ?>">
        <div class="row-fluid">
            <div class="mo_saml_table_layout_1">
                <div class="mo_saml_table_layout mo_saml_container">
                    <?php
                    if (!MoOtpUtility::is_customer_registered()) { ?>
                        <div style="display:block;margin-top:10px;color:red;background-color:rgba(251, 232, 0, 0.15);padding:5px;border:solid 1px rgba(255, 0, 9, 0.36);">
                            Please <a href="index.php?option=com_joomlaotp&view=accountsetup">Register or Login with
                                miniOrange</a> to enable Joomla OTP Configurations.
                        </div><br/>
                        <?php
                    }
                    ?>
                    <table style="width:100%">
                        <tbody>
                        <tr>
                            <td colspan="2">
                                <h3>
                                    OTP PROPERTIES:
                                    <span style="float:right;margin-top:-10px;">
                                            <span class="dashicons dashicons-arrow-up toggle-div" data-show="false"
                                                  data-toggle="otp_settings">
                                            </span>
                                    </span>
                                </h3>
                                <hr>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <?php
                    $customer_details = MoOtpUtility::getCustomerDetails();
                    $login_status = $customer_details['login_status'];
                    $registration_status = $customer_details['registration_status'];
                        mo_otp_settings_tab();
                    ?>
                </div>
                  <div class="mo_saml_table_layout_ad_1">
                    <?php echo mo_saml_otp_2fa(); ?>
                </div>

                <form action="<?php echo JRoute::_('index.php?option=com_joomlaotp&view=accountsetup&task=accountsetup.saveDomainBlocks'); ?>" method="post" name="adminForm" id="otp_form">
                    <input id="mo_otp_blocked_email_domains" type="hidden" name="option9" value="mo_domain_block"/>
                    <input id="mo_otp_allowed_email_domains" type="hidden" name="option9" value="mo_domain_allow"/>
                    <input id="reg_restriction" type="hidden" name="option9" value="mo_domain_allow"/>

                    <?php

                   /* $db = JFactory::getDbo();
                    $query = $db->getQuery(true);
                    $query->select('*');
                    $query->from($db->quoteName('#__miniorange_otp_customer'));
                    $query->where($db->quoteName('id') . " = 1");
                    $db->setQuery($query);
                    $result = $db->loadAssoc();*/

                    $result         = MoOtpUtility::__getDBValuesWOArray('#__miniorange_otp_customer');
                    $white_or_black = isset($result['white_or_black']) ? $result['white_or_black'] : 0;
                    $allowed_emails = isset($result['mo_otp_allowed_email_domains']) ? $result['mo_otp_allowed_email_domains'] : 0;
                    $reg_restr      = isset($result['reg_restriction']) ? $result['reg_restriction'] : 0;

                    ?>
                    <div class="mo_saml_table_layout mo_saml_container">
                        <table style="width:100%">
                            <tbody>
                            <tr>
                                <td colspan="2">
                                    <h3>
                                        DOMAIN RESTRICTIONS:
                                        <span style="float:right;margin-top:-10px;">
                                            <span class="dashicons dashicons-arrow-up toggle-div" data-show="false"
                                                  data-toggle="otp_settings">
                                            </span>
                                    </span>

                                    <b>
                                    </b>

                                    </h3>
                                    <hr>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <table style="width:50%">
                            <tbody>
                            <td>
                                <?php if (MoOtpUtility::is_customer_registered()) $disabled = true; else $disabled = false;?>

                                <b> <input id="reg_restriction" name="reg_restriction" class="reg_restriction" type="checkbox" onclick="enable_restriction()" value="1"
                                        <?php if ($reg_restr == 1) echo "checked"; ?> style="float: left;margin-right: 10px;" <?php if ($disabled) echo "enabled"; else echo "disabled"; ?>>Enable During Registration
                                </b>
                            </td>
                            <td>
                                <?php if (MoOtpUtility::is_customer_registered()) $disabled = true;
                                else $disabled = false;
                                ?>
                            </td>
                            </tbody>
                        </table>
                        <br>

                        <div id="otp_settings">
                            <table>
                                <tbody>
                                <tr>
                                    <td><strong>&nbsp;&nbsp;&nbsp;EMAIL DOMAINS: </strong></td>
                                </tr>
                                <tr>
                                    <td width="50%">
                                        <div class="mo_otp_note" style="padding:10px;">
                                            <?php if (MoOtpUtility::is_customer_registered()) $disabled = true;
                                            else $disabled = false;
                                            ?>
                                            <input id="mo_otp_allowed_email_domains"
                                                   class="mo_otp_allowed_email_domains"
                                                   name="mo_otp_allowed_email_domains" <?php if ($disabled) echo "enabled";
                                            else echo "disabled"; ?>
                                                   type="text" class="mo_saml_support_table_textbox otp-textfield"
                                                   style="width: 55%" height="100px"
                                                   value="<?php echo $allowed_emails; ?>"
                                                   placeholder="Write the email domains here that you want to allow or block."/>
                                            <p>Enter semicolon(;) separated domains. Eg. xxxx.com;xxxx.com;</p>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        <div id="otp_settings">
                            <?php if (MoOtpUtility::is_customer_registered()) $disabled = true;
                            else $disabled = false;
                            ?>
                            <table style="margin-left:-45px;width: 55%">
                                <tbody>
                                <td>
                                    <b> <input type="radio" checked id="white_or_black" name="white_or_black"
                                               value="1" class="white_or_black" ;
                                            <?php if ($white_or_black == 1) echo "checked"; ?>
                                               style="float: left;margin-left: 44px;" <?php if ($disabled) echo "enabled";
                                        else echo "disabled"; ?>>
                                        Allow Domains
                                    </b></td>
                                <td>
                                    <b> <input type="radio" id="white_or_black" name="white_or_black" value="2"
                                               class="white_or_black" ;
                                            <?php if ($white_or_black == 2) echo "checked"; ?>
                                               style="float: left;margin-left: 44px;" <?php if ($disabled) echo "enabled";
                                        else echo "disabled"; ?>>
                                        Block Domains
                                    </b></td>
                                </tbody>
                            </table>

                            <script type="text/javascript">
                                enable_restriction();

                                function enable_restriction() {
                                    var check_box_var1 = document.getElementsByClassName('reg_restriction')[0];
                                    var domain_field = document.getElementsByClassName('mo_otp_allowed_email_domains')[0];
                                    var white_or_black = document.getElementsByClassName('white_or_black')[0];

                                    if (check_box_var1.checked === true) {
                                        domain_field.disabled = false;
                                        white_or_black[0].disabled = false;
                                        white_or_black[1].disabled = false;
                                    } else {
                                        domain_field.disabled = true;
                                        white_or_black[0].disabled = true;
                                        white_or_black[1].disabled = true;
                                    }
                                }
                            </script><br>
                            <span style="margin-left: 42.5%;margin-top:-10px;">
							    <input type="submit" <?php if ($disabled) echo "enabled";else echo "disabled"; ?> name="save" class="mo_btn btn-medium mo_btn_success" value="Save Settings">
                            </span>
                        </div>
                    </div>
                </form>
                <div class="mo_saml_table_layout mo_saml_container">
                    <table style="width:100%">
                        <tbody>
                            <tr>
                                <td colspan="2">
                                    <h3>OTP PREFERENCES:
                                        <span style="float:right;margin-top:-10px;">
                                             <span class="dashicons dashicons-arrow-up toggle-div" data-show="false" data-toggle="otp_settings"></span>
                                        </span>
                                    </h3><hr>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div id="otp_settings">
                        <table>
                            <tbody>
                            <tr>
                                <td><strong>&nbsp;&nbsp;&nbsp;OTP LENGTH: </strong></td>
                                <td><strong>&nbsp;&nbsp;&nbsp;OTP VALIDITY (in mins): </strong></td>
                            </tr>
                            <tr>
                                <td width="50%">
                                    <div class="mo_otp_note" style="padding:10px;">
                                        <div class="mo_saml_table_layout_1">
                                            <?php if (MoOtpUtility::is_customer_registered()) $disabled = true;else $disabled = false;?>
                                            <a href="https://faq.miniorange.com/knowledgebase/change-length-otp/" target="_blank" <?php if ($disabled) echo "enabled";else echo "disabled"; ?>>
                                                Click here to see how you can change OTP Length
                                            </a>
                                        </div>
                                    </div>
                                </td>
                                <td width="50%">
                                    <div class="mo_otp_note" style="padding:10px;">
                                        <div class="mo_saml_table_layout_1">
                                            <?php if (MoOtpUtility::is_customer_registered()) $disabled = true;else $disabled = false;?>
                                            <a href="https://faq.miniorange.com/knowledgebase/change-time-otp-stays-valid/" target="_blank" <?php if ($disabled) echo "enabled";else echo "disabled"; ?>>
                                                Click here to see how you can change OTP Validity
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="custom_setting" class="tab-pane <?php echo $otp_active_tab == 'custom_setting' ? 'active' : ''; ?>">
        <div class="row-fluid">
            <div class="mo_saml_table_layout_1">
                <div class="mo_saml_table_layout mo_saml_container">
                    <?php
                    get_country_code_dropdown();
                    __block_country_code();
                    //redirect_url_after_login_logout();
                    ?>
                </div>
               <div class="mo_saml_table_layout_ad_1">
                    <?php echo mo_saml_otp_network(); ?>
                </div>
            </div>
        </div>
    </div>

    <div id="custom_message" class="tab-pane <?php echo $otp_active_tab == 'custom_message' ? 'active' : ''; ?>">
        <div class="row-fluid">
            <div class="mo_saml_table_layout_1">
                <div class="mo_saml_table_layout mo_saml_container_custom">
                    <?php _custom_email_messages();?>
                </div>
                <div class="mo_saml_container_custom_2">
                    <?php _custom_phone_messages(); ?>
                </div>
                <div class="mo_saml_container_custom_3">
                    <?php _custom_common_otp_messages(); ?>
                </div>
            </div>
        </div>
    </div>

    <div id="upgrade" class="tab-pane <?php echo $otp_active_tab == 'upgrade' ? 'active' : ''; ?>">
        <div class="mo_otp_verification_table_layout_1">
            <div class="mo_otp_verification_table_layout" style="padding-bottom: 35px;"><br>
                <h2>&emsp; Upgrade Plans</h2><hr>
                <section id="mo_otp-pricing-table">
                    <div class="mo_otp-container_1">
                        <div class="row">
                            <div class="pricing">
                                <div class="mo_otp-pricing-table mo_otp_class_inline_1" style="margin-left: 20px;margin-bottom: 70px">
                                    <div class="mo_otp-pricing-header" id="mo_otp-feature_list">
                                        <h2 class="pricing-title">Features / Plans</h2>
                                    </div>
                                    <div class="pricing-list">
                                        <ul>
                                            <li>Email Address Verification</li>
                                            <li>Phone Number Verification</li>
                                            <li>Custom Email Template</li>
                                            <li>Custom SMS Template</li>
                                            <li>Send Custom SMS & Email Messages</li>
                                            <li>Custom OTP Length
                                            <li>Custom OTP Validity Time</li>
                                            <li>Support various SMS gateways like msg91,twilio,etc...</li>
                                            <li>Support Multiple international countries</li>
                                            <li>Support Single international country</li>
                                            <li>1 Year plugin update</li>
                                            <li>Custom SMS/SMTP Gateway</li>
                                            <li>Custom Integration/Work</li>
                                            <li>Support</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="mo_otp-pricing-table mo_otp-class_inline">
                                    <div class="mo_otp-pricing-header">
                                        <p class="pricing-title" style="margin-top: 30px">
                                            FREE<br><span></span></span></p>
                                        <p class="pricing-rate"><sup>$</sup> 0</sup></p>
                                        <h4 class="mo_otp-text_h4">10 SMS and 10 Email Verifications through miniOrange Gateway</h4><br><br><br><br><br>
                                        <div class="filler-class"></div>
                                        <a class="mo_btn mo_otp_btn-custom mo_otp-mbtn-danger mo_otp-mbtn-sm" style="margin-top: 0px">ACTIVE PLAN</a>
                                    </div>
                                    <div class="pricing-list">
                                        <ul>
                                            <li>&#x2714;</li>
                                            <li>&#x2714;</li>
                                            <li>&#x2714;</li>
                                            <li>&#x2714;</li>
                                            <li>&#x2714;</li>
                                            <li>&#x2714;</li>
                                            <li>&#x2714;</li>
                                            <li></li>
                                            <li>&#x2714;</li>
                                            <li>&#x2714;</li>
                                            <li></li>
                                            <li></li>
                                            <li></li>
                                            <li>Basic Email Support Available</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="mo_otp-pricing-table mo_otp-class_inline">
                                    <div class="mo_otp-pricing-header">
                                        <p class="pricing-title" style="margin-top: 30px">CUSTOM GATEWAY<br><span>[One Time Payment]</span></p>
                                        <p class="pricing-rate"><sup>$</sup> 99</p>
                                        <h4 class="mo_otp-text_h4">Unlimited OTP Generation and Verification through the plugin</h4>
                                        <h4 class="mo_otp-text_h4">SMS and Email delivery will be through <br>your gateway</h4><br><br><br><br><br>
                                        <div class="filler-class-custom-gateway"></div>
                                        <a href="https://www.miniorange.com/contact" target="_blank" class="mo_btn mo_otp_btn-custom mo_otp-mbtn-danger mo_otp-mbtn-sm" style="margin-top: 5px">CONTACT US</a>
                                    </div>
                                    <div class="pricing-list">
                                        <ul>
                                            <li>&#x2714;</li>
                                            <li>&#x2714;</li>
                                            <li>&#x2714;</li>
                                            <li>&#x2714;</li>
                                            <li>&#x2714;</li>
                                            <li>&#x2714;</li>
                                            <li>&#x2714;</li>
                                            <li></li>
                                            <li>&#x2714;</li>
                                            <li>&#x2714;</li>
                                            <li>&#x2714;</li>
                                            <li>&#x2714;</li>
                                            <li></li>
                                            <li>Basic Email Support Available</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="mo_otp-pricing-table mo_otp-class_inline">
                                    <div class="mo_otp-pricing-header">
                                        <p class="pricing-title" id="mo_gateway" style="margin-top: 30px"> MINIORANGE GATEWAY <br></p>
                                        <p class="pricing-rate"><sup>$</sup> 0</p>
                                        <p class="pricing-title" id="mo_gateway">SMS CHARGES <br></p>
                                        <select required style="background:#ffffff; border: 1px solid white;color:black; margin-left: 7px;height:26px; width:85%; margin-top: -90px">
                                            <option>$2 per 100 OTP* + SMS Charges
                                            <option>$5 per 500 OTP* + SMS Charges
                                            <option>$7 per 1k OTP* + SMS Charges
                                            <option>$20 per 5k OTP* + SMS Charges
                                            <option>$30 per 10k OTP* + SMS Charges
                                            <option>$45 per 50k OTP* + SMS Charges
                                        </select>
                                        <p class="pricing-title" id="mo_gateway" style="margin-top: -30px"">EMAIL CHARGES <br></p>
                                        <select required style="background:#ffffff; border: 1px solid white;color:black; margin-left: 7px;height:26px; width:65%; margin-top: -80px">
                                            <option>$2 per 100 Emails
                                            <option>$5 per 500 Emails
                                            <option>$7 per 1k Emails
                                            <option>$20 per 5k Emails
                                            <option>$30 per 10k Emails
                                            <option>$45 per 50k Emails
                                        </select>
                                        <h4 class="mo_otp-text_h4">SMS Delivery charges vary from country to country</h4>
                                        <h4 class="mo_otp-text_h4">The transactions comes with Lifetime validity</h4>
                                        <div class="filler-class-mo-gateway"></div>
                                        <a href="https://www.miniorange.com/contact" target="_blank" class="mo_btn mo_otp_btn-custom mo_otp-mbtn-danger mo_otp-mbtn-sm" style="margin-top: -2px">CONTACT US</a>
                                    </div>

                                    <div class="pricing-list">
                                        <ul>
                                            <li>&#x2714;</li>
                                            <li>&#x2714;</li>
                                            <li>&#x2714;</li>
                                            <li>&#x2714;</li>
                                            <li>&#x2714;</li>
                                            <li>&#x2714;</li>
                                            <li>&#x2714;</li>
                                            <li>&#x2714;</li>
                                            <li>&#x2714;</li>
                                            <li>&#x2714;</li>
                                            <li>&#x2714;</li>
                                            <li></li>
                                            <li>&#x2714;</li>
                                            <li>Premium Email Support Available</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <h3 style="text-align: center;font-family:cursive;font-weight:600;font-style:normal;font-size: 22px;" class="vc_custom_heading" >30+ Gateway Supported all Over World</h3>

                <div class="mmarquee">
                    <div class="mo_os">
	                    <div class="mo_otp_image"><img src="https://plugins.miniorange.com/wp-content/uploads/2019/07/amazon_sns.png" alt="amazon-sns" /></div>
                         <div class="mo_otp_image"><img src="https://plugins.miniorange.com/wp-content/uploads/2019/07/click-send.jpg" alt="click-send" /></div>
                         <div class="mo_otp_image"><img src="https://plugins.miniorange.com/wp-content/uploads/2019/07/ebulk-sms-cscstar-logo.png"  alt="ebulk-sms-cscstar" /></div>
                         <div class="mo_otp_image"><img src="https://plugins.miniorange.com/wp-content/uploads/2019/07/expert-texting-logo.png"  alt="expert-texting" /></div>
                         <div class="mo_otp_image"><img src="https://plugins.miniorange.com/wp-content/uploads/2019/07/gupshup.png"  alt="gupshup" /></div>
                         <div class="mo_otp_image"><img src="https://plugins.miniorange.com/wp-content/uploads/2019/07/msg91.png"  alt="msg91" /></div>
                         <div class="mo_otp_image"><img src="https://plugins.miniorange.com/wp-content/uploads/2019/07/ringcentral-logo.png"  alt="ringcentral" /></div>
                         <div class="mo_otp_image"><img src="https://plugins.miniorange.com/wp-content/uploads/2019/07/Routee-1.png"  alt="routee" style="height:100px!important;"/></div>
                         <div class="mo_otp_image"><img src="https://plugins.miniorange.com/wp-content/uploads/2019/07/sms-horizon-1.png"  alt="sms-horizon" /></div>
                         <div class="mo_otp_image"><img src="https://plugins.miniorange.com/wp-content/uploads/2019/07/tencent-cloud.jpg"  alt="tencent-cloud" /></div>
                    </div>
                </div><hr>

               <?php echo showAddonsContent();?>


                <p style="margin-left: 25px;"><b>SMTP gateway</b> is a service provider for sending Emails on your behalf to your users.
                <p>
                <p style='margin-left: 25px;font-weight:normal;'>* Transaction prices may vary depending on country.
                If you want to use more than 50k transactions, mail us at <b><a href='mailto:joomlasupport@xecurify.com'><i>joomlasupport@xecurify.com</i></a></b> or submit a support request using the support form. </p>
                <h3 style="margin-left: 25px;"><b>10 Days Return Policy -</b></h3>
                <p style='margin-left: 25px;margin-right: 25px;font-weight:normal;'>At miniOrange, we want to ensure
                    At miniOrange, we want to ensure you are 100% happy with your purchase. If the module you purchased is not working as advertised and you've attempted to resolve any issues with our support team, which couldn't get resolved, we will refund the whole amount given that you have a raised a refund request within the first 10 days of the purchase.
                    Please email us at <b><a href='mailto:joomlasupport@xecurify.com'><i>joomlasupport@xecurify.com</i></a></b>
                    for any queries regarding the return policy.</p>
                <p style='margin-left: 25px;font-weight:normal;'>If you have any doubts regarding the licensing
                    plans, you can mail us at <b> <a href='mailto:joomlasupport@xecurify.com'><i>joomlasupport@xecurify.com</i></a></b>
                    or submit a query using the support form.
                </p>
            </div>
        </div>
    </div>

    <div id="licensing-plans" class="tab-pane <?php echo $otp_active_tab == 'license' ? 'active' : ''; ?>">
        <div class="row-fluid">
            <?php

            $result = MoOtpUtility::__getDBValuesWColumn('email', '#__miniorange_otp_customer');
            $email = isset($result['email']) ? $result['email'] : '';
            $hostName = 'https://www.miniorange.com';
            $loginUrl = $hostName . '/contact';

            ?>
            <form id="otp_default_form" method="post" action="<?php echo JRoute::_('index.php?option=com_joomlaotp&view=accountsetup'); ?>">
            </form>
            <form style="display:none;" id="mootp_loginform" action="<?php echo $loginUrl; ?>" target="_blank" method="post">
                <input name="username" value="<?php echo $email; ?>" type="email" style="display:none;">
                <input name="requestOrigin" id="requestOrigin" type="hidden">
            </form>
            <script>
                function backToDefaultTab() {
                    jQuery('#otp_default_form').submit();
                }

                jQuery('#otp_basic_upgrade').click(function () {
                    jQuery('#requestOrigin').val('joomla_otp_basic_plan');
                    jQuery('#mootp_loginform').submit();
                });
                jQuery('#otp_premium_upgrade').click(function () {
                    jQuery('#requestOrigin').val('joomla_otp_premium_plan');
                    jQuery('#mootp_loginform').submit();
                });
            </script>
        </div>
    </div>

    <div id="configuration" class="tab-pane <?php echo $otp_active_tab == 'configuration' ? 'active' : ''; ?>">
        <div class="row-fluid">
            <div class="mo_saml_table_layout_1">
                <div class="mo_saml_table_layout mo_saml_container">
                    <table style="width:100%">
                        <tbody>
                        <tr>
                            <td colspan="2">
                                <h3>SMS &amp; EMAIL CONFIGURATION
                                    <span style="float:right;margin-top:-10px;">
                                         <span class="dashicons dashicons-arrow-up toggle-div" data-show="false" data-toggle="otp_settings"></span>
                                    </span>
                                </h3><hr>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <div class="mo_otp_verification_highlight_background_note">
                        <b>Look at the sections below to customize the Email and SMS that you receive:</b>
                    </div><br>
                    <b>SMS</b><br>
                    1. <a target="_blank" href="https://login.xecurify.com/moas/login?redirectUrl=https://login.xecurify.com/moas/admin/customer/showsmstemplate">Custom SMS Template</a> : Change the text of the SMS that you receive.<br>
                    2. <a class='collapsed' data-toggle='collapse' href='#faqa1' aria-expanded='false'>Custom SMS Gateway</a> You can configure settings to use your own SMS gateway.
                    <div id='faqa1' class='collapse'>
                        <ul>
                            <li> 1. An SMS Gateway is a system that allows user to send or receive messages. SMS
                                Gateway Providers is a company that provides such services at a cost.
                            </li>
                            <li>2. In order to be able to use your own SMS Gateway with the plugin you will need to
                                be on your Gateway plan
                            </li>
                        </ul>
                        For any further queries, please contact us.
                    </div>
                    <b>EMAIL</b><br>
                    1. <a target="_blank"
                          href="https://login.xecurify.com/moas/login?redirectUrl=https://login.xecurify.com/moas/admin/customer/emailtemplateconfiguration">Custom
                        Email Template</a> : Change the text of the email that you receive.<br>
                    2. <a class='collapsed' data-toggle='collapse' href='#faqa2' aria-expanded='false'>Custom Email
                        Gateway</a> You can configure settings to use your own Email gateway.
                    <div id='faqa2' class='collapse'>
                        <ul>
                            <li> 1. An Email Gateway is a system that allows user to send or receive messages. Email
                                Gateway Providers is a company that provides such services at a cost.
                            </li>
                            <li>2. In order to be able to use your own SMS Gateway with the plugin you will need to
                                be on your Gateway plan
                            </li>
                        </ul>
                        For any further queries, please contact us.
                    </div>

                    <div class="mo_otp_verification_highlight_background_note">
                        <b><a class='collapsed' data-toggle='collapse' href='#faqb1' aria-expanded='false'> How can
                                I change the SenderID/Number of the SMS I receive?</a></b>
                        <p>SenderID/Number is gateway specific. You will need to use your own SMS gateway for
                            this.</p>
                    </div>
                    <div id="faqb1" class="collapse">
                        <ol>
                            <li>1. Customization of the Sender ID with miniOrange is possible if customers have
                                opted for the Your Gateway Plan.
                            </li>
                            <li>2. Those looking to change the Sender ID going out in the SMS text need to register
                                and authenticate their ID with the SMS gateway provider before they can use it.
                            </li>
                            <li>3. Customization of Sender ID is not supported with the miniOrange Gateway Plan as
                                it can result in abuse, by using it as a tool for spamming and spoofing.
                            </li>
                            <li>4. For this reason miniOrange has provided its customers with an option to use their
                                own SMS gateway with the plugin.
                            </li>
                            <li>5. Customers can <a _target="blank" href="https://www.miniorange.com/contact">Click
                                    here</a> to upgrade to the Your Gateway Plan.
                            </li>
                        </ol>
                        Drop a query at <a href="mailto:joomlasupport@xecurify.com"> joomlasupport@xecurify.com </a>for
                        any further queries regarding this.
                    </div>
                    <div class="mo_otp_verification_highlight_background_note">
                        <b><a class='collapsed' data-toggle='collapse' href='#faqb2' aria-expanded='false'>How can I
                                change the Sender Email of the Email I receive?</a></b>
                        <p>Sender Email is gateway specific. You will need to use your own Email gateway for
                            this.</p>
                    </div>
                    <div id="faqb2" class="collapse">
                        <ol type="1">
                            <li>1. Customization of the Email Address with miniOrange is possible if customers have
                                their own SMTP Gateway.
                            </li>
                            <li>2. Changing the Email Address is not supported with the miniOrange Gateway Plan as
                                it can be used as a tool for spamming and spoofing.
                            </li>
                            <li>3. For this reason miniOrange has provided its customers with an option to use their
                                own SMS gateway with the plugin.
                            </li>
                            <li>4. Customers can <a _target="blank" href="https://www.miniorange.com/contact">Click
                                    here</a> to upgrade to the Your Gateway Plan.
                            </li>
                        </ol>
                        Drop a query at <a href="mailto:joomlasupport@xecurify.com"> joomlasupport@xecurify.com </a>for
                        any further queries regarding this.
                    </div>
                    <br>
                </div>
               <div class="mo_saml_table_layout_ad_1">
                    <?php echo mo_saml_otp_network(); ?>
                </div>
            </div>
        </div>
    </div>

    <div id="help" class="tab-pane <?php echo $otp_active_tab == 'faqs' ? 'active' : ''; ?>">
        <div class="row-fluid">
            <div class="mo_saml_table_layout_1">
                <div class="mo_saml_table_layout mo_saml_container">
                    <?php echo $this->showHelpAndTroubleshooting(); ?>
                </div>
                 <div class="mo_saml_table_layout_ad_1">
                    <?php echo mo_saml_otp_network(); ?>
                </div>
            </div>
        </div>
    </div>

    <?php echo mo_otp_support(); ?>
    <!--</div>-->

    <!--
        *End Of Tabs for accountsetup view.
        *Below are the UI for various sections of Account Creation.
    -->




    <?php

    function _custom_email_messages()
    {
        $messages = unserialize(MO_MESSAGES);

        $otp_sent      = isset($messages['OTP_SENT_EMAIL']) ? $messages['OTP_SENT_EMAIL'] : '';
        $otp_error     = isset($messages['ERROR_OTP_EMAIL']) ? $messages['ERROR_OTP_EMAIL'] : '';
        $email_blocked = isset($messages['ERROR_EMAIL_BLOCKED']) ? $messages['ERROR_EMAIL_BLOCKED'] : '';
        $email_format  = isset($messages['EMAIL_FORMAT']) ? $messages['EMAIL_FORMAT'] : '';

        $result                       = MoOtpUtility::_get_custom_message();
        $custom_success_email_message = isset($result['mo_custom_email_success_message']) && !empty($result['mo_custom_email_success_message']) ? $result['mo_custom_email_success_message'] : $otp_sent;
        $error_otp_message            = isset($result['mo_custom_email_error_message']) && !empty($result['mo_custom_email_error_message']) ? $result['mo_custom_email_error_message'] : $otp_error;
        $invalid_format               = isset($result['mo_custom_email_invalid_format_message']) && !empty($result['mo_custom_email_invalid_format_message']) ? $result['mo_custom_email_invalid_format_message'] : $email_format;
        $blocked_email_message        = isset($result['mo_custom_email_blocked_message']) && !empty($result['mo_custom_email_blocked_message']) ? $result['mo_custom_email_blocked_message'] : $email_blocked;

        if (MoOtpUtility::is_customer_registered()) $disabled = true;
        else $disabled = false;
        ?>

        <form action="<?php echo JRoute::_('index.php?option=com_joomlaotp&view=accountsetup&task=accountsetup.saveCustomMessage'); ?>" method="post" name="custom_message" style="width:85%;float: left;margin-right: 10px;">
            <input type="submit" class="mo_btn btn-medium mo_btn_success" style="float: right;" name="custom_email_messages" value="Save Settings" <?php if ($disabled) echo "enabled"; else echo "disabled"; ?> />
            <h3>EMAIL MESSAGES:</h3><hr>
            <h4>SUCCESS OTP MESSAGE:</h4>
            <div style="color: red;">( NOTE: ##email## in the message body will be replaced by the user's email address)</div>
            <textarea name="mo_custom_email_success_message_send" class="form-control mo_textarea_css" style="border-radius:4px;resize: vertical;width:100%;" cols="52" rows="5"><?php echo $custom_success_email_message; ?></textarea><br><br>
            <h4>ERROR OTP MESSAGE:</h4>
            <textarea name="mo_custom_email_error_message" class="form-control mo_textarea_css" style="border-radius:4px;resize: vertical;width:100%;" cols="52" rows="5"><?php echo $error_otp_message; ?></textarea><br><br>
            <h4>INVALID FORMAT MESSAGE:</h4>
            <div style="color: red;">( NOTE: ##email## in the message body will be replaced by the user's email address)</div>
            <textarea name="mo_custom_email_invalid_format_message" class="form-control mo_textarea_css"style="border-radius:4px;resize: vertical;width:100%;" cols="52" rows="5"><?php echo $invalid_format; ?></textarea><br><br>
            <h4>BLOCKED EMAIL MESSAGE:</h4>
            <textarea name="mo_custom_email_blocked_message" class="form-control mo_textarea_css"style="border-radius:4px;resize: vertical;width:100%;" cols="52"rows="5"><?php echo $blocked_email_message; ?></textarea>
        </form><br><br>
        <?php
    }


    function _custom_phone_messages()
    {
        $messages = unserialize(MO_MESSAGES);

        $ph_otp_sent   = isset($messages['OTP_SENT_PHONE']) ? $messages['OTP_SENT_PHONE'] : '';
        $ph_otp_error  = isset($messages['ERROR_OTP_PHONE']) ? $messages['ERROR_OTP_PHONE'] : '';
        $phone_blocked = isset($messages['ERROR_PHONE_BLOCKED']) ? $messages['ERROR_PHONE_BLOCKED'] : '';
        $phone_format  = isset($messages['ERROR_PHONE_FORMAT']) ? $messages['ERROR_PHONE_FORMAT'] : '';

        $result                  = MoOtpUtility::_get_custom_message();
        $success_phone_message   = isset($result['mo_custom_phone_success_message']) && !empty($result['mo_custom_phone_success_message']) ? $result['mo_custom_phone_success_message'] : $ph_otp_sent;
        $error_phone_otp_message = isset($result['mo_custom_phone_error_message']) && !empty($result['mo_custom_phone_error_message']) ? $result['mo_custom_phone_error_message'] : $ph_otp_error;
        $invalid_phone_format    = isset($result['mo_custom_phone_invalid_format_message']) && !empty($result['mo_custom_phone_invalid_format_message']) ? $result['mo_custom_phone_invalid_format_message'] : $phone_format;
        $blocked_phone_message   = isset($result['mo_custom_phone_blocked_message']) && !empty($result['mo_custom_phone_blocked_message']) ? $result['mo_custom_phone_blocked_message'] : $phone_blocked;

        if (MoOtpUtility::is_customer_registered()) $disabled = true;
        else $disabled = false;
        ?>

        <form action="<?php echo JRoute::_('index.php?option=com_joomlaotp&view=accountsetup&task=accountsetup.saveCustomPhoneMessage'); ?>"
              method="post" name="custom_phone_message" style="width:85%;float: left;margin-right: 10px;">
            <input type="submit" class="mo_btn btn-medium mo_btn_success" style="float: right;"
                   name="custom_phone_messages"
                   value="Save Settings" <?php if ($disabled) echo "enabled";
            else echo "disabled"; ?> />
            <h3>SMS/MOBILE MESSAGES:</h3>
            <hr>
            <h4>SUCCESS OTP MESSAGE:</h4>
            <div style="color: red;">( NOTE: ##phone## in the message body will be replaced by the user's mobile number)
            </div>
            <textarea name="mo_custom_phone_success_message" class="form-control mo_textarea_css" style="border-radius:4px;resize: vertical;width:100%;" cols="52" rows="5"><?php echo $success_phone_message; ?></textarea><br><br>
            <h4>ERROR OTP MESSAGE:</h4>
            <textarea name="mo_custom_phone_error_message" class="form-control mo_textarea_css" style="border-radius:4px;resize: vertical;width:100%;" cols="52" rows="5"><?php echo $error_phone_otp_message; ?></textarea><br><br>

            <h4>INVALID FORMAT MESSAGE:</h4>
            <div style="color: red;">( NOTE: ##phone## in the message body will be replaced by the user's phone number)</div>
            <textarea name="mo_custom_phone_invalid_format_message" class="form-control mo_textarea_css" style="border-radius:4px;resize: vertical;width:100%;" cols="52" rows="5"><?php echo $invalid_phone_format; ?></textarea><br><br>

            <h4>BLOCKED PHONE MESSAGE:</h4>
            <textarea name="mo_custom_phone_blocked_message" class="form-control mo_textarea_css" style="border-radius:4px;resize: vertical;width:100%;" cols="52" rows="5"><?php echo $blocked_phone_message; ?></textarea>
        </form><br><br>
        <?php
    }

    function _custom_common_otp_messages()
    {
        $messages = unserialize(MO_MESSAGES);
        $com_messages = isset($messages['COMMON_MESSAGES']) ? $messages['COMMON_MESSAGES'] : '';
        $result = MoOtpUtility::_get_custom_message();
        $invalid_otp_message = isset($result['mo_custom_invalid_otp_message']) && !empty($result['mo_custom_invalid_otp_message']) ? $result['mo_custom_invalid_otp_message'] : $com_messages;
        if (MoOtpUtility::is_customer_registered()) $disabled = true;
        else $disabled = false;
        ?>

        <form action="<?php echo JRoute::_('index.php?option=com_joomlaotp&view=accountsetup&task=accountsetup.saveComOTPMessages'); ?>"
              method="post" name="block_country_codes" style="width:85%;float: left;margin-right: 10px;">
            <input type="submit" class="mo_btn btn-medium mo_btn_success" style="float: right;"
                   name="custom_otp_messages"
                   value="Save Settings" <?php if ($disabled) echo "enabled";
            else echo "disabled"; ?> />
            <h3>COMMON MESSAGES:</h3>
            <hr>
            <h4>INVALID OTP MESSAGE:</h4>
            <textarea name="mo_custom_invalid_otp_message" class="form-control mo_textarea_css"
                      style="border-radius:4px;resize: vertical;width:100%;" cols="52" rows="5"
            ><?php echo $invalid_otp_message; ?></textarea>
        </form>
        <?php
    }

    /*function redirect_url_after_login_logout(){
        $result              = MoOtpUtility::__getDBValuesWOArray('#__miniorange_otp_customer');
        $login_redirect_url  = isset($result['redirect_after_login']) ? $result['redirect_after_login'] : '';
        $logout_redirect_url = isset($result['redirect_after_logout']) ? $result['redirect_after_logout'] : '';

        if (MoOtpUtility::is_customer_registered()) $disabled = true;
        else $disabled = false;
        */?><!--
        <form action="<?php /*echo JRoute::_('index.php?option=com_joomlaotp&view=accountsetup&task=accountsetup.login_logout_redirect'); */?>" method="post" name="login_logout_redirect" style="width:85%;float: left;margin-right: 10px;">
            <input type="submit" class="mo_btn btn-medium mo_btn_success" style="float: right;" value="Save Settings" <?php /*if ($disabled) echo "enabled"; else echo "disabled"; */?> />
            <h3>Redirect URL:</h3><hr>
            <div>
                <span style="color: red;">Note:</span> Provide the default URL if you want your users to redirect after logging in or logout from the plugin.
                <br><i>(For example - <b>https://www.xyz.com/yyyy</b> or <b>http://localhost/xxxx</b>)</i>
            </div><br>
            <table class="mo_login_logout_settings_table">
                <tr>
                    <td><strong>Redirect URL after Login:</strong></td>
                    <td>
                        <input class="mo_table_textbox otp-textfield" type="text" style="width: 293%;" id="mo_oauth_login_redirect_url"
                           name="mo_oauth_login_redirect_url" placeholder="Provide URL if you want your users to redirect after login." value='<?php /*echo $login_redirect_url; */?>'>
                    </td>
                </tr>
                <tr>
                    <td><strong>Redirect URL after Logout:</strong></td>
                    <td><input class="mo_table_textbox otp-textfield" type="text" style="width: 293%;" id="mo_oauth_logout_redirect_url"
                           name="mo_oauth_logout_redirect_url" placeholder="Provide URL if you want your users to redirect after logout." value='<?php /*echo $logout_redirect_url; */?>'>
                </tr>
            </table>
      </form> --><?php
/*    }*/

    function __block_country_code()
    {
        $result = MoOtpUtility::_get_custom_message();
        $country_code = isset($result['mo_block_country_code']) ? $result['mo_block_country_code'] : '';
        $country_code = unserialize($country_code);

        if (!empty($country_code)) {
            $country_code = implode(';', $country_code);
        } else {
            $country_code = '';
        }

        if (MoOtpUtility::is_customer_registered()) $disabled = true;
        else $disabled = false;
        ?>

        <form action="<?php echo JRoute::_('index.php?option=com_joomlaotp&view=accountsetup&task=accountsetup.block_country_codes'); ?>"
              method="post" name="block_country_code" style="width:85%;float: left;margin-right: 10px;">
            <input type="submit" class="mo_btn btn-medium mo_btn_success" style="float: right;"
                   value="Save Settings" <?php if ($disabled) echo "enabled";
            else echo "disabled"; ?> />
            <h3>BLOCK COUNTRY CODE:</h3>
            <hr>
            <div><span style="color: red;">Note:</span> Once you block the country code then the user belongs to that
                country won't be allowd to register.
            </div>
            <br>
            <textarea name="mo_block_country_code" class="form-control mo_textarea_css"
                      style="border-radius:4px;resize: vertical;width:100%;" cols="52" rows="5"
                      placeholder="Enter the country codes with semicolan(;) seperated."
            ><?php echo $country_code; ?></textarea>
        </form>
        <?php
    }

            function get_country_code_dropdown()
            {
                $result            = MoOtpUtility::__getDBValuesWOArray('#__miniorange_otp_customer');
                $default_cont_code = isset($result['mo_default_country_code']) ? $result['mo_default_country_code'] : '';
                $default_cont_name = isset($result['mo_default_country']) ? $result['mo_default_country'] : '';

                if (MoOtpUtility::is_customer_registered()) $disabled = true;
                else $disabled = false;
                if (!MoOtpUtility::is_customer_registered()) { ?>
                     <div style="display:block;margin-top:10px;color:red;background-color:rgba(251, 232, 0, 0.15);padding:5px;border:solid 1px rgba(255, 0, 9, 0.36);">
                         Please <a href="index.php?option=com_joomlaotp&view=accountsetup">Register or Login with miniOrange</a> to enable Joomla OTP Configurations.
                     </div><br/>
                    <?php
                }
                ?>

                <form action="<?php echo JRoute::_('index.php?option=com_joomlaotp&view=accountsetup&task=accountsetup.saveCustomSettings'); ?>"
                      method="post" name="custom_set" id="otp_form" style="width:85%;float: left;margin-right: 10px;">
                    <input type="submit" class="mo_btn btn-medium mo_btn_success" style="float: right;"
                       value="Save Settings" <?php if ($disabled) echo "enabled";
                    else echo "disabled"; ?> />
                    <h3>COUNTRY CODE:</h3><hr>
                    <div>Select Default Country Code:</div><br>
                    <p>If you select the defualt country code then user wont need to enter a Country code during Register.</p>
                    <select name="default_country_code" class="mo_textarea_css"
                        id="mo_country_code" <?php if ($disabled) echo "enabled";
                    else echo "disabled"; ?>><hr>
                    <option value="" disabled selected="selected">
                        --------- Select your Country -------
                    </option>
                    <?php
                    foreach (getCountryCodeList() as $key => $country) {
                        echo '<option id="mo_count" data-countrycode="' . $country['countryCode'] . '" value="' . $country['countryCode'], ',' . $country['name'] . '"';
                        echo $default_cont_code == $country['countryCode'] ? 'selected' : '';
                        echo '>' . $country['name'] . ', ' . $country['countryCode'] . '</option>';
                    }
                    echo '</select><hr><br>
                </form>';
            }

            function showAddonsContent(){

                define("MO_ADDONS_CONTENT",serialize( array(

                            "JOOMLA_SMS_NOTIFICATION" =>      [
                                'addonName'  => 'Joomla SMS Notification to Admin & User on Registration',
                                'addonDescription'  => 'Allows your site to send out custom SMS notifications to Customers and Administrators when a new user registers on your Joomla site. Click on the button above for further details.',
                            ],
                            "JOOMLA_PASSWORD_RESET" =>      [
                                'addonName'  => 'Joomla Password Reset Over OTP',
                                'addonDescription'  => 'Allows your users to reset their password using OTP instead of email links. Click on the button above for further details.',
                            ],
                            "REGISTER_USING_ONLY_PHONE" =>      [
                                'addonName'  => 'Register Using Only Phone Number',
                                'addonDescription'  => 'Allows your users to register on your Joomla site using only their Phone Number instead of email address. Click on the button above for further details.',
                            ],
                            "RESEND_OTP_CONTROL" =>      [
                                'addonName'  => 'Resend OTP Control',
                                'addonDescription'  => 'Allows you to block OTP from being sent out before the set timer is up. Click on the button above for further details.',
                            ],
                            "REGISTER_USING_OTP" =>      [
                                'addonName'  => 'Register Using OTP instead of Password ',
                                'addonDescription'  => 'Allows user to register using OTP instead of using Password. Click on the button above for further details.',
                            ],
                            "OTP_OVER_VOICE" =>      [
                                'addonName'  => 'OTP Over Voice',
                                'addonDescription'  => 'User will get the OTP over Voice or Phone call. Click on the button above for further details.',
                            ],
                            "OTP_OVER_WHATSAPP" =>      [
                                'addonName'  => 'OTP Over Whatsapp',
                                'addonDescription'  => 'User will get the OTP over WhatsApp. Click on the button above for further details.',
                            ],
                            )));

                    $displayMessage = "";
                    $messages = unserialize(MO_ADDONS_CONTENT);
                    echo '<div class="mo_otp_wrapper">';
                    $queryBody = "Hi! I am interested in the {{addonName}} addon, could you please tell me more about this addon?";
                    foreach ($messages as $messageKey)
                    {
                        echo'<div id="'.$messageKey["addonName"].'">
                                 <center><h3 style="color:white;">'.$messageKey["addonName"].'<br /><br /></h3></center>                               
                                    <footer>
                                        <center>
                                            <input type="button" class="mo_btn btn-medium mo_btn_inter" onclick="support_form_open();" value="Interested">
                                        </center>
                                    </footer>
                                    <span class="cd-pricing-body">
                                        <ul class="cd-pricing-features">
                                            <li style="color:white;text-align: center;">'.$messageKey["addonDescription"].'</li>
                                        </ul>
                                    </span>
                                 </div>';
                    }
                    echo '</div><br>';
                    return $displayMessage;
            }

            function getCountryCodeList()
        {
            $countries = array(
                        array('name' => 'All Countries',
                                'alphacode' => '',
                                'countryCode' => ''
                            ),
                       array(
                                'name' => 'Afghanistan (‫افغانستان‬‎)',
                                'alphacode' => 'af',
                                'countryCode' => '+93'
                            ),
                        array(
                            'name' => 'Albania (Shqipëri)',
                            'alphacode' => 'al',
                            'countryCode' => '+355'
                        ),
                        array(
                            'name' => 'Algeria (‫الجزائر‬‎)',
                            'alphacode' => 'dz',
                            'countryCode' => '+213'
                        ),
                        array(
                            'name' => 'American Samoa',
                            'alphacode' => 'as',
                            'countryCode' => '+1684'
                        ),
                        array(
                            'name' => 'Andorra',
                            'alphacode' => 'ad',
                            'countryCode' => '+376'
                        ),
                        array(
                            'name' => 'Angola',
                            'alphacode' => 'ao',
                            'countryCode' => '+244'
                        ),
                        array(
                            'name' => 'Anguilla',
                            'alphacode' => 'ai',
                            'countryCode' => '+1264'
                        ),
                        array(
                            'name' => 'Antigua and Barbuda',
                            'alphacode' => 'ag',
                            'countryCode' => '+1268'
                        ),
                        array(
                            'name' => 'Argentina',
                            'alphacode' => 'ar',
                            'countryCode' => '+54'
                        ),
                        array(
                            'name' => 'Armenia (Հայաստան)',
                            'alphacode' => 'am',
                            'countryCode' => '+374'
                        ),
                        array(
                            'name' => 'Aruba',
                            'alphacode' => 'aw',
                            'countryCode' => '+297'
                        ),
                        array(
                            'name' => 'Australia',
                            'alphacode' => 'au',
                            'countryCode' => '+61'
                        ),
                        array(
                            'name' => 'Austria (Österreich)',
                            'alphacode' => 'at',
                            'countryCode' => '+43'
                        ),
                        array(
                            'name' => 'Azerbaijan (Azərbaycan)',
                            'alphacode' => 'az',
                            'countryCode' => '+994'
                        ),
                        array(
                            'name' => 'Bahamas',
                            'alphacode' => 'bs',
                            'countryCode' => '+1242'
                        ),
                        array(
                            'name' => 'Bahrain (‫البحرين‬‎)',
                            'alphacode' => 'bh',
                            'countryCode' => '+973'
                        ),
                        array(
                            'name' => 'Bangladesh (বাংলাদেশ)',
                            'alphacode' => 'bd',
                            'countryCode' => '+880'
                        ),
                        array(
                            'name' => 'Barbados',
                            'alphacode' => 'bb',
                            'countryCode' => '+1246'
                        ),
                        array(
                            'name' => 'Belarus (Беларусь)',
                            'alphacode' => 'by',
                            'countryCode' => '+375'
                        ),
                        array(
                            'name' => 'Belgium (België)',
                            'alphacode' => 'be',
                            'countryCode' => '+32'
                        ),
                        array(
                            'name' => 'Belize',
                            'alphacode' => 'bz',
                            'countryCode' => '+501'
                        ),
                        array(
                            'name' => 'Benin (Bénin)',
                            'alphacode' => 'bj',
                            'countryCode' => '+229'
                        ),
                        array(
                            'name' => 'Bermuda',
                            'alphacode' => 'bm',
                            'countryCode' => '+1441'
                        ),
                        array(
                            'name' => 'Bhutan (འབྲུག)',
                            'alphacode' => 'bt',
                            'countryCode' => '+975'
                        ),
                        array(
                            'name' => 'Bolivia',
                            'alphacode' => 'bo',
                            'countryCode' => '+591'
                        ),
                        array(
                            'name' => 'Bosnia and Herzegovina (Босна и Херцеговина)',
                            'alphacode' => 'ba',
                            'countryCode' => '+387'
                        ),
                        array(
                            'name' => 'Botswana',
                            'alphacode' => 'bw',
                            'countryCode' => '+267'
                        ),
                        array(
                            'name' => 'Brazil (Brasil)',
                            'alphacode' => 'br',
                            'countryCode' => '+55'
                        ),
                        array(
                            'name' => 'British Indian Ocean Territory',
                            'alphacode' => 'io',
                            'countryCode' => '+246'
                        ),
                        array(
                            'name' => 'British Virgin Islands',
                            'alphacode' => 'vg',
                            'countryCode' => '+1284'
                        ),
                        array(
                            'name' => 'Brunei',
                            'alphacode' => 'bn',
                            'countryCode' => '+673'
                        ),
                        array(
                            'name' => 'Bulgaria (България)',
                            'alphacode' => 'bg',
                            'countryCode' => '+359'
                        ),
                        array(
                            'name' => 'Burkina Faso',
                            'alphacode' => 'bf',
                            'countryCode' => '+226'
                        ),
                        array(
                            'name' => 'Burundi (Uburundi)',
                            'alphacode' => 'bi',
                            'countryCode' => '+257'
                        ),
                        array(
                            'name' => 'Cambodia (កម្ពុជា)',
                            'alphacode' => 'kh',
                            'countryCode' => '+855'
                        ),
                        array(
                            'name' => 'Cameroon (Cameroun)',
                            'alphacode' => 'cm',
                            'countryCode' => '+237'
                        ),
                        array(
                            'name' => 'Canada',
                            'alphacode' => 'ca',
                            'countryCode' => '+1'
                        ),
                        array(
                            'name' => 'Cape Verde (Kabu Verdi)',
                            'alphacode' => 'cv',
                            'countryCode' => '+238'
                        ),
                        array(
                            'name' => 'Caribbean Netherlands',
                            'alphacode' => 'bq',
                            'countryCode' => '+599'
                        ),
                        array(
                            'name' => 'Cayman Islands',
                            'alphacode' => 'ky',
                            'countryCode' => '+1345'
                        ),
                        array(
                            'name' => 'Central African Republic (République centrafricaine)',
                            'alphacode' => 'cf',
                            'countryCode' => '+236'
                        ),
                        array(
                            'name' => 'Chad (Tchad)',
                            'alphacode' => 'td',
                            'countryCode' => '+235'
                        ),
                        array(
                            'name' => 'Chile',
                            'alphacode' => 'cl',
                            'countryCode' => '+56'
                        ),
                        array(
                            'name' => 'China (中国)',
                            'alphacode' => 'cn',
                            'countryCode' => '+86'
                        ),
                        array(
                            'name' => 'Christmas Island',
                            'alphacode' => 'cx',
                            'countryCode' => '+61'
                        ),
                        array(
                            'name' => 'Cocos (Keeling) Islands',
                            'alphacode' => 'cc',
                            'countryCode' => '+61'
                        ),
                        array(
                            'name' => 'Colombia',
                            'alphacode' => 'co',
                            'countryCode' => '+57'
                        ),
                        array(
                            'name' => 'Comoros (‫جزر القمر‬‎)',
                            'alphacode' => 'km',
                            'countryCode' => '+269'
                        ),
                        array(
                            'name' => 'Congo (DRC) (Jamhuri ya Kidemokrasia ya Kongo)',
                            'alphacode' => 'cd',
                            'countryCode' => '+243'
                        ),
                        array(
                            'name' => 'Congo (Republic) (Congo-Brazzaville)',
                            'alphacode' => 'cg',
                            'countryCode' => '+242'
                        ),
                        array(
                            'name' => 'Cook Islands',
                            'alphacode' => 'ck',
                            'countryCode' => '+682'
                        ),
                        array(
                            'name' => 'Costa Rica',
                            'alphacode' => 'cr',
                            'countryCode' => '+506'
                        ),
                        array(
                            'name' => 'Côte d’Ivoire',
                            'alphacode' => 'ci',
                            'countryCode' => '+225'
                        ),
                        array(
                            'name' => 'Croatia (Hrvatska)',
                            'alphacode' => 'hr',
                            'countryCode' => '+385'
                        ),
                        array(
                            'name' => 'Cuba',
                            'alphacode' => 'cu',
                            'countryCode' => '+53'
                        ),
                        array(
                            'name' => 'Curaçao',
                            'alphacode' => 'cw',
                            'countryCode' => '+599'
                        ),
                        array(
                            'name' => 'Cyprus (Κύπρος)',
                            'alphacode' => 'cy',
                            'countryCode' => '+357'
                        ),
                        array(
                            'name' => 'Czech Republic (Česká republika)',
                            'alphacode' => 'cz',
                            'countryCode' => '+420'
                        ),
                        array(
                            'name' => 'Denmark (Danmark)',
                            'alphacode' => 'dk',
                            'countryCode' => '+45'
                        ),
                        array(
                            'name' => 'Djibouti',
                            'alphacode' => 'dj',
                            'countryCode' => '+253'
                        ),
                        array(
                            'name' => 'Dominica',
                            'alphacode' => 'dm',
                            'countryCode' => '+1767'
                        ),
                        array(
                            'name' => 'Dominican Republic (República Dominicana)',
                            'alphacode' => 'do',
                            'countryCode' => '+1'
                        ),
                        array(
                            'name' => 'Ecuador',
                            'alphacode' => 'ec',
                            'countryCode' => '+593'
                        ),
                        array(
                            'name' => 'Egypt (‫مصر‬‎)',
                            'alphacode' => 'eg',
                            'countryCode' => '+20'
                        ),
                        array(
                            'name' => 'El Salvador',
                            'alphacode' => 'sv',
                            'countryCode' => '+503'
                        ),
                        array(
                            'name' => 'Equatorial Guinea (Guinea Ecuatorial)',
                            'alphacode' => 'gq',
                            'countryCode' => '+240'
                        ),
                        array(
                            'name' => 'Eritrea',
                            'alphacode' => 'er',
                            'countryCode' => '+291'
                        ),
                        array(
                            'name' => 'Estonia (Eesti)',
                            'alphacode' => 'ee',
                            'countryCode' => '+372'
                        ),
                        array(
                            'name' => 'Ethiopia',
                            'alphacode' => 'et',
                            'countryCode' => '+251'
                        ),
                        array(
                            'name' => 'Falkland Islands (Islas Malvinas)',
                            'alphacode' => 'fk',
                            'countryCode' => '+500'
                        ),
                        array(
                            'name' => 'Faroe Islands (Føroyar)',
                            'alphacode' => 'fo',
                            'countryCode' => '+298'
                        ),
                        array(
                            'name' => 'Fiji',
                            'alphacode' => 'fj',
                            'countryCode' => '+679'
                        ),
                        array(
                            'name' => 'Finland (Suomi)',
                            'alphacode' => 'fi',
                            'countryCode' => '+358'
                        ),
                        array(
                            'name' => 'France',
                            'alphacode' => 'fr',
                            'countryCode' => '+33'
                        ),
                        array(
                            'name' => 'French Guiana (Guyane française)',
                            'alphacode' => 'gf',
                            'countryCode' => '+594'
                        ),
                        array(
                            'name' => 'French Polynesia (Polynésie française)',
                            'alphacode' => 'pf',
                            'countryCode' => '+689'
                        ),
                        array(
                            'name' => 'Gabon',
                            'alphacode' => 'ga',
                            'countryCode' => '+241'
                        ),
                        array(
                            'name' => 'Gambia',
                            'alphacode' => 'gm',
                            'countryCode' => '+220'
                        ),
                        array(
                            'name' => 'Georgia (საქართველო)',
                            'alphacode' => 'ge',
                            'countryCode' => '+995'
                        ),
                        array(
                            'name' => 'Germany (Deutschland)',
                            'alphacode' => 'de',
                            'countryCode' => '+49'
                        ),
                        array(
                            'name' => 'Ghana (Gaana)',
                            'alphacode' => 'gh',
                            'countryCode' => '+233'
                        ),
                        array(
                            'name' => 'Gibraltar',
                            'alphacode' => 'gi',
                            'countryCode' => '+350'
                        ),
                        array(
                            'name' => 'Greece (Ελλάδα)',
                            'alphacode' => 'gr',
                            'countryCode' => '+30'
                        ),
                        array(
                            'name' => 'Greenland (Kalaallit Nunaat)',
                            'alphacode' => 'gl',
                            'countryCode' => '+299'
                        ),
                        array(
                            'name' => 'Grenada',
                            'alphacode' => 'gd',
                            'countryCode' => '+1473'
                        ),
                        array(
                            'name' => 'Guadeloupe',
                            'alphacode' => 'gp',
                            'countryCode' => '+590'
                        ),
                        array(
                            'name' => 'Guam',
                            'alphacode' => 'gu',
                            'countryCode' => '+1671'
                        ),
                        array(
                            'name' => 'Guatemala',
                            'alphacode' => 'gt',
                            'countryCode' => '+502'
                        ),
                        array(
                            'name' => 'Guernsey',
                            'alphacode' => 'gg',
                            'countryCode' => '+44'
                        ),
                        array(
                            'name' => 'Guinea (Guinée)',
                            'alphacode' => 'gn',
                            'countryCode' => '+224'
                        ),
                        array(
                            'name' => 'Guinea-Bissau (Guiné Bissau)',
                            'alphacode' => 'gw',
                            'countryCode' => '+245'
                        ),
                        array(
                            'name' => 'Guyana',
                            'alphacode' => 'gy',
                            'countryCode' => '+592'
                        ),
                        array(
                            'name' => 'Haiti',
                            'alphacode' => 'ht',
                            'countryCode' => '+509'
                        ),
                        array(
                            'name' => 'Honduras',
                            'alphacode' => 'hn',
                            'countryCode' => '+504'
                        ),
                        array(
                            'name' => 'Hong Kong (香港)',
                            'alphacode' => 'hk',
                            'countryCode' => '+852'
                        ),
                        array(
                            'name' => 'Hungary (Magyarország)',
                            'alphacode' => 'hu',
                            'countryCode' => '+36'
                        ),
                        array(
                            'name' => 'Iceland (Ísland)',
                            'alphacode' => 'is',
                            'countryCode' => '+354'
                        ),
                        array(
                            'name' => 'India (भारत)',
                            'alphacode' => 'in',
                            'countryCode' => '+91'
                        ),
                        array(
                            'name' => 'Indonesia',
                            'alphacode' => 'id',
                            'countryCode' => '+62'
                        ),
                        array(
                            'name' => 'Iran (‫ایران‬‎)',
                            'alphacode' => 'ir',
                            'countryCode' => '+98'
                        ),
                        array(
                            'name' => 'Iraq (‫العراق‬‎)',
                            'alphacode' => 'iq',
                            'countryCode' => '+964'
                        ),
                        array(
                            'name' => 'Ireland',
                            'alphacode' => 'ie',
                            'countryCode' => '+353'
                        ),
                        array(
                            'name' => 'Isle of Man',
                            'alphacode' => 'im',
                            'countryCode' => '+44'
                        ),
                        array(
                            'name' => 'Israel (‫ישראל‬‎)',
                            'alphacode' => 'il',
                            'countryCode' => '+972'
                        ),
                        array(
                            'name' => 'Italy (Italia)',
                            'alphacode' => 'it',
                            'countryCode' => '+39'
                        ),
                        array(
                            'name' => 'Jamaica',
                            'alphacode' => 'jm',
                            'countryCode' => '+1876'
                        ),
                        array(
                            'name' => 'Japan (日本)',
                            'alphacode' => 'jp',
                            'countryCode' => '+81'
                        ),
                        array(
                            'name' => 'Jersey',
                            'alphacode' => 'je',
                            'countryCode' => '+44'
                        ),
                        array(
                            'name' => 'Jordan (‫الأردن‬‎)',
                            'alphacode' => 'jo',
                            'countryCode' => '+962'
                        ),
                        array(
                            'name' => 'Kazakhstan (Казахстан)',
                            'alphacode' => 'kz',
                            'countryCode' => '+7'
                        ),
                        array(
                            'name' => 'Kenya',
                            'alphacode' => 'ke',
                            'countryCode' => '+254'
                        ),
                        array(
                            'name' => 'Kiribati',
                            'alphacode' => 'ki',
                            'countryCode' => '+686'
                        ),
                        array(
                            'name' => 'Kosovo',
                            'alphacode' => 'xk',
                            'countryCode' => '+383'
                        ),
                        array(
                            'name' => 'Kuwait (‫الكويت‬‎)',
                            'alphacode' => 'kw',
                            'countryCode' => '+965'
                        ),
                        array(
                            'name' => 'Kyrgyzstan (Кыргызстан)',
                            'alphacode' => 'kg',
                            'countryCode' => '+996'
                        ),
                        array(
                            'name' => 'Laos (ລາວ)',
                            'alphacode' => 'la',
                            'countryCode' => '+856'
                        ),
                        array(
                            'name' => 'Latvia (Latvija)',
                            'alphacode' => 'lv',
                            'countryCode' => '+371'
                        ),
                        array(
                            'name' => 'Lebanon (‫لبنان‬‎)',
                            'alphacode' => 'lb',
                            'countryCode' => '+961'
                        ),
                        array(
                            'name' => 'Lesotho',
                            'alphacode' => 'ls',
                            'countryCode' => '+266'
                        ),
                        array(
                            'name' => 'Liberia',
                            'alphacode' => 'lr',
                            'countryCode' => '+231'
                        ),
                        array(
                            'name' => 'Libya (‫ليبيا‬‎)',
                            'alphacode' => 'ly',
                            'countryCode' => '+218'
                        ),
                        array(
                            'name' => 'Liechtenstein',
                            'alphacode' => 'li',
                            'countryCode' => '+423'
                        ),
                        array(
                            'name' => 'Lithuania (Lietuva)',
                            'alphacode' => 'lt',
                            'countryCode' => '+370'
                        ),
                        array(
                            'name' => 'Luxembourg',
                            'alphacode' => 'lu',
                            'countryCode' => '+352'
                        ),
                        array(
                            'name' => 'Macau (澳門)',
                            'alphacode' => 'mo',
                            'countryCode' => '+853'
                        ),
                        array(
                            'name' => 'Macedonia (FYROM) (Македонија)',
                            'alphacode' => 'mk',
                            'countryCode' => '+389'
                        ),
                        array(
                            'name' => 'Madagascar (Madagasikara)',
                            'alphacode' => 'mg',
                            'countryCode' => '+261'
                        ),
                        array(
                            'name' => 'Malawi',
                            'alphacode' => 'mw',
                            'countryCode' => '+265'
                        ),
                        array(
                            'name' => 'Malaysia',
                            'alphacode' => 'my',
                            'countryCode' => '+60'
                        ),
                        array(
                            'name' => 'Maldives',
                            'alphacode' => 'mv',
                            'countryCode' => '+960'
                        ),
                        array(
                            'name' => 'Mali',
                            'alphacode' => 'ml',
                            'countryCode' => '+223'
                        ),
                        array(
                            'name' => 'Malta',
                            'alphacode' => 'mt',
                            'countryCode' => '+356'
                        ),
                        array(
                            'name' => 'Marshall Islands',
                            'alphacode' => 'mh',
                            'countryCode' => '+692'
                        ),
                        array(
                            'name' => 'Martinique',
                            'alphacode' => 'mq',
                            'countryCode' => '+596'
                        ),
                        array(
                            'name' => 'Mauritania (‫موريتانيا‬‎)',
                            'alphacode' => 'mr',
                            'countryCode' => '+222'
                        ),
                        array(
                            'name' => 'Mauritius (Moris)',
                            'alphacode' => 'mu',
                            'countryCode' => '+230'
                        ),
                        array(
                            'name' => 'Mayotte',
                            'alphacode' => 'yt',
                            'countryCode' => '+262'
                        ),
                        array(
                            'name' => 'Mexico (México)',
                            'alphacode' => 'mx',
                            'countryCode' => '+52'
                        ),
                        array(
                            'name' => 'Micronesia',
                            'alphacode' => 'fm',
                            'countryCode' => '+691'
                        ),
                        array(
                            'name' => 'Moldova (Republica Moldova)',
                            'alphacode' => 'md',
                            'countryCode' => '+373'
                        ),
                        array(
                            'name' => 'Monaco',
                            'alphacode' => 'mc',
                            'countryCode' => '+377'
                        ),
                        array(
                            'name' => 'Mongolia (Монгол)',
                            'alphacode' => 'mn',
                            'countryCode' => '+976'
                        ),
                        array(
                            'name' => 'Montenegro (Crna Gora)',
                            'alphacode' => 'me',
                            'countryCode' => '+382'
                        ),
                        array(
                            'name' => 'Montserrat',
                            'alphacode' => 'ms',
                            'countryCode' => '+1664'
                        ),
                        array(
                            'name' => 'Morocco (‫المغرب‬‎)',
                            'alphacode' => 'ma',
                            'countryCode' => '+212'
                        ),
                        array(
                            'name' => 'Mozambique (Moçambique)',
                            'alphacode' => 'mz',
                            'countryCode' => '+258'
                        ),
                        array(
                            'name' => 'Myanmar (Burma) (မြန်မာ)',
                            'alphacode' => 'mm',
                            'countryCode' => '+95'
                        ),
                        array(
                            'name' => 'Namibia (Namibië)',
                            'alphacode' => 'na',
                            'countryCode' => '+264'
                        ),
                        array(
                            'name' => 'Nauru',
                            'alphacode' => 'nr',
                            'countryCode' => '+674'
                        ),
                        array(
                            'name' => 'Nepal (नेपाल)',
                            'alphacode' => 'np',
                            'countryCode' => '+977'
                        ),
                        array(
                            'name' => 'Netherlands (Nederland)',
                            'alphacode' => 'nl',
                            'countryCode' => '+31'
                        ),
                        array(
                            'name' => 'New Caledonia (Nouvelle-Calédonie)',
                            'alphacode' => 'nc',
                            'countryCode' => '+687'
                        ),
                        array(
                            'name' => 'New Zealand',
                            'alphacode' => 'nz',
                            'countryCode' => '+64'
                        ),
                        array(
                            'name' => 'Nicaragua',
                            'alphacode' => 'ni',
                            'countryCode' => '+505'
                        ),
                        array(
                            'name' => 'Niger (Nijar)',
                            'alphacode' => 'ne',
                            'countryCode' => '+227'
                        ),
                        array(
                            'name' => 'Nigeria',
                            'alphacode' => 'ng',
                            'countryCode' => '+234'
                        ),
                        array(
                            'name' => 'Niue',
                            'alphacode' => 'nu',
                            'countryCode' => '+683'
                        ),
                        array(
                            'name' => 'Norfolk Island',
                            'alphacode' => 'nf',
                            'countryCode' => '+672'
                        ),
                        array(
                            'name' => 'North Korea (조선 민주주의 인민 공화국)',
                            'alphacode' => 'kp',
                            'countryCode' => '+850'
                        ),
                        array(
                            'name' => 'Northern Mariana Islands',
                            'alphacode' => 'mp',
                            'countryCode' => '+1670'
                        ),
                        array(
                            'name' => 'Norway (Norge)',
                            'alphacode' => 'no',
                            'countryCode' => '+47'
                        ),
                        array(
                            'name' => 'Oman (‫عُمان‬‎)',
                            'alphacode' => 'om',
                            'countryCode' => '+968'
                        ),
                        array(
                            'name' => 'Pakistan (‫پاکستان‬‎)',
                            'alphacode' => 'pk',
                            'countryCode' => '+92'
                        ),
                        array(
                            'name' => 'Palau',
                            'alphacode' => 'pw',
                            'countryCode' => '+680'
                        ),
                        array(
                            'name' => 'Palestine (‫فلسطين‬‎)',
                            'alphacode' => 'ps',
                            'countryCode' => '+970'
                        ),
                        array(
                            'name' => 'Panama (Panamá)',
                            'alphacode' => 'pa',
                            'countryCode' => '+507'
                        ),
                        array(
                            'name' => 'Papua New Guinea',
                            'alphacode' => 'pg',
                            'countryCode' => '+675'
                        ),
                        array(
                            'name' => 'Paraguay',
                            'alphacode' => 'py',
                            'countryCode' => '+595'
                        ),
                        array(
                            'name' => 'Peru (Perú)',
                            'alphacode' => 'pe',
                            'countryCode' => '+51'
                        ),
                        array(
                            'name' => 'Philippines',
                            'alphacode' => 'ph',
                            'countryCode' => '+63'
                        ),
                        array(
                            'name' => 'Poland (Polska)',
                            'alphacode' => 'pl',
                            'countryCode' => '+48'
                        ),
                        array(
                            'name' => 'Portugal',
                            'alphacode' => 'pt',
                            'countryCode' => '+351'
                        ),
                        array(
                            'name' => 'Puerto Rico',
                            'alphacode' => 'pr',
                            'countryCode' => '+1'
                        ),
                        array(
                            'name' => 'Qatar (‫قطر‬‎)',
                            'alphacode' => 'qa',
                            'countryCode' => '+974'
                        ),
                        array(
                            'name' => 'Réunion (La Réunion)',
                            'alphacode' => 're',
                            'countryCode' => '+262'
                        ),
                        array(
                            'name' => 'Romania (România)',
                            'alphacode' => 'ro',
                            'countryCode' => '+40'
                        ),
                        array(
                            'name' => 'Russia (Россия)',
                            'alphacode' => 'ru',
                            'countryCode' => '+7'
                        ),
                        array(
                            'name' => 'Rwanda',
                            'alphacode' => 'rw',
                            'countryCode' => '+250'
                        ),
                        array(
                            'name' => 'Saint Barthélemy',
                            'alphacode' => 'bl',
                            'countryCode' => '+590'
                        ),
                        array(
                            'name' => 'Saint Helena',
                            'alphacode' => 'sh',
                            'countryCode' => '+290'
                        ),
                        array(
                            'name' => 'Saint Kitts and Nevis',
                            'alphacode' => 'kn',
                            'countryCode' => '+1869'
                        ),
                        array(
                            'name' => 'Saint Lucia',
                            'alphacode' => 'lc',
                            'countryCode' => '+1758'
                        ),
                        array(
                            'name' => 'Saint Martin (Saint-Martin (partie française))',
                            'alphacode' => 'mf',
                            'countryCode' => '+590'
                        ),
                        array(
                            'name' => 'Saint Pierre and Miquelon (Saint-Pierre-et-Miquelon)',
                            'alphacode' => 'pm',
                            'countryCode' => '+508'
                        ),
                        array(
                            'name' => 'Saint Vincent and the Grenadines',
                            'alphacode' => 'vc',
                            'countryCode' => '+1784'
                        ),
                        array(
                            'name' => 'Samoa',
                            'alphacode' => 'ws',
                            'countryCode' => '+685'
                        ),
                        array(
                            'name' => 'San Marino',
                            'alphacode' => 'sm',
                            'countryCode' => '+378'
                        ),
                        array(
                            'name' => 'São Tomé and Príncipe (São Tomé e Príncipe)',
                            'alphacode' => 'st',
                            'countryCode' => '+239'
                        ),
                        array(
                            'name' => 'Saudi Arabia (‫المملكة العربية السعودية‬‎)',
                            'alphacode' => 'sa',
                            'countryCode' => '+966'
                        ),
                        array(
                            'name' => 'Senegal (Sénégal)',
                            'alphacode' => 'sn',
                            'countryCode' => '+221'
                        ),
                        array(
                            'name' => 'Serbia (Србија)',
                            'alphacode' => 'rs',
                            'countryCode' => '+381'
                        ),
                        array(
                            'name' => 'Seychelles',
                            'alphacode' => 'sc',
                            'countryCode' => '+248'
                        ),
                        array(
                            'name' => 'Sierra Leone',
                            'alphacode' => 'sl',
                            'countryCode' => '+232'
                        ),
                        array(
                            'name' => 'Singapore',
                            'alphacode' => 'sg',
                            'countryCode' => '+65'
                        ),
                        array(
                            'name' => 'Sint Maarten',
                            'alphacode' => 'sx',
                            'countryCode' => '+1721'
                        ),
                        array(
                            'name' => 'Slovakia (Slovensko)',
                            'alphacode' => 'sk',
                            'countryCode' => '+421'
                        ),
                        array(
                            'name' => 'Slovenia (Slovenija)',
                            'alphacode' => 'si',
                            'countryCode' => '+386'
                        ),
                        array(
                            'name' => 'Solomon Islands',
                            'alphacode' => 'sb',
                            'countryCode' => '+677'
                        ),
                        array(
                            'name' => 'Somalia (Soomaaliya)',
                            'alphacode' => 'so',
                            'countryCode' => '+252'
                        ),
                        array(
                            'name' => 'South Africa',
                            'alphacode' => 'za',
                            'countryCode' => '+27'
                        ),
                        array(
                            'name' => 'South Korea (대한민국)',
                            'alphacode' => 'kr',
                            'countryCode' => '+82'
                        ),
                        array(
                            'name' => 'South Sudan (‫جنوب السودان‬‎)',
                            'alphacode' => 'ss',
                            'countryCode' => '+211'
                        ),
                        array(
                            'name' => 'Spain (España)',
                            'alphacode' => 'es',
                            'countryCode' => '+34'
                        ),
                        array(
                            'name' => 'Sri Lanka (ශ්‍රී ලංකාව)',
                            'alphacode' => 'lk',
                            'countryCode' => '+94'
                        ),
                        array(
                            'name' => 'Sudan (‫السودان‬‎)',
                            'alphacode' => 'sd',
                            'countryCode' => '+249'
                        ),
                        array(
                            'name' => 'Suriname',
                            'alphacode' => 'sr',
                            'countryCode' => '+597'
                        ),
                        array(
                            'name' => 'Svalbard and Jan Mayen',
                            'alphacode' => 'sj',
                            'countryCode' => '+47'
                        ),
                        array(
                            'name' => 'Swaziland',
                            'alphacode' => 'sz',
                            'countryCode' => '+268'
                        ),
                        array(
                            'name' => 'Sweden (Sverige)',
                            'alphacode' => 'se',
                            'countryCode' => '+46'
                        ),
                        array(
                            'name' => 'Switzerland (Schweiz)',
                            'alphacode' => 'ch',
                            'countryCode' => '+41'
                        ),
                        array(
                            'name' => 'Syria (‫سوريا‬‎)',
                            'alphacode' => 'sy',
                            'countryCode' => '+963'
                        ),
                        array(
                            'name' => 'Taiwan (台灣)',
                            'alphacode' => 'tw',
                            'countryCode' => '+886'
                        ),
                        array(
                            'name' => 'Tajikistan',
                            'alphacode' => 'tj',
                            'countryCode' => '+992'
                        ),
                        array(
                            'name' => 'Tanzania',
                            'alphacode' => 'tz',
                            'countryCode' => '+255'
                        ),
                        array(
                            'name' => 'Thailand (ไทย)',
                            'alphacode' => 'th',
                            'countryCode' => '+66'
                        ),
                        array(
                            'name' => 'Timor-Leste',
                            'alphacode' => 'tl',
                            'countryCode' => '+670'
                        ),
                        array(
                            'name' => 'Togo',
                            'alphacode' => 'tg',
                            'countryCode' => '+228'
                        ),
                        array(
                            'name' => 'Tokelau',
                            'alphacode' => 'tk',
                            'countryCode' => '+690'
                        ),
                        array(
                            'name' => 'Tonga',
                            'alphacode' => 'to',
                            'countryCode' => '+676'
                        ),
                        array(
                            'name' => 'Trinidad and Tobago',
                            'alphacode' => 'tt',
                            'countryCode' => '+1868'
                        ),
                        array(
                            'name' => 'Tunisia (‫تونس‬‎)',
                            'alphacode' => 'tn',
                            'countryCode' => '+216'
                        ),
                        array(
                            'name' => 'Turkey (Türkiye)',
                            'alphacode' => 'tr',
                            'countryCode' => '+90'
                        ),
                        array(
                            'name' => 'Turkmenistan',
                            'alphacode' => 'tm',
                            'countryCode' => '+993'
                        ),
                        array(
                            'name' => 'Turks and Caicos Islands',
                            'alphacode' => 'tc',
                            'countryCode' => '+1649'
                        ),
                        array(
                            'name' => 'Tuvalu',
                            'alphacode' => 'tv',
                            'countryCode' => '+688'
                        ),
                        array(
                            'name' => 'U.S. Virgin Islands',
                            'alphacode' => 'vi',
                            'countryCode' => '+1340'
                        ),
                        array(
                            'name' => 'Uganda',
                            'alphacode' => 'ug',
                            'countryCode' => '+256'
                        ),
                        array(
                            'name' => 'Ukraine (Україна)',
                            'alphacode' => 'ua',
                            'countryCode' => '+380'
                        ),
                        array(
                            'name' => 'United Arab Emirates (‫الإمارات العربية المتحدة‬‎)',
                            'alphacode' => 'ae',
                            'countryCode' => '+971'
                        ),
                        array(
                            'name' => 'United Kingdom',
                            'alphacode' => 'gb',
                            'countryCode' => '+44'
                        ),
                        array(
                            'name' => 'United States',
                            'alphacode' => 'us',
                            'countryCode' => '+1'
                        ),
                        array(
                            'name' => 'Uruguay',
                            'alphacode' => 'uy',
                            'countryCode' => '+598'
                        ),
                        array(
                            'name' => 'Uzbekistan (Oʻzbekiston)',
                            'alphacode' => 'uz',
                            'countryCode' => '+998'
                        ),
                        array(
                            'name' => 'Vanuatu',
                            'alphacode' => 'vu',
                            'countryCode' => '+678'
                        ),
                        array(
                            'name' => 'Vatican City (Città del Vaticano)',
                            'alphacode' => 'va',
                            'countryCode' => '+39'
                        ),
                        array(
                            'name' => 'Venezuela',
                            'alphacode' => 've',
                            'countryCode' => '+58'
                        ),
                        array(
                            'name' => 'Vietnam (Việt Nam)',
                            'alphacode' => 'vn',
                            'countryCode' => '+84'
                        ),
                        array(
                            'name' => 'Wallis and Futuna (Wallis-et-Futuna)',
                            'alphacode' => 'wf',
                            'countryCode' => '+681'
                        ),
                        array(
                            'name' => 'Western Sahara (‫الصحراء الغربية‬‎)',
                            'alphacode' => 'eh',
                            'countryCode' => '+212'
                        ),
                        array(
                            'name' => 'Yemen (‫اليمن‬‎)',
                            'alphacode' => 'ye',
                            'countryCode' => '+967'
                        ),
                        array(
                            'name' => 'Zambia',
                            'alphacode' => 'zm',
                            'countryCode' => '+260'
                        ),
                        array(
                            'name' => 'Zimbabwe',
                            'alphacode' => 'zw',
                            'countryCode' => '+263'
                        ),
                        array(
                            'name' => 'Åland Islands',
                            'alphacode' => 'ax',
                            'countryCode' => '+358'
                        ),
                    );
                    return $countries;
        }

            function mo_otp_login_page()
        {
            $admin_email = MoOtpUtility::__getDBLoadResult('email', '#__miniorange_otp_customer');
            ?>
            <form name="f" method="post" action="<?php echo JRoute::_('index.php?option=com_joomlaotp&view=accountsetup&task=accountsetup.verifyCustomer'); ?>">
                 <h3>Login with miniOrange</h3><hr>
                 <p>Please enter your miniOrange account credentials. If you forgot your password then enter your email and click on <b>Forgot your password</b> link. If you are not registered with miniOrange then click on <b>Back To Registration</b> link. </p><br/>
                 <table class="otp-table">
                     <tr>
                        <td class="otp-table-td"><b><font color="#FF0000">*</font>Email:</b></td>
                            <td><input class="form-control otp-textfield" type="email" name="email" id="email"
                                          required placeholder="person@example.com"
                                           value="<?php echo $admin_email; ?>"/></td>
                            </tr>
                            <tr>
                                <td><b><font color="#FF0000">*</font>Password:</b></td>
                                <td><input class="form-control otp-textfield" required type="password"
                                           name="password" placeholder="Enter your miniOrange password"/></td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td><br><input type="submit" class="mo_btn btn-medium mo_btn_success" value="Login"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <a href="#mo_otp_forgot_password_link" class="mo_btn btn-medium mo_btn_success">Forgot your password?</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <a href="#otp_cancel_link" class="mo_btn btn-medium mo_btn_success">Back To Registration</a>
                                </td>
                            </tr>
                        </table>
                    </form>

                    <form id="otp_forgot_password_form" method="post"
                          action="<?php echo JRoute::_('index.php?option=com_joomlaotp&view=accountsetup&task=accountsetup.forgotpassword'); ?>">
                        <input type="hidden" name="current_admin_email" id="current_admin_email" value=""/>
                    </form>
                    <form id="otp_cancel_form" method="post"
                          action="<?php echo JRoute::_('index.php?option=com_joomlaotp&view=accountsetup&task=accountsetup.cancelform'); ?>">
                    </form>
                    <script>

                        jQuery('a[href=#otp_cancel_link]').click(function () {
                            jQuery('#otp_cancel_form').submit();
                        });

                        jQuery('a[href=#mo_otp_forgot_password_link]').click(function () {
                            var email = jQuery('#email').val();
                            jQuery('#current_admin_email').val(email);
                            jQuery('#otp_forgot_password_form').submit();
                        });
                    </script>

                    <?php
        }

            /* Show OTP verification page*/
            function mo_otp_show_otp_verification()
            {
                ?>
                <form name="f" method="post" id="otp_form"
                      action="<?php echo JRoute::_('index.php?option=com_joomlaotp&view=accountsetup&task=accountsetup.validateOtp'); ?>">
                    <h3>Verify Your Email</h3><br/>
                    <table class="otp-table">
                        <!-- Enter otp -->
                        <tr>
                            <td><b><font color="#FF0000">*</font>Enter OTP:</b></td>
                            <td colspan="1"><input class="form-control" autofocus="true" type="text" name="otp_token"
                                                   required
                                                   placeholder="Enter OTP"/>
                                &nbsp;&nbsp;<a href="#mo_otp_resend_otp_email">Resend OTP over Email</a></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td><input type="submit" value="Validate OTP" class="mo_btn btn-medium mo_btn_success"/>&nbsp;&nbsp;&nbsp;
                                <input type="button" value="Back" id="back_btn" class="mo_btn btn-medium btn-danger"/>
                            </td>
                        </tr>
                    </table>
                </form>

                <form method="post"
                      action="<?php echo JRoute::_('index.php?option=com_joomlaotp&view=accountsetup&task=accountsetup.cancelform'); ?>"
                      id="mo_otp_cancel_form">
                </form>
                <form name="f" id="resend_otp_form" method="post"
                      action="<?php echo JRoute::_('index.php?option=com_joomlaotp&view=accountsetup&task=accountsetup.resendOtp'); ?>">
                </form>
                <br>
                <hr>
                <h3>I did not recieve any email with OTP . What should I do ?</h3>
                <form id="phone_verification" method="post"
                      action="<?php echo JRoute::_('index.php?option=com_joomlaotp&view=accountsetup&task=accountsetup.phoneVerification'); ?>">
                    If you can't see the email from miniOrange in your mails, please check your <b>SPAM Folder</b>. If
                    you don't see
                    an email even in SPAM folder, verify your identity with our alternate method.
                    <br><br>
                    <b>Enter your valid phone number here and verify your identity using one time passcode sent to your
                        phone.</b><br><br>
                    <input class="form-control" required="true" pattern="[\+]\d{1,3}\d{10}" autofocus="true" type="text"
                           name="phone_number" id="phone_number" placeholder="Enter Phone Number with country code"
                           style="width:40%;"
                           title="Enter phone number without any space or dashes with country code."/>
                    <br><input type="submit" value="Send OTP" class="mo_btn btn-medium mo_btn_success"/>
                </form>
                <script>
                    jQuery('#back_btn').click(function () {
                        jQuery('#mo_otp_cancel_form').submit();
                    });

                    jQuery('a[href=#mo_otp_resend_otp_email]').click(function () {
                        jQuery('#resend_otp_form').submit();
                    });
                </script>
                <?php
            }

            /* Create Customer function */
            function mo_otp_registration_page()
            {
                $current_user = JFactory::getUser();
                $isUserEnabled = JPluginHelper::isEnabled('user', 'miniorangesendotp');
                $isSystemEnabled = JPluginHelper::isEnabled('system', 'miniorangeverifyotp');
                if (!$isSystemEnabled || !$isUserEnabled)
                {
                    ?>
                    <div id="system-message-container">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <div class="alert alert-error">
                            <h4 class="alert-heading">Warning!</h4>
                            <div class="alert-message">
                                <h4>This component requires User and System Plugin to be activated. Please activate the
                                    following 2
                                    plugins
                                    to proceed further.</h4>
                                <ul>
                                    <li>User - miniOrange OTP Verification</li>
                                    <li>PLG_SYSTEM_MINIORANGEVERIFYOTP_NAME</li>
                                </ul>
                                <h4>Steps to activate the plugins.</h4>
                                <ul>
                                    <li>In the top menu, click on Extensions and select Plugins.</li>
                                    <li>Search for miniOrange in the search box and press 'Search' to display the plugins.
                                    </li>
                                    <li>Now enable both User and System plugin.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                <?php
                } ?>

                <!--Register with miniOrange-->
                <form name="f" method="post"
                      action="<?php echo JRoute::_('index.php?option=com_joomlaotp&view=accountsetup&task=accountsetup.registerCustomer'); ?>">
                    <input type="hidden" name="option1" value="mo_saml_local_verify_customer"/>
                    <div style="min-height: 274px;">
                        <h3>Register with miniOrange</h3> <a href="#otp_account_exist" class="mo_btn btn-medium mo_btn_success" style="margin-left: 65%;margin-top: -58px;"><b>Already registered with miniOrange?</b></a><hr>
                        <p>Just complete the short registration below to configure your OTP Verification module. Please
                            enter a valid email id that you have access to.
                            You will be able to move forward after verifying an OTP that we will send to this email.</p><br/>
                        <p style="color: green">If you face any issues during registraion then you can <a href="https://www.miniorange.com/businessfreetrial" target="_blank"><b>click here</b></a> to quick register your account with miniOrange
                            and use the same credentials to login into the plugin.</br>.</p><br>
                        <table class="otp-table">
                            <tr>
                                <td class="otp-table-td"><b><font color="#FF0000">*</font>Email:</b></td>
                                <td>
                                    <input class="form-control otp-textfield" type="email" name="email"
                                           required placeholder="person@example.com" style="width: 73%;"
                                           value=""/>
                                </td>
                            </tr>
                            <tr>
                                <td><b>Phone number:</b></td>
                                <td><input class="form-control otp-textfield" type="tel" id="phone"
                                           pattern="[\+]\d{11,14}|[\+]\d{1,4}([\s]{0,1})(\d{0}|\d{9,10})" name="phone"
                                           title="Phone with country code eg. +1xxxxxxxxxx"
                                           placeholder="Phone with country code eg. +1xxxxxxxxxx" style="width: 73%"/>
                                    <i>We will call only if you need assistance.</i>
                                </td>
                            </tr>
                            <tr>
                                <td><b><font color="#FF0000">*</font>Password:</b></td>
                                <td><input class="form-control otp-textfield" required type="password"
                                           style="width: 73%"
                                           name="password" placeholder="Choose your password (Min. length 6)"/>
                                </td>
                            </tr>
                            <tr>
                                <td><b><font color="#FF0000">*</font>Confirm Password:</b></td>
                                <td><input class="form-control otp-textfield" required type="password"
                                           style="width: 73%"
                                           name="confirmPassword" placeholder="Confirm your password"/>
                                </td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td><input type="submit" value="Register"
                                           style="display:block; margin-left:23%; margin-right:auto;"
                                           class="mo_btn btn-medium mo_btn_success"/></td>
                            </tr>
                        </table>
                    </div>
                </form>

                <form name="f" id="resend_otp_form" method="post"
                      action="<?php echo JRoute::_('index.php?option=com_joomlaotp&view=accountsetup&task=accountsetup.customerLoginForm'); ?> ">
                </form>
                <script>
                    jQuery('a[href=#otp_account_exist]').click(function () {
                        jQuery('#resend_otp_form').submit();
                    });
                </script>
                <?php
            }

            function mo_otp_settings_tab()
            {

                $result = MoOtpUtility::__getDBValuesWOArray('#__miniorange_otp_customer');
                $enable_otp = $result['registration_otp_type'];
                $enable_during_registration = $result['enable_during_registration'];

                if (MoOtpUtility::is_customer_registered()) $disabled = true;
                else $disabled = false;
                ?>

                <form action="<?php echo JRoute::_('index.php?option=com_joomlaotp&view=accountsetup&task=accountsetup.saveOTP'); ?>"
                      method="post" name="adminForm" id="otp_form" style="width:85%;float: left;margin-right: 10px;">
                    <input id="mo_otp_form_action" type="hidden" name="option9" value="mo_otp"/>

                    <b> <input type="checkbox" name="otp_during_registration" id="otp_during_registration"
                               onclick="enfun1()"
                               value="1" ;
                            <?php if ($enable_during_registration == 1) echo "checked"; ?>
                               style="float: left;margin-right: 10px;" <?php if ($disabled) echo "enabled";
                        else echo "disabled"; ?>>
                        Enable During Registration
                    </b><br><br/>
                    <b> <input type="radio" checked name="login_otp_type" value="1" class="login_otp_type" ;
                            <?php if ($enable_otp == 1) echo "checked"; ?>
                               style="float: left;margin-left: 44px;" <?php if ($disabled) echo "enabled";
                        else echo "disabled"; ?>>
                        Enable Email Verification
                    </b>
                    <br><br/>
                    <b> <input type="radio" name="login_otp_type" value="2" class="login_otp_type" ;
                            <?php if ($enable_otp == 2) echo "checked"; ?>
                               style="float: left;margin-left: 44px;" <?php if ($disabled) echo "enabled";
                        else echo "disabled"; ?>>
                        Enable OTP over SMS Verification
                    </b>

                    <br><br><p><a href="https://plugins.miniorange.com/step-by-step-guide-for-joomla-otp-verification" target="_blank"><b>Click here</b></a> to see how to add custom phone field during register in Joomla.</p><br><br>
                    <input type="submit" style="margin-left: 50%" class="mo_btn btn-medium mo_btn_success"
                           value="Save Settings" <?php if ($disabled) echo "enabled";
                    else echo "disabled"; ?>>


                    <h4>Enable During Login</h4><hr>
                    <div>If you want to use a second layer of security to your users Joomla accounts during login then you can use our Two Factor Authentication plugin.
                     We support OTP over SMS and Email, QR Code, Push notification, Soft token (15+ methods to choose from).
                    </div><br>
                    <a class='collapsed' data-toggle='collapse' href='#advance_verification_through_2fa' aria-expanded='false'><b>Install Joomla Two Factor (2FA) plugin</b></a>
                    <div id='advance_verification_through_2fa' class='collapse'>
                        <ul><br>
                            <li>For Mobile Authentication you need to have miniOrange 2 Factor plugin installed.</li>
                            <li><a href="https://prod-marketing-site.s3.amazonaws.com/plugins/joomla/joomla-2fa-plugin.zip" target="_blank"><b>Click here</b></a> to download Two Factor (2FA) Plugin.</li>
                            <li>Please follow the below mentioned steps to install the Joomla Two Factor Authentication plugin which one you have downloaded.</li>
                            <li>
                                Steps for Installing:<br>
                                1) Go to Extensions --> Manage --> Install.<br>
                                2) Select Upload Package File and choose the latest downloaded plugin.
                            </li>
                        </ul>
                    </div>
                </form>
                <script type="text/javascript">
                    var v = document.getElementsByClassName('login_otp_type');
                    var v1 = document.getElementsByClassName('registration_otp_type');
                    var check_box_var = document.getElementById('otp_during_registration');
                    var check_box_var1 = document.getElementById('otp_during_login');
                    enfun1();
                    function enfun1() {
                        var v = document.getElementsByClassName('login_otp_type');
                        var check_box_var = document.getElementById('otp_during_registration');
                        if (check_box_var.checked == false) {
                            v[0].disabled = true;
                            v[1].disabled = true;
                        } else {
                            v[0].disabled = false;
                            v[1].disabled = false;
                        }
                    }
                </script>
                <?php
            }

            function mo_otp_account_page()
            {
                /*$db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select('*');
                $query->from($db->quoteName('#__miniorange_otp_customer'));
                $query->where($db->quoteName('id') . " = 1");
                $db->setQuery($query);
                $result = $db->loadAssoc();*/

                $result = MoOtpUtility::__getDBValuesWOArray('#__miniorange_otp_customer');

                //$enable_otp = $result['registration_otp_type'];
                //$enable_otp_over_sms = $result['login_otp_type'];

                $email = isset($result['email']) ? $result['email'] : '';
                $customer_key = isset($result['customer_key']) ? $result['customer_key'] : '';
                $api_key = isset($result['api_key']) ? $result['api_key'] : '';
                $customer_token = isset($result['customer_token']) ? $result['customer_token'] : '';
                $isUserEnabled = JPluginHelper::isEnabled('user', 'miniorangesendotp');
                $isSystemEnabled = JPluginHelper::isEnabled('system', 'miniorangeverifyotp');
                if (!$isSystemEnabled || !$isUserEnabled)
                {
                    ?>
                    <div id="system-message-container">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <div class="alert alert-error">
                        <h4 class="alert-heading">Warning!</h4>
                        <div class="alert-message">
                            <h4>This component requires User and System Plugin to be activated. Please activate the following 2 plugins to proceed further.</h4>
                            <ul>
                                <li>User - miniOrange OTP Verification</li>
                                <li>PLG_SYSTEM_MINIORANGEVERIFYOTP_NAME</li>
                            </ul>
                            <h4>Steps to activate the plugins.</h4>
                            <ul>
                                <li>In the top menu, click on Extensions and select Plugins.</li>
                                <li>Search for miniOrange in the search box and press 'Search' to display the plugins.
                                </li>
                                <li>Now enable both User and System plugin.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            <?php
            }

            $url = "https://login.xecurify.com/moas/login?username=$email&redirectUrl=https://login.xecurify.com/moas/viewtransactions; " ?>
                <input type="submit" onclick="click_to_view_transaction()" value="View Transactions"
                       style="float: right;"
                       class="mo_btn btn-medium mo_btn_success"/>
                <script>
                    var url = "<?php echo $url ?>";

                    function click_to_view_transaction() {
                        window.open(url, "_blank");
                    }
                </script>
                <p><b>Thank You for registering with miniOrange.</b><p>
                <table style="width:100%">
                    <tbody>
                    <tr>
                        <td colspan="2">
                            <h3>
                                YOUR PROFILE
                                <span style="float:right;margin-top:-10px;">
                        <span class="dashicons dashicons-arrow-up toggle-div" data-show="false"
                              data-toggle="otp_settings">
                        </span>
                    </span>
                            </h3>
                            <hr>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <table class="table table-striped table-hover table-bordered otp-table">
                    <tr>
                        <td><b>Username/Email</b></td>
                        <td><?php echo $email ?></td>
                    </tr>
                    <tr>
                        <td><b>Customer ID</b></td>
                        <td><?php echo $customer_key ?></td>
                    </tr>
                    <tr>
                        <td><b>API Key</b></td>
                        <td><?php echo $api_key ?></td>
                    </tr>
                    <tr>
                        <td><b>Token Key</b></td>
                        <td><?php echo $customer_token ?></td>
                    </tr>
                </table>

                <form id="otp_forgot_password_form" method="post"
                      action="<?php echo JRoute::_('index.php?option=com_joomlaotp&view=accountsetup&task=accountsetup.forgotpassword'); ?>">
                    <input type="hidden" name="current_admin_email" id="current_admin_email" value=""/>
                </form>

                <script>
                    jQuery('a[href=#mo_otp_forgot_password_link]').click(function () {
                        var email = jQuery('#admin_emailid').val();
                        jQuery('#current_admin_email').val(email);
                        jQuery('#otp_forgot_password_form').submit();
                    });
                </script>

                <?php
            }

            function mo_saml_otp_network()
            {

                ?>

                <form name="f1">
                    <h4 style="text-align: center;">Looking for a Joomla Web Security plugin?</h4>
                    <table id="otp_support" class="otp-table">

                        <tr>
                            <th class="" style="border: none; padding-bottom: 4%;"><img
                                        src="<?php echo JURI::root(); ?>administrator/components/com_joomlaotp/assets/images/security.jpg"
                                        alt="miniOrange icon" height=100px width=40%>
                                <h3>
                                    <img src="<?php echo JURI::root(); ?>administrator/components/com_joomlaotp/assets/images/miniorange.png"
                                         alt="miniOrange icon" height=50px width=50px>&nbsp;&nbsp;&nbsp;Joomla Web Security Lite</h3>
                            </th>
                        </tr>

                        <tr>
                            <td style="text-align: center">
                                Building a website is a time-consuming process that requires tremendous efforts. For smooth
                                functioning and protection from any sort of web attack appropriate security is essential and we
                                ensure to provide the best website security solutions available in the market.
                                We provide you enterprise-level security, protecting your Joomla site from hackers and malware.
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-left: 15%"><br>
                                <a href="https://prod-marketing-site.s3.amazonaws.com/plugins/joomla/miniorange_joomla_network_security.zip"
                                   class="btn btn-primary" style="padding: 4px 10px;">Download Plugin</a>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a
                                        href="https://plugins.miniorange.com/joomla-network-security" class="btn btn-success"
                                        style="padding: 4px 10px;" target="_blank">Know More</a>
                            </td>
                        </tr>
                    </table>
                </form>

                <?php
            }

            function mo_saml_otp_2fa()
            {
                ?>


                <form name="f2">
                    <h4 style="text-align: center;">Looking for a Joomla Two-Factor Authentication (2FA)?</h4>
                    <table class="otp-table">

                        <tr>
                            <th class="" style="border: none; padding-bottom: 4%;"><img
                                        src="<?php echo JURI::root(); ?>administrator/components/com_joomlaotp/assets/images/2fa.png"
                                        alt="miniOrange icon" height=100px width=80%>
                                <h3>
                                    <img src="<?php echo JURI::root(); ?>administrator/components/com_joomlaotp/assets/images/miniorange.png"
                                         alt="miniOrange icon" height=50px width=50px>&nbsp;&nbsp;&nbsp;Two-Factor Authentication
                                    (2FA)</h3></th>
                        </tr>
                        <tr>
                            <td style="text-align: center">
                                Two Factor Authentication (2FA) plugin adds a second layer of authentication at the time of login to
                                secure your Joomla accounts. It is a highly secure and easy to setup plugin which protects your site
                                from hacks and unauthorized login attempts.
                            </td>
                        </tr>

                        <tr>
                            <td style="padding-left: 15%"><br>
                                <a href="https://prod-marketing-site.s3.amazonaws.com/plugins/joomla/joomla-2fa-plugin.zip" class="btn btn-primary"
                                   style="padding: 4px 10px;">Download Plugin</a>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a
                                        href="https://plugins.miniorange.com/joomla-two-factor-authentication-2fa"
                                        class="btn btn-success" style="padding: 4px 10px;" target="_blank">Know More</a>
                            </td>
                        </tr>

                    </table>
                </form>

                <?php
            }

            function mo_otp_support()
            {
                $result = MoOtpUtility::__getDBValuesWOArray('#__miniorange_otp_customer');
                $admin_email = isset($result['email']) ? $result['email'] : '';
                $admin_phone = isset($result['admin_phone']) ? $result['admin_phone'] : '';
                ?>
                <div id="mosaml_support_button_1234" class="mo_saml_table_layout_support_btn">

                    <input type="button" class="btn btn-primary" id="mo_support_btn" value="Support" onclick="support_form_open();"/>

                        <div id="Support_Section" class="mo_saml_table_layout_support_1">
                    <form name="f" method="post"
                      action="<?php echo JRoute::_('index.php?option=com_joomlaotp&view=accountsetup&task=accountsetup.contactUs'); ?>">
                      <h3>Support/Feature Request</h3><hr>
                    <p class="otp-table">Need any help? We can help you with configuring your OTP Verification plugin.
                        Just send us a query and we will get back to you soon.</p>
                    <table class="otp-table">
                        <tr>
                            <td>
                                <input type="email" class="mo_saml_support_table_textbox otp-textfield"
                                       id="query_email"
                                       name="query_email" value="<?php echo $admin_email; ?>"
                                       placeholder="Enter your email"
                                       required/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="text" class="mo_saml_support_table_textbox mo_textarea_css"
                                       name="query_phone"
                                       id="query_phone" value="<?php echo $admin_phone; ?>"
                                       placeholder="Enter your phone with country code"/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <textarea id="query" name="query" class="form-control mo_textarea_css otp-table"
                                  style="border-radius:4px;resize: vertical;" cols="52" rows="7"
                                  onkeyup="mo_otp_valid(this)" onblur="mo_otp_valid(this)"
                                  onkeypress="mo_otp_valid(this)" placeholder="Write your query here"></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="submit" name="send_query" id="send_query" value="Submit Query"
                                       style="margin-bottom:3%;display:block; margin-right:auto;margin-left: 39%;"
                                       class="mo_btn btn-medium mo_btn_success"/>
                            </td>
                        </tr>
                    </table>
                </form>
                    </div>
                    </div>

                    <div hidden id="mosaml-feedback-overlay"></div>
                <script>

                    function mo_otp_valid(f) {
                        !(/^[a-zA-Z?,.\(\)\/@ 0-9]*$/).test(f.value) ? f.value = f.value.replace(/[^a-zA-Z?,.\(\)\/@ 0-9]/, '') : null;
                    }

                    function support_form_open() {

                        var n = jQuery("#mosaml_support_button_1234").css("right");

                        if (n != "-411px") {
                            jQuery("#mosaml-feedback-overlay").show();
                            jQuery("#mosaml_support_button_1234").animate({
                                right: "-411px"
                            });
                        } else {

                            jQuery("#mosaml-feedback-overlay").hide();
                            jQuery("#mosaml_support_button_1234").animate({
                                right: "-825px"
                            });
                        }
                    }
                </script>
                <?php
            }