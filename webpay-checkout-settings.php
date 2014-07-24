<?php

add_action( 'admin_menu', function() {
	$slug = 'webpay-checkout';
	add_options_page( __( 'Settings Page for Simple WebPay Checkout', $slug ),
		'WebPay Checkout', 'manage_options', $slug, 'webpay_checkout_options_page' );
} );

function webpay_checkout_options_page() {
	$slug = 'webpay-checkout';
	echo '<h2>'.__( 'Settings Page for Simple WebPay Checkout', $slug ).'</h2>';
}