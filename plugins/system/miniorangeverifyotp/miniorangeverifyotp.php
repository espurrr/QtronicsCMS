<?php

/**
 * @package     Joomla.System
 * @subpackage  plg_system_miniorangeverifyotp
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
JHtml::stylesheet(JURI::base() . 'components/com_joomlaotp/assets/css/miniorange_otp.css', array(), true);

jimport('joomla.plugin.plugin');
jimport('miniorangesendotp.miniorange_form_handler');

/**
 * miniOrange SAML System plugin
 */
class plgSystemMiniorangeverifyotp extends JPlugin
{

    /**
     * @return bool
     * @throws Exception
     */
    public function onAfterInitialise()
    {
        require_once JPATH_ROOT . DIRECTORY_SEPARATOR . 'administrator' . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_joomlaotp' . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'mo_otp_utility.php';

        $post = JFactory::getApplication()->input->post->getArray();
        $result = MoOtpUtility::__getDBValuesWOArray('#__miniorange_otp_customer');
        $rows = MoOtpUtility::__loadObjectList('#__users');
        $user_name = isset($post['username']) ? $post['username'] : '';

        /*foreach ($rows as $row) {
            if ($user_name == $row->name)
                $user_emails = isset ($row->email) ? $row->email : '';
        }*/

        if ($result['reg_restriction'] == 1) {
            if ($result['white_or_black'] == 1) {
                $allowed_domains = isset ($result['mo_otp_allowed_email_domains']) ? $result['mo_otp_allowed_email_domains'] : 0;
            } elseif ($result['white_or_black'] == 2) {
                $blocked_domains = isset ($result['mo_otp_allowed_email_domains']) ? $result['mo_otp_allowed_email_domains'] : 0;
            }
        }

        $blocked_domains = isset($blocked_domains) ? explode(';', $blocked_domains) : [0];
        $allowed_domains = isset($allowed_domains) ? explode(';', $allowed_domains) : [0];

        if ($result['reg_restriction'] == 1) {
            isset ($post['jform']['email1']) ? $domain = explode('@', $post['jform']['email1'])[1] : '';
            $crnt_email = isset($post['jform']['email1']) ? $post['jform']['email1'] : '';
        }

        $phone_number = isset($post['jform']['profile']['phone']) ? $post['jform']['profile']['phone'] : '';
        $is_blocked = MoOtpUtility::_check_country_code_blocked($phone_number);

        if ($is_blocked) {
            $is_phone = true;
            $is_email = false;
            self::_show_blocked_message($is_phone, $is_email);
        }

        if (isset($domain)) {
            if (!((array_search($domain, $blocked_domains) === FALSE || empty($blocked_domains[0])) && ((array_search($domain, $allowed_domains) !== FALSE) || empty($allowed_domains[0])))) {
                $is_phone = false;
                $is_email = true;
                self::_show_blocked_message($is_phone, $is_email);
            }
        }

        if (isset($post['mojsp_feedback'])) {
            self::_get_feedback_form($post);
        } else {
            require_once JPATH_ROOT . DIRECTORY_SEPARATOR . 'plugins' . DIRECTORY_SEPARATOR . 'user' . DIRECTORY_SEPARATOR . 'miniorangesendotp' . DIRECTORY_SEPARATOR . 'miniorange_form_handler.php';
            require_once JPATH_ROOT . DIRECTORY_SEPARATOR . 'plugins' . DIRECTORY_SEPARATOR . 'user' . DIRECTORY_SEPARATOR . 'miniorangesendotp' . DIRECTORY_SEPARATOR . 'moutility.php';
            require_once JPATH_ROOT . DIRECTORY_SEPARATOR . 'plugins' . DIRECTORY_SEPARATOR . 'user' . DIRECTORY_SEPARATOR . 'miniorangesendotp' . DIRECTORY_SEPARATOR . 'curl.php';
            require_once JPATH_ROOT . DIRECTORY_SEPARATOR . 'plugins' . DIRECTORY_SEPARATOR . 'user' . DIRECTORY_SEPARATOR . 'miniorangesendotp' . DIRECTORY_SEPARATOR . 'constants.php';

            if (isset($post) && !empty($post)) {
                if (!isset($post['option1'])) {
                    $post['option1'] = 'First_time_allowed';
                }
                if (!isset($post['task'])) {
                    $post['task'] = 'allowed_first_time';
                }
                if (($post['task'] == 'registration.register') && ($post['option1'] != 'miniorange-validate-otp-form')) {

                    require_once JPATH_ROOT . DIRECTORY_SEPARATOR . 'administrator' . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_joomlaotp' . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'mo_otp_utility.php';

                    $errors = NULL;
                    $extra_data = NULL;
                    $customer_details = MoOtpUtility::getCustomerDetails();
                    $registration_otp_type = isset($customer_details['registration_otp_type']) ? $customer_details['registration_otp_type'] : '';
                    $username = isset($post['jform']['username']) ? $post['jform']['username'] : '';
                    $email = isset($post['jform']['email1']) ? $post['jform']['email1'] : '';
                    $phone_number = isset($post['jform']['profile']['phone']) ? $post['jform']['profile']['phone'] : '';
                    $password = isset($post['jform']['password1']) ? $post['jform']['password1'] : '';

                    PlgUserMiniorangesendotp::startVerificationProcess($registration_otp_type, $username, $email, $errors, $phone_number, $password, $extra_data);
                }
            }
            miniorange_customer_validation_handle_form();
        }
    }

