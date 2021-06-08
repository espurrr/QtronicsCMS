<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_joomlaotp
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
/**
 * AccountSetup Controller
 *
 * @package     Joomla.Administrator
 * @subpackage  com_joomlaotp
 * @since       0.0.9
 */
defined('_JEXEC') or die('Restricted access');

class JoomlaOtpControllerAccountSetup extends JControllerForm
{
    function __construct()
    {
        $this->view_list = 'accountsetup';
        parent::__construct();
    }

    function saveOTP()
    {

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $tab = JFactory::getApplication()->input->post->getArray()['login_otp_type'];
        $tab2 = JFactory::getApplication()->input->post->getArray()['otp_during_registration'];

        if (!isset($tab2)) $tab = 0;

        if (!isset($tab3)) $tab1 = 0;

        $tab_va = isset($tab) ? $tab : 0;
        $tab_val = isset($tab2) ? $tab2 : 0;

        $db_table = '#__miniorange_otp_customer';
        $db_coloums = array(
            'registration_otp_type' => $tab_va,
            'enable_during_registration' => $tab_val,
        );

        MoOtpUtility::__genDBUpdate($db_table, $db_coloums);
        $this->setRedirect('index.php?option=com_joomlaotp&view=accountsetup&tab-panel=setting', 'Your configuration has been saved successfully.');
    }

    function saveDomainBlocks()
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $post = JFactory::getApplication()->input->post->getArray();
        $allow_domains = isset($post['mo_otp_allowed_email_domains']) ? $post['mo_otp_allowed_email_domains'] : 0;
        $white_or_black = isset($post['white_or_black']) ? $post['white_or_black'] : 0;
        $reg_restriction = isset($post['reg_restriction']) ? $post['reg_restriction'] : 0;
        $allow_domains = preg_replace('!\s+!', ';', $allow_domains);

