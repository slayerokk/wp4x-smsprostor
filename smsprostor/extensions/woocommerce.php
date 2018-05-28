<?php

if (!defined('ABSPATH')) {exit;}

include_once ('gateway.php');

function smsprostor_woocommerce_order_complete($order_id, $old_status, $new_status){
    if (($old_status == 'pending') && ($new_status == 'processing')) {
        $smsprostor_login = get_option('smsprostor_login');
        $smsprostor_password = get_option('smsprostor_password');
        $smsprostor_phone = get_option('smsprostor_phone');
        $smsprostor_sender = get_option('smsprostor_sender');
        $smsprostor_wc_send_customer = get_option('smsprostor_wc_send_customer');
        $smsprostor_wc_customer_message = get_option('smsprostor_wc_customer_message');
        $smsprostor_wc_send_admin = get_option('smsprostor_wc_send_admin');
        $smsprostor_wc_admin_message = get_option('smsprostor_wc_admin_message');

        $order = new WC_Order($order_id);

        $order_data = $order->get_data();

        $original = array(
            "@id",
            "@total",
            "@firstname",
            "@lastname",
            "@phone",
            "@email"
        );
        $replace = array(
            $order_data['id'],
            $order_data['total'],
            $order_data['billing']['first_name'],
            $order_data['billing']['last_name'],
            $order_data['billing']['phone'],
            $order_data['billing']['email']
        );
        $customer_message = str_replace($original, $replace, $smsprostor_wc_customer_message);
        $admin_message = str_replace($original, $replace, $smsprostor_wc_admin_message);
        $sms = new gateway();
        if((!empty($smsprostor_login))
            && (!empty($smsprostor_password))
        ) {
            if ($smsprostor_wc_send_admin == 'on') {
                $sms->sms_send($smsprostor_login, $smsprostor_password, $smsprostor_phone, $admin_message, $smsprostor_sender);
            }
            if ($smsprostor_wc_send_customer == 'on') {
                $sms->sms_send($smsprostor_login, $smsprostor_password, $order_data['billing']['phone'], $customer_message, $smsprostor_sender);
            }
        }
    }
}

add_action( 'woocommerce_order_status_changed', 'smsprostor_woocommerce_order_complete', 99, 3 );