    public function onUserLogout()
    {
        $app = JFactory::getApplication();
        $user = JFactory::getUser();
        $isAdmin = $this->adminBypass($user);
        if ($isAdmin == true) {
            return;
        }

        /* $user_id = $user->get('id');
         $result = MoOtpUtility::__getDBValuesWOArray('#__miniorange_otp_customer');

         if(isset($result['redirect_after_logout']) && ($result['redirect_after_logout'] != ""))
             $logout_redirect_url = $result['redirect_after_logout'];
         else
             $logout_redirect_url = JURI::root();

         $session = JFactory::getSession();
         $session->destroy();
         $app->redirect($logout_redirect_url);*/
    }

    public function adminBypass($user)
    {
        $userId = $user->id;
        $groups = JAccess::getGroupsByUser($userId);
        foreach ($groups as $key => $value) {
            if ($value == 7 || $value == 8) {
                return true;
            }
        }
    }

    function onExtensionBeforeUninstall($id)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('extension_id');
        $query->from('#__extensions');
        $query->where($db->quoteName('name') . " = " . $db->quote('COM_JOOMLAOTP'));
        $db->setQuery($query);
        $result = $db->loadColumn();

        $tables = JFactory::getDbo()->getTableList();
        $tab = 0;
        foreach ($tables as $table) {
            if (strpos($table, "miniorange_otp_customer"))
                $tab = $table;
        }