        $db_table = '#__miniorange_otp_customer';
        if ($reg_restriction == 1) {
            $db_coloums = array(
                'reg_restriction' => $reg_restriction,
                'white_or_black' => $white_or_black,
                'mo_otp_allowed_email_domains' => $allow_domains,
            );
        } else {
            $db_coloums = array(
                'reg_restriction' => 0,
                'white_or_black' => 0,
                'mo_otp_allowed_email_domains' => '',
            );
        }
        MoOtpUtility::__genDBUpdate($db_table, $db_coloums);
        $this->setRedirect('index.php?option=com_joomlaotp&view=accountsetup&tab-panel=setting', 'Your configuration has been saved successfully.');
    }

    /*function isRestrictedUserEmail($email_domain){

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from($db->quoteName('#__miniorange_otp_customer'));
        $query->where($db->quoteName('id')." = 1");
        $db->setQuery($query);
        $result = $db->loadAssoc();

        $domain=explode('@', $email_domain)[1];
        if(is_null($domain) || empty($domain)){
            return FALSE;
        }

        $blocked_domains =  isset ($result['mo_otp_blocked_email_domains']) ? $result['mo_otp_blocked_email_domains'] : 0;
        $blocked_domains=explode(';',$blocked_domains);
        if(array_search($domain, $blocked_domains)===FALSE){
            return FALSE;
        }
        else return TRUE;
    }*/

    function customerLoginForm()
    {
        $db_table = '#__miniorange_otp_customer';

        $db_coloums = array(
            'login_status' => true,
            'password' => '',
            'email_count' => '',
            'sms_count' => '',
        );

        MoOtpUtility::__genDBUpdate($db_table, $db_coloums);
        $this->setRedirect('index.php?option=com_joomlaotp&view=accountsetup');
    }

    function verifyCustomer()
    {
        $post = JFactory::getApplication()->input->post->getArray();

        $email = '';
        $password = '';

        if (MoOtpUtility::check_empty_or_null($post['email']) || MoOtpUtility::check_empty_or_null($post['password'])) {
            JFactory::getApplication()->enqueueMessage(4711, 'All the fields are required. Please enter valid entries.');
            return;
        } else {
            $email = $post['email'];
            $password = $post['password'];
        }

        $customer = new MoOtpCustomer();
        $content = $customer->get_customer_key($email, $password);

        $customerKey = json_decode($content, true);
        if (strcasecmp($customerKey['apiKey'], 'CURL_ERROR') == 0) {
            $this->setRedirect('index.php?option=com_joomlaotp&view=accountsetup', $customerKey['token'], 'error');
        } else if (json_last_error() == JSON_ERROR_NONE) {
            if (isset($customerKey['id']) && isset($customerKey['apiKey']) && !empty($customerKey['id']) && !empty($customerKey['apiKey'])) {
                $this->save_customer_configurations($email, $customerKey['id'], $customerKey['apiKey'], $customerKey['token'], $customerKey['phone']);
                $this->setRedirect('index.php?option=com_joomlaotp&view=accountsetup', 'Your account has been retrieved successfully.');
            } else {
                $this->setRedirect('index.php?option=com_joomlaotp&view=accountsetup', 'There was an error in fetching your details. Please try again.', 'error');
            }
        } else {
            $this->setRedirect('index.php?option=com_joomlaotp&view=accountsetup', 'Invalid username or password. Please try again.', 'error');
        }
    }

    function saveCustomMessage()
    {
        $post = JFactory::getApplication()->input->post->getArray();
        MoOtpUtility::_save_custom_message($post);
        $this->setRedirect('index.php?option=com_joomlaotp&tab-panel=custom_message', 'Settings saved successfully.');
    }

    function saveCustomPhoneMessage()
    {
        $post = JFactory::getApplication()->input->post->getArray();
        MoOtpUtility::_save_custom_phone_message($post);
        $this->setRedirect('index.php?option=com_joomlaotp&tab-panel=custom_message', 'Settings saved successfully.');
    }

    function saveComOTPMessages()
    {
        $post = JFactory::getApplication()->input->post->getArray();
        MoOtpUtility::_save_com_message($post);
        $this->setRedirect('index.php?option=com_joomlaotp&tab-panel=custom_message', 'Settings saved successfully.');
    }

    function block_country_codes()
    {
        $post = JFactory::getApplication()->input->post->getArray();
        $check = MoOtpUtility::_is_default_selected($post);

        if ($check) {
            $this->setRedirect('index.php?option=com_joomlaotp&tab-panel=custom_setting', 'You can\'t block default country code. If you want to block it then please remove it from default country code.', 'warning');
            return;
        }

        MoOtpUtility::_block_country_code($post);
        $this->setRedirect('index.php?option=com_joomlaotp&tab-panel=custom_setting', 'Settings saved successfully.');
    }

   /* function login_logout_redirect()
    {
        $post = JFactory::getApplication()->input->post->getArray();
        $db_table = '#__miniorange_otp_customer';

        $db_coloums = array(
            'redirect_after_login' => $post['mo_oauth_login_redirect_url'],
            'redirect_after_logout' => $post['mo_oauth_logout_redirect_url'],
        );

        MoOtpUtility::__genDBUpdate($db_table, $db_coloums);
        $this->setRedirect('index.php?option=com_joomlaotp&view=accountsetup&tab-panel=custom_setting', 'Settings saved successfully.');
    }*/

    function saveCustomSettings()
    {
        $default_co_values = JFactory::getApplication()->input->post->getArray()['default_country_code'];
        $country_val = explode(',', $default_co_values);
        $default_co_code = isset($country_val[0]) ? $country_val[0] : '';
        $default_co_name = isset($country_val[1]) ? $country_val[1] : '';

        if (!empty($default_co_code) && $default_co_code != '') {
            $is_blocked = MoOtpUtility::_is_country_code_blocked($default_co_code);
        }

        if ($is_blocked) {
            $this->setRedirect('index.php?option=com_joomlaotp&tab-panel=custom_setting', 'This Country code has been blocked already. If you want to add it for default country code then please first unblock it and then try.', 'warning');
            return;
        }

        $db_table = '#__miniorange_otp_customer';

        $db_coloums = array(
            'mo_default_country_code' => $default_co_code,
            'mo_default_country' => $default_co_name,
        );

        MoOtpUtility::__genDBUpdate($db_table, $db_coloums);
        $this->setRedirect('index.php?option=com_joomlaotp&tab-panel=custom_setting', 'Settings saved successfully.');
    }

    function save_customer_configurations($email, $id, $apiKey, $token, $phone)
    {
        $db_table = '#__miniorange_otp_customer';

        $db_coloums = array(
            'email' => $email,
            'customer_key' => $id,
            'api_key' => $apiKey,
            'customer_token' => $token,
            'admin_phone' => $phone,
            'login_status' => false,
            'registration_status' => 'SUCCESS',
            'password' => '',
            'email_count' => '',
            'sms_count' => '',
        );

        MoOtpUtility::__genDBUpdate($db_table, $db_coloums);
    }

    function registerCustomer()
    {
        //validate and sanitize

        $email = '';
        $phone = '';
        $password = '';
        $confirmPassword = '';

        $password = (JFactory::getApplication()->input->post->getArray()["password"]);
        $confirmPassword = (JFactory::getApplication()->input->post->getArray()["confirmPassword"]);

        $email = (JFactory::getApplication()->input->post->getArray()["email"]);
        /*if(isRestrictedUserEmail($email)===TRUE)
            {$this->setRedirect('index.php?option=com_joomlaotp&view=accountsetup','Please Contact your Administrator your Domain may be blocked by your administrator','error');exit;}*/

        if (MoOtpUtility::check_empty_or_null($email) || MoOtpUtility::check_empty_or_null($password) || MoOtpUtility::check_empty_or_null($confirmPassword)) {
            $this->setRedirect('index.php?option=com_joomlaotp&view=accountsetup', 'All the fields are required. Please enter valid entries.', 'error');
            return;
        } else if (strlen($password) < 6 || strlen($confirmPassword) < 6) {    //check password is of minimum length 6
            $this->setRedirect('index.php?option=com_joomlaotp&view=accountsetup', 'Choose a password with minimum length 6.', 'error');
            return;
        } else {
            $email = JFactory::getApplication()->input->post->getArray()["email"];
            $email = strtolower($email);
            $phone = JFactory::getApplication()->input->post->getArray()["phone"];
            $password = JFactory::getApplication()->input->post->getArray()["password"];
            $confirmPassword = JFactory::getApplication()->input->post->getArray()["confirmPassword"];
        }

        if (strcmp($password, $confirmPassword) == 0) {

            $db_table = '#__miniorange_otp_customer';

            $db_coloums = array(
                'email' => $email,
                'admin_phone' => $phone,
                'password' => $password,
            );

            MoOtpUtility::__genDBUpdate($db_table, $db_coloums);

            $customer = new MoOtpCustomer();
            $content = json_decode($customer->check_customer($email), true);
            if (strcasecmp($content['status'], 'CUSTOMER_NOT_FOUND') == 0) {
                $auth_type = 'EMAIL';
                $content = json_decode($customer->send_otp_token($auth_type, $email), true);
                if (strcasecmp($content['status'], 'SUCCESS') == 0) {

                    $db_table = '#__miniorange_otp_customer';

                    $db_coloums = array(
                        'email_count' => 1,
                        'transaction_id' => $content['txId'],
                        'login_status' => false,
                        'registration_status' => 'MO_OTP_DELIVERED_SUCCESS',
                    );

                    MoOtpUtility::__genDBUpdate($db_table, $db_coloums);
                    $this->setRedirect('index.php?option=com_joomlaotp&view=accountsetup', 'A One Time Passcode has been sent <b>( 1 )</b> to <b>' . $email . '</b>. Please enter the OTP below to verify your email. ');
                } else {
                    $db_table = '#__miniorange_otp_customer';

                    $db_coloums = array(
                        'login_status' => false,
                        'registration_status' => 'MO_OTP_DELIVERED_FAILURE',
                    );

                    MoOtpUtility::__genDBUpdate($db_table, $db_coloums);
                    $this->setRedirect('index.php?option=com_joomlaotp&view=accountsetup', 'There was an error in sending email. Please click on Resend OTP to try again. ', 'error');
                }
            } else if (strcasecmp($content['status'], 'CURL_ERROR') == 0) {

                $db_table = '#__miniorange_otp_customer';

                $db_coloums = array(
                    'login_status' => false,
                    'registration_status' => 'MO_OTP_DELIVERED_FAILURE',
                );

                MoOtpUtility::__genDBUpdate($db_table, $db_coloums);
                $this->setRedirect('index.php?option=com_joomlaotp&view=accountsetup', $content['statusMessage'], 'error');

            } else {
                $content = $customer->get_customer_key($email, $password);
                $customerKey = json_decode($content, true);
                if (json_last_error() == JSON_ERROR_NONE) {
                    $this->save_customer_configurations($email, $customerKey['id'], $customerKey['apiKey'], $customerKey['token'], $customerKey['phone']);
                    $this->setRedirect('index.php?option=com_joomlaotp&view=accountsetup', 'Your account has been retrieved successfully.');
                } else {
                    $db_table = '#__miniorange_otp_customer';

                    $db_coloums = array(
                        'login_status' => true,
                        'registration_status' => '',
                    );

                    MoOtpUtility::__genDBUpdate($db_table, $db_coloums);
                    $this->setRedirect('index.php?option=com_joomlaotp&view=accountsetup', 'You already have an account with miniOrange. Please enter a valid password. ', 'error');
                }
            }

        } else {
            $db_table = '#__miniorange_otp_customer';
            $db_coloums = array(
                'login_status' => false,
            );
            MoOtpUtility::__genDBUpdate($db_table, $db_coloums);
            $this->setRedirect('index.php?option=com_joomlaotp&view=accountsetup', 'Password and Confirm password do not match.', 'error');
        }
    }

    function validateOtp()
    {

        $otp_token = trim(JFactory::getApplication()->input->post->getArray()["otp_token"]);
        //validation and sanitization
        //$otp_token = '';
        if (MoOtpUtility::check_empty_or_null($otp_token)) {
            $this->setRedirect('index.php?option=com_joomlaotp&view=accountsetup', 'Please enter a valid OTP.', 'error');
            return;
        } else {
            $otp_token = trim(JFactory::getApplication()->input->post->getArray()['otp_token']);
        }

        $transaction_id = MoOtpUtility::__getDBLoadResult('transaction_id', '#__miniorange_otp_customer');


        $customer = new MoOtpCustomer();
        $content = json_decode($customer->validate_otp_token($transaction_id, $otp_token), true);
        if (strcasecmp($content['status'], 'SUCCESS') == 0) {
            $customerKey = json_decode($customer->create_customer(), true);

            $db_table = '#__miniorange_otp_customer';

            $db_coloums = array(
                'email_count' => '',
                'sms_count' => '',
            );

            MoOtpUtility::__genDBUpdate($db_table, $db_coloums);

            if (strcasecmp($customerKey['status'], 'CUSTOMER_USERNAME_ALREADY_EXISTS') == 0) {    //admin already exists in miniOrange
                $content = $customer->get_customer_key();
                $customerKey = json_decode($content, true);
                if (json_last_error() == JSON_ERROR_NONE) {
                    $this->save_customer_configurations($customerKey['email'], $customerKey['id'], $customerKey['apiKey'], $customerKey['token'], $customerKey['phone']);
                    $this->setRedirect('index.php?option=com_joomlaotp&view=accountsetup', 'Your account has been retrieved successfully.');
                } else {
                    $db_table = '#__miniorange_otp_customer';

                    $db_coloums = array(
                        'login_status' => true,
                        'password' => '',
                    );

                    MoOtpUtility::__genDBUpdate($db_table, $db_coloums);
                    $this->setRedirect('index.php?option=com_joomlaotp&view=accountsetup', 'You already have an account with miniOrange. Please enter a valid password.', 'error');
                }
            } else if (strcasecmp($customerKey['status'], 'SUCCESS') == 0) {

                //registration successful
                $this->save_customer_configurations($customerKey['email'], $customerKey['id'], $customerKey['apiKey'], $customerKey['token'], $customerKey['phone']);
                $this->setRedirect('index.php?option=com_joomlaotp&view=accountsetup', 'Your account has been created successfully.');
            } else if (strcmp($customerKey['message'], 'Email is not enterprise email.') || strcmp($customerKey['status'], 'INVALID_EMAIL_QUICK_EMAIL') == 0) {

                $db_table = '#__miniorange_otp_customer';

                $db_coloums = array(
                    'registration_status' => '',
                    'email' => '',
                    'password' => '',
                    'transaction_id' => '',
                );

                MoOtpUtility::__genDBUpdate($db_table, $db_coloums);
                $this->setRedirect('index.php?option=com_joomlaotp&view=accountsetup', 'There was an error creating an account for you. You may have entered an invalid Email-Id. <br><b>(We discourage the use of disposable emails)</b><br>
												Please try again with a valid email.', 'error');

            }
        } else if (strcasecmp($content['status'], 'CURL_ERROR') == 0) {

            $db_table = '#__miniorange_otp_customer';
            $db_coloums = array(
                'registration_status' => 'MO_OTP_VALIDATION_FAILURE',
            );

            MoOtpUtility::__genDBUpdate($db_table, $db_coloums);
            $this->setRedirect('index.php?option=com_joomlaotp&view=accountsetup', $content['statusMessage'], 'error');

        } else {

            $db_table = '#__miniorange_otp_customer';
            $db_coloums = array(
                'registration_status' => 'MO_OTP_VALIDATION_FAILURE',
            );
            MoOtpUtility::__genDBUpdate($db_table, $db_coloums);
            $this->setRedirect('index.php?option=com_joomlaotp&view=accountsetup', 'You have entered an invalid OTP. Please enter a valid OTP.', 'error');
        }
    }

    function resendOtp()
    {
        $customer = new MoOtpCustomer();
        $auth_type = 'EMAIL';
        $email = MoOtpUtility::__getDBLoadResult('email', '#__miniorange_otp_customer');

        $content = json_decode($customer->send_otp_token($auth_type, $email), true);
        if (strcasecmp($content['status'], 'SUCCESS') == 0) {

            $customer_details = MoOtpUtility::getCustomerDetails();
            $email_count = $customer_details['email_count'];
            $admin_email = $customer_details['email'];

            if ($email_count != '' && $email_count >= 1) {
                $email_count = $email_count + 1;

                $db_table = '#__miniorange_otp_customer';

                $db_coloums = array(
                    'email_count' => $email_count,
                    'transaction_id' => $content['txId'],
                    'registration_status' => 'MO_OTP_DELIVERED_SUCCESS',
                );

                MoOtpUtility::__genDBUpdate($db_table, $db_coloums);
                $this->setRedirect('index.php?option=com_joomlaotp&view=accountsetup', 'Another One Time Passcode has been sent <b>( ' . $email_count . ' )</b> to <b>' . ($admin_email) . '</b>. Please enter the OTP below to verify your email.');

            } else {
                $db_table = '#__miniorange_otp_customer';
                $db_coloums = array(
                    'email_count' => 1,
                    'transaction_id' => $content['txId'],
                    'registration_status' => 'MO_OTP_DELIVERED_SUCCESS',
                );
                MoOtpUtility::__genDBUpdate($db_table, $db_coloums);
                $this->setRedirect('index.php?option=com_joomlaotp&view=accountsetup', 'An OTP has been sent to <b>' . ($admin_email) . '</b>. Please enter the OTP below to verify your email.');
            }

        } else if (strcasecmp($content['status'], 'CURL_ERROR') == 0) {
            $db_table = '#__miniorange_otp_customer';
            $db_coloums = array(
                'registration_status' => 'MO_OTP_DELIVERED_FAILURE',
            );
            MoOtpUtility::__genDBUpdate($db_table, $db_coloums);
            $this->setRedirect('index.php?option=com_joomlaotp&view=accountsetup', $content['statusMessage'], 'error');

        } else {
            $db_table = '#__miniorange_otp_customer';
            $db_coloums = array(
                'registration_status' => 'MO_OTP_DELIVERED_FAILURE',
            );
            MoOtpUtility::__genDBUpdate($db_table, $db_coloums);
            $this->setRedirect('index.php?option=com_joomlaotp&view=accountsetup', 'There was an error in sending email. Please click on Resend OTP to try again.', 'error');
        }
    }

    function cancelform()
    {
        $db_table = '#__miniorange_otp_customer';
        $db_coloums = array(
            'email' => '',
            'password' => '',
            'customer_key' => '',
            'api_key' => '',
            'customer_token' => '',
            'admin_phone' => '',
            'login_status' => false,
            'registration_status' => '',
            'email_count' => '',
            'sms_count' => '',
        );
        MoOtpUtility::__genDBUpdate($db_table, $db_coloums);
        $this->setRedirect('index.php?option=com_joomlaotp&view=accountsetup');
    }

    function phoneVerification()
    {
        $phone = JFactory::getApplication()->input->post->getArray()['phone_number'];
        $phone = str_replace(' ', '', $phone);

        $pattern = "/[\+][0-9]{1,3}[0-9]{10}/";

        if (preg_match($pattern, $phone, $matches, PREG_OFFSET_CAPTURE)) {
            $auth_type = 'SMS';
            $customer = new MoOtpCustomer();
            $send_otp_response = json_decode($customer->send_otp_token($auth_type, $phone));
            if ($send_otp_response->status == 'SUCCESS') {
                $sms_count = MoOtpUtility::__getDBLoadResult('sms_count', '#__miniorange_otp_customer');

                if ($sms_count != '' && $sms_count >= 1) {
                    $sms_count = $sms_count + 1;
                    $db_table = '#__miniorange_otp_customer';
                    $db_coloums = array(
                        'sms_count' => $sms_count,
                        'transaction_id' => $send_otp_response->txId,
                    );
                    MoOtpUtility::__genDBUpdate($db_table, $db_coloums);
                    $this->setRedirect('index.php?option=com_joomlaotp&view=accountsetup', 'Another One Time Passcode has been sent <b>(' . $sms_count . ')</b> for verification to ' . $phone);
                } else {
                    $db_table = '#__miniorange_otp_customer';
                    $db_coloums = array(
                        'sms_count' => 1,
                        'transaction_id' => $send_otp_response->txId,
                    );
                    MoOtpUtility::__genDBUpdate($db_table, $db_coloums);
                    $this->setRedirect('index.php?option=com_joomlaotp&view=accountsetup', 'A One Time Passcode has been sent ( <b>1</b> ) for verification to ' . $phone);
                }

            } else {
                $this->setRedirect('index.php?option=com_joomlaotp&view=accountsetup', 'An error occurred while sending OTP to phone. Please try again.');
            }
        } else {
            $this->setRedirect('index.php?option=com_joomlaotp&view=accountsetup', 'Please enter the phone number in the following format: <b>+##country code## ##phone number##', 'error');
        }
    }

    function forgotPassword()
    {
        $admin_email = JFactory::getApplication()->input->post->getArray()['current_admin_email'];
        if (MoOtpUtility::check_empty_or_null($admin_email)) {
            $this->setRedirect('index.php?option=com_joomlaotp&view=accountsetup', 'Please enter your email registered with miniOrange and then click on Forgot Password link.', 'error');
            return;
        }

        $customer = new MoOtpCustomer();
        $forgot_password_response = json_decode($customer->mo_otp_forgot_password($admin_email));
        if ($forgot_password_response->status == 'SUCCESS') {
            $message = 'You password has been reset successfully. A new password has been sent to your registered mail.';
            $this->setRedirect('index.php?option=com_joomlaotp&view=accountsetup', $message);

        } else {
            $message = 'An error occurred while reseting the password. Please try again.';
            $this->setRedirect('index.php?option=com_joomlaotp&view=accountsetup', $forgot_password_response->message, 'error');
        }
    }

    function contactUs()
    {
        $query_email = JFactory::getApplication()->input->post->getArray()['query_email'];
        $query = JFactory::getApplication()->input->post->getArray()['query'];
        $query = preg_replace('!\s+!', '', $query);
        if (MoOtpUtility::check_empty_or_null($query_email) || MoOtpUtility::check_empty_or_null($query)) {
            $this->setRedirect('index.php?option=com_joomlaotp&view=accountsetup', 'Please submit your query with email.', 'error');
            return;
        } else {
            $query = JFactory::getApplication()->input->post->getArray()['query'];
            $email = JFactory::getApplication()->input->post->getArray()['query_email'];
            $phone = JFactory::getApplication()->input->post->getArray()['query_phone'];
            $contact_us = new MoOtpCustomer();
            $submited = json_decode($contact_us->submit_contact_us($email, $phone, $query), true);
            if (json_last_error() == JSON_ERROR_NONE) {
                if (is_array($submited) && array_key_exists('status', $submited) && $submited['status'] == 'ERROR') {
                    $this->setRedirect('index.php?option=com_joomlaotp&view=accountsetup', $submited['message'], 'error');
                } else {
                    if ($submited == false) {
                        $this->setRedirect('index.php?option=com_joomlaotp&view=accountsetup', 'Your query could not be submitted. Please try again.', 'error');
                    } else {
                        $this->setRedirect('index.php?option=com_joomlaotp&view=accountsetup', 'Thanks for getting in touch! We shall get back to you shortly.');
                    }
                }
            }
        }
    }
}