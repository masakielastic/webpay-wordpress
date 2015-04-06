<?php
/*
Plugin Name: Simple WebPay Checkout
Plugin URI: https://github.com/masakielastic/webpay-wordpress
Description: Add WebPay Checkout forms using shortcodes.
Version: 0.1
Author: Masaki Kagaya
Author URI:  https://github.com/masakielastic
License: GPLv2 or later
Text Domain: webpay-checkout
Domain Path: /languages
*/

require_once __DIR__.'/vendor/autoload.php';
require_once( dirname(__FILE__) . '/webpay-checkout-settings.php' );
require_once( dirname(__FILE__) . '/webpay-checkout.php' );


if ( is_admin() ) {
    register_deactivation_hook( __FILE__, 'webpay_checkout_deactivate' );
    add_action( 'admin_menu', 'webpay_checkout_admin_menu' );
    add_action( 'admin_init', 'webpay_checkout_admin_init' );
}

add_action( 'wp_ajax_webpay_checkout', 'webpay_ajax_response' );
add_action( 'wp_ajax_nopriv_webpay_checkout', 'webpay_ajax_response' );
add_shortcode( 'webpay', 'webpay_checkout_shortcode' );