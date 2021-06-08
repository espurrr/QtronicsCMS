CREATE TABLE IF NOT EXISTS `#__miniorange_otp_customer` (
`id` int(11) UNSIGNED NOT NULL ,
`email` VARCHAR(255)  NOT NULL ,
`password` VARCHAR(255)  NOT NULL ,
`admin_phone` VARCHAR(255)  NOT NULL ,
`customer_key` VARCHAR(255)  NOT NULL ,
`customer_token` VARCHAR(255) NOT NULL,
`api_key` VARCHAR(255)  NOT NULL,
`login_status` tinyint(1) DEFAULT FALSE,
`registration_status` VARCHAR(255) NOT NULL,
`transaction_id` VARCHAR(255) NOT NULL,
`mo_otp_allowed_email_domains` VARCHAR(255) NOT NULL,
`reg_restriction` VARCHAR(255) NOT NULL,
--`log_restriction` VARCHAR(255) NOT NULL,
`white_or_black` VARCHAR(255) NOT NULL,
`email_count` int(11),
`sms_count` int(11),
`registration_otp_type` int(1),
`login_otp_type` int(1),
`enable_during_registration` int(1),
--`enable_during_login` int(1),
--`otp_bypass` int(1),
`uninstall_feedback` int(1) NOT NULL,
`mo_default_country_code` int(5) NOT NULL,
`mo_default_country` VARCHAR(255) NOT NULL,
--`redirect_after_login` VARCHAR(255) NOT NULL,
--`redirect_after_logout` VARCHAR(255) NOT NULL,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__miniorange_otp_custom_message` (
`id` int(11) UNSIGNED NOT NULL,
`mo_custom_email_success_message` VARCHAR(1048) NOT NULL,
`mo_custom_email_error_message` VARCHAR(1048) NOT NULL,
`mo_custom_email_invalid_format_message` VARCHAR(1048) NOT NULL,
`mo_custom_email_blocked_message` VARCHAR(1048) NOT NULL,
`mo_custom_phone_success_message` VARCHAR(1048) NOT NULL,
`mo_custom_phone_error_message` VARCHAR(1048) NOT NULL,
`mo_custom_phone_invalid_format_message` VARCHAR(1048) NOT NULL,
`mo_custom_phone_blocked_message` VARCHAR(1048) NOT NULL,
`mo_custom_invalid_otp_message` VARCHAR(1048) NOT NULL,
`mo_block_country_code` VARCHAR (1048) NOT NULL,

PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

INSERT IGNORE INTO `#__miniorange_otp_customer`(`id`,`login_status`) values (1,false) ;
INSERT IGNORE INTO `#__miniorange_otp_custom_message`(`id`) values (1);
