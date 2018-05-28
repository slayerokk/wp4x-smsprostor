<?php
/*
Plugin Name: ProstorSMS integration gateway
Plugin URI:  https://wordpress.org/plugins/smsprostor/
Description: Отправка смс-сообщений Вашим клиентам
Version:     1.0.0
Author:      SMS Prostor
Author URI:  https://prostor-sms.ru
Text Domain: smsprostor
License:      GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
*/

if ( ! defined( 'ABSPATH' ) ) { exit; }

include_once ( plugin_dir_path( __FILE__ ) . 'includes/admin.php' );

include_once ( plugin_dir_path( __FILE__ ) . 'extensions/gateway.php' );
include_once ( plugin_dir_path( __FILE__ ) . 'extensions/cf7.php' );
include_once ( plugin_dir_path( __FILE__ ) . 'extensions/woocommerce.php' );