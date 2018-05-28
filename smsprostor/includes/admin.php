<?php

if ( ! defined( 'ABSPATH' ) ) { exit; }

function prostorsms_options_page() {

    add_options_page(
        'Простор СМС',
        'Простор СМС',
        'manage_options',
        'prostorsms',
        'prostorsms_options_page_ui'
    );

}

add_action( 'admin_menu', 'prostorsms_options_page' );

function prostorsms_options_page_ui() {

    include 'admin-ui.php';

}