        if ($tab) {
            jimport('miniorangesamlplugin.utility.Utilities');
            $current_user = JFactory::getUser();
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select('uninstall_feedback') . "," . 'email';
            $query->from('#__miniorange_otp_customer');
            $query->where($db->quoteName('id') . " = " . $db->quote(1));
            $db->setQuery($query);
            $fid = $db->loadColumn();
            //$user_email=isset($session->get('user_email'))?$session->get('user_email'):'';
            $customerResult = $db->loadResults();
            //$admin_email = $db->loadResults();
            $admin_email = isset($current_user->email) ? $current_user->email : '';

            $post = JFactory::getApplication()->input->post->getArray();
            $tpostData = $post;

            foreach ($fid as $value) {
                if ($value == 0) {
                    foreach ($result as $results) {
                        if ($results == $id) {
                            ?>
                            <div class="form-style-6 ">
                                <!-- <span class="mojsp_close">&times;</span> -->
                                <h1>Feedback for Joomla OTP</h1>
                                <h3>Email </h3>
                                <form name="f" method="post" action="" id="mojsp_feedback">
                                    <input type="hidden" name="mojsp_feedback" value="mojsp_feedback"/>
                                    <div>
                                        <table class="otp-table">
                                            <tr>
                                                <td>
                                                    <input type="email" id="query_email" name="query_email"
                                                           style="width: 100%" value="<?php echo $admin_email; ?>"
                                                           placeholder="Enter your email" required/>
                                                </td>
                                            </tr>
                                        </table>
                                        <h3>What Happened? </h3>
                                        <p style="margin-left:2%">
                                            <?php
                                            $deactivate_reasons = array(
                                                "Facing issues During Registration",
                                                "Not receiving OTP during Registration",
                                                "Does not have the features I'm looking for",
                                                "Not able to Configure",
                                                "Bugs in the plugin",
                                                "Other Reasons:"
                                            );
                                            foreach ($deactivate_reasons

                                            as $deactivate_reasons) { ?>
                                        <div class=" radio " style="padding:1px;margin-left:2%">
                                            <label style="font-weight:normal;font-size:14.6px"
                                                   for="<?php echo $deactivate_reasons; ?>">
                                                <input type="radio" name="deactivate_plugin"
                                                       value="<?php echo $deactivate_reasons; ?>" required>
                                                <?php echo $deactivate_reasons; ?></label>
                                        </div>
                                        <?php } ?>
                                        <br>
                                        <textarea id="query_feedback" name="query_feedback" rows="4" style="width:100%"
                                                  cols="50" placeholder="Write your query here"></textarea>

                                        <?php
                                        foreach ($tpostData['cid'] as $key) { ?>
                                            <input type="hidden" name="result[]" value=<?php echo $key ?>>

                                        <?php } ?>

                                        <br><br>
                                        <div class="mojsp_modal-footer">
                                            <input type="submit" name="miniorange_feedback_submit"
                                                   class="button button-primary button-large" value="Submit"/>
                                        </div>
                                    </div>
                                </form>
                                <form name="f" method="post" action="" id="mojsp_feedback_form_close">
                                    <input type="hidden" name="option" value="mojsp_skip_feedback"/>
                                </form>
                            </div>
                            <script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
                            <script>
                                jQuery('input:radio[name="deactivate_plugin"]').click(function () {
                                    var reason = jQuery(this).val();
                                    jQuery('#query_feedback').removeAttr('required')
                                    if (reason == 'Facing issues During Registration') {
                                        jQuery('#query_feedback').attr("placeholder", "Can you please describe the issue in detail?");
                                    } else if (reason == "Not receving OTP during Registration") {
                                        jQuery('#query_feedback').attr("placeholder", "Could you please describe in detail");
                                    } else if (reason == "Does not have the features I'm looking for") {
                                        jQuery('#query_feedback').attr("placeholder", "Let us know what feature are you looking for");
                                    } else if (reason == "Bugs in the plugin") {
                                        jQuery('#query_feedback').attr("placeholder", "Could you please describe the bug in detail");
                                    } else if (reason == "Other Reasons:") {
                                        jQuery('#query_feedback').attr("placeholder", "Can you let us know the reason for deactivation");
                                        jQuery('#query_feedback').prop('required', true);
                                    } else if (reason == "Not able to Configure") {
                                        jQuery('#query_feedback').attr("placeholder", "Not able to Configure? let us know so that we can improve the interface");
                                    }
                                });
                                // When the user clicks on <span> (x), mojsp_close the mojsp_modal
                                var span = document.getElementsByClassName("mojsp_close")[0];
                                span.onclick = function () {
                                    mojsp_modal.style.display = "none";
                                    jQuery('#mojsp_feedback_form_close').submit();
                                }
                            </script>
                            <style type="text/css">
                                .form-style-6 {
                                    font: 95% Arial, Helvetica, sans-serif;
                                    max-width: 400px;
                                    margin: 10px auto;
                                    padding: 16px;
                                    background: #F7F7F7;
                                }

                                .form-style-6 h1 {
                                    background: #43D1AF;
                                    padding: 20px 0;
                                    font-size: 140%;
                                    font-weight: 300;
                                    text-align: center;
                                    color: #fff;
                                    margin: -16px -16px 16px -16px;
                                }

                                .form-style-6 input[type="text"],
                                .form-style-6 input[type="date"],
                                .form-style-6 input[type="datetime"],
                                .form-style-6 input[type="email"],
                                .form-style-6 input[type="number"],
                                .form-style-6 input[type="search"],
                                .form-style-6 input[type="time"],
                                .form-style-6 input[type="url"],
                                .form-style-6 textarea,
                                .form-style-6 select {
                                    -webkit-transition: all 0.30s ease-in-out;
                                    -moz-transition: all 0.30s ease-in-out;
                                    -ms-transition: all 0.30s ease-in-out;
                                    -o-transition: all 0.30s ease-in-out;
                                    outline: none;
                                    box-sizing: border-box;
                                    -webkit-box-sizing: border-box;
                                    -moz-box-sizing: border-box;
                                    width: 100%;
                                    background: #fff;
                                    margin-bottom: 4%;
                                    border: 1px solid #ccc;
                                    padding: 3%;
                                    color: #555;
                                    font: 95% Arial, Helvetica, sans-serif;
                                }

                                .form-style-6 input[type="text"]:focus,
                                .form-style-6 input[type="date"]:focus,
                                .form-style-6 input[type="datetime"]:focus,
                                .form-style-6 input[type="email"]:focus,
                                .form-style-6 input[type="number"]:focus,
                                .form-style-6 input[type="search"]:focus,
                                .form-style-6 input[type="time"]:focus,
                                .form-style-6 input[type="url"]:focus,
                                .form-style-6 textarea:focus,
                                .form-style-6 select:focus {
                                    box-shadow: 0 0 5px #43D1AF;
                                    padding: 3%;
                                    border: 1px solid #43D1AF;
                                }

                                .form-style-6 input[type="submit"],
                                .form-style-6 input[type="button"] {
                                    box-sizing: border-box;
                                    -webkit-box-sizing: border-box;
                                    -moz-box-sizing: border-box;
                                    width: 100%;
                                    padding: 3%;
                                    background: #43D1AF;
                                    border-bottom: 2px solid #30C29E;
                                    border-top-style: none;
                                    border-right-style: none;
                                    border-left-style: none;
                                    color: #fff;
                                }

                                .form-style-6 input[type="submit"]:hover,
                                .form-style-6 input[type="button"]:hover {
                                    background: #2EBC99;
                                }
                            </style>
                            <?php
                            exit;
                        }
                    }
                }
            }
        }
    }

    function _get_feedback_form($post)
    {
        jimport('miniorangesamlplugin.utility.Utilities');
        $radio = isset($post['deactivate_plugin']) ? $post['deactivate_plugin'] : '';
        $data = isset($post['query_feedback']) ? $post['query_feedback'] : '';

        $db_table = '#__miniorange_otp_customer';
        $db_coloums = array('uninstall_feedback' => 1,);

        MoOtpUtility::__genDBUpdate($db_table, $db_coloums);
        $customerResult = MoOtpUtility::__getDBValuesArray('#__miniorange_otp_customer');

        $admin_email = isset($post['query_email']) ? $post['query_email'] : '';
        $admin_phone = isset($customerResult['admin_phone']) ? $customerResult['admin_phone'] : '';
        $data1 = $radio . ' : ' . $data;

        require_once JPATH_BASE . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_joomlaotp' . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'mo_otp_customer_setup.php';
        MoOtpCustomer::submit_feedback_form($admin_email, $admin_phone, $data1);
        require_once JPATH_SITE . DIRECTORY_SEPARATOR . 'libraries' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'Installer' . DIRECTORY_SEPARATOR . 'Installer.php';

        foreach ($post['result'] as $fbkey) {
            $result = MoOtpUtility::__getDBValuesUsingColumns('type', '#__extensions', $fbkey);
            $identifier = $fbkey;
            $type = 0;

            foreach ($result as $results) {
                $type = $results;
            }
            if ($type) {
                $cid = 0;
                $installer = new JInstaller();
                $installer->uninstall($type, $identifier, $cid);
            }
        }
    }

    function _show_blocked_message($is_phone, $is_email)
    {
        require_once JPATH_ROOT . DIRECTORY_SEPARATOR . 'administrator' . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_joomlaotp' . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'mo_otp_utility.php';
        $result = MoOtpUtility::_get_custom_message();

        if ($is_phone) {
            $custom_blocked_phone_message = isset($result['mo_custom_phone_blocked_message']) ? $result['mo_custom_phone_blocked_message'] : '';
            if (empty($custom_blocked_phone_message) || $custom_blocked_phone_message == ''){
                $custom_blocked_phone_message = 'You are not allowed to register. Your country may be blocked. Please contact your administrator.';
            }
            self::_redirect_url($custom_blocked_phone_message);
        } else if ($is_email) {
            $custom_blocked_email_message = isset($result['mo_custom_email_blocked_message']) ? $result['mo_custom_email_blocked_message'] : '';
            if (empty($custom_blocked_email_message) || $custom_blocked_email_message == 'You are not allowed to register. Your Domain may be blocked. Please contact your administrator.'){
                $custom_blocked_email_message = 'You are not allowed to register. Your domain may be blocked. Please contact your administrator.';
            }
            self::_redirect_url($custom_blocked_email_message);
        }

    }

    function _redirect_url($message)
    {
        $app = JFactory::getApplication();
        $app->enqueueMessage($message, 'error');
        $app->redirect(JRoute::_('index.php'));
    }
}