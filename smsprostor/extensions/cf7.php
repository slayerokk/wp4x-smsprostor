<?php

include_once ('gateway.php');

function prostorsms_cf7_mail_sent(){
    $submission = WPCF7_Submission::get_instance()->get_posted_data();
    $smsprostor_login = get_option('smsprostor_login');
    $smsprostor_password = get_option('smsprostor_password');
    $smsprostor_phone = get_option('smsprostor_phone');
    $smsprostor_sender = get_option('smsprostor_sender');
    $smsprostor_cf7_customer_field = get_option('smsprostor_cf7_customer_field');
    $smsprostor_cf7_send_customer = get_option('smsprostor_cf7_send_customer');
    $smsprostor_cf7_customer_message = get_option('smsprostor_cf7_customer_message');
    $smsprostor_cf7_send_admin = get_option('smsprostor_cf7_send_admin');
    $smsprostor_cf7_admin_message = get_option('smsprostor_cf7_admin_message');
    $sms = new gateway();
    if ((isset($smsprostor_login)) && (isset($smsprostor_password))) {
        if ($smsprostor_cf7_send_admin == 'on') {
            $message = $smsprostor_cf7_admin_message;
            foreach ($submission as $key => $value) {
                $message = str_replace('@'.$key, $value, $message);
            }
            $sms->sms_send($smsprostor_login, $smsprostor_password, $smsprostor_phone, $message, $smsprostor_sender);
        }
        if ($smsprostor_cf7_send_customer == 'on') {
            $message = $smsprostor_cf7_customer_message;
            foreach ($submission as $key => $value) {
                $message = str_replace('@'.$key, $value, $message);
            }
            $sms->sms_send($smsprostor_login, $smsprostor_password, $submission[$smsprostor_cf7_customer_field], $message, $smsprostor_sender);
        }
    }
}

add_action('wpcf7_mail_sent', 'prostorsms_cf7_mail_sent');