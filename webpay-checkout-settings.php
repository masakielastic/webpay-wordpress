<?php
register_deactivation_hook( __FILE__, function() {
	$settings = webpay_checkout_get_settings();
	$option_name = $settings['option_name'];
    delete_option( $option_name );
} );

add_action( 'admin_init', 'webpay_checkout_init' );
add_action( 'admin_menu', function() {
    $settings = webpay_checkout_get_settings();
    $slug = $settings['option_name'];

	add_options_page( __( 'Settings Page for Simple WebPay Checkout', $slug ),
		'Simple WebPay Checkout', 'manage_options', $slug, 'webpay_checkout_options_page' );
} );

function webpay_checkout_get_settings() {
	return [
		'slug' => 'webpay-checkout',
		'group' => 'webpay-checkout-settings-group',
		'section' => 'webpay-checkout-section',
		'option_name' => 'webpay-checkout-settings'
	];
}

function webpay_checkout_init() {

	$settings = webpay_checkout_get_settings();
	$slug = $settings['slug'];
	$group = $settings['group'];
	$section = $settings['section'];
    $option_name = $settings['option_name'];
	$fields = [ 'test-mode', 'currency', 'test-public-key', 'test-private-key', 'public-key', 'private-key' ];

	register_setting( $group, $option_name, 'webpay_validate' );
	add_settings_section( $section, __( 'Settings for Users', $slug ), 'webpay_checkout_section', $slug );

	add_settings_field( $fields[0], __( 'Test Environment', $slug ), 
		'webpay_checkout_test_mode', $slug, $section, [ 'field_name' => $fields[0] ]
		);
    add_settings_field( $fields[1], __( 'Currency', $slug ),
    	'webpay_checkout_currency', $slug, $section, [ 'field_name' => $fields[1] ]);

    add_settings_field( $fields[2], 
		__( 'Public Key For Test Environment', $slug ),
		'webpay_checkout_test_public_key', $slug, $section, [ 'field_name' => $fields[2] ]
	);
    add_settings_field( $fields[3], __( 'Private Key For Test Environment', $slug ),
    	'webpay_checkout_test_private_key', $slug, $section, [ 'field_name' => $fields[3] ]);

    add_settings_field( $fields[4], 
        __( 'Public Key For Production Environment', $slug ),
        'webpay_checkout_public_key', $slug, $section, [ 'field_name' => $fields[4] ]
    );
    add_settings_field( $fields[5], __( 'Private Key For Production Environment', $slug ),
        'webpay_checkout_private_key', $slug, $section, [ 'field_name' => $fields[5] ]);

    settings_fields( $group );
}

function webpay_checkout_options_page() {
	$settings = webpay_checkout_get_settings();
	$slug = $settings['slug'];
	$group = $settings['group'];

	echo '<h2>'.__( 'Settings Page for Simple WebPay Checkout', $slug ).'</h2>';
    echo '<form action="options.php" method="POST">';
    do_settings_sections( $slug );
    settings_fields( $group );
    submit_button();
    echo '</form>';
}

function webpay_validate( $input ) {

    $settings = webpay_checkout_get_settings();
    $slug = $settings['slug'];

    foreach ($input as $key => $value) {

        if (strlen( $value ) >= 100 ) {

            add_settings_error(
                'webpay-settings['.$key.']',
                'webpay-settings-texterror',
                __( 'The length of string cannot be greater than 100', $slug ),
                'error'
            );

            $input[$key] = '';

        }

    }

    return $input;
}

function webpay_checkout_section() {
	$settings = webpay_checkout_get_settings();
	$slug = $settings['slug'];
    $url = 'https://webpay.jp/settings';

    echo __( 'See <a href="'.$url.'" target="_blank">'.$url.'</a> for the details.', $slug );
}

function webpay_checkout_test_mode($args) {

	$settings = webpay_checkout_get_settings();
    $option_name = $settings['option_name'];
    $key = $args['field_name'];

    $options = get_option( $option_name );
    $value = empty( $options[$key] ) ? '' : esc_attr( $options[$key] );

    echo '<select name="'.$option_name.'['.$key.']">'
    .($value === 'off' ? 
        '<option value="on" >on</option><option value="off" selected="selected">off</option>' :
        '<option value="on" selected="selected">on</option><option value="off">off</option>')
    .'</select>';
}

function webpay_checkout_currency($args) {
	$settings = webpay_checkout_get_settings();
    $option_name = $settings['option_name'];
    $key = $args['field_name'];

    $options = get_option( $option_name );
    $value = empty( $options[$key] ) ? '' : esc_attr( $options[$key] );

    echo '<select name="'.$option_name.'['.$key.']">'
    .'<option value="jpy" selected="selected">jpy</option>'
    .'</select>';
}

function webpay_checkout_test_public_key($args) {
	$settings = webpay_checkout_get_settings();
    $option_name = $settings['option_name'];
    $key = $args['field_name'];

	$options = get_option( $option_name );
	$value = empty( $options[$key] ) ? '' : esc_attr( $options[$key] );

	echo '<input type="text" name="'.$option_name.'['.$key.']" value="'.$value.'" />';
}

function webpay_checkout_test_private_key($args) {
	$settings = webpay_checkout_get_settings();
    $option_name = $settings['option_name'];
    $key = $args['field_name'];

    $options = get_option( $option_name );
    $value = empty( $options[$key] ) ? '' : esc_attr( $options[$key] );

    echo '<input type="text" name="'.$option_name.'['.$key.']" value="'.$value.'" />';
}

function webpay_checkout_public_key($args) {
    $settings = webpay_checkout_get_settings();
    $option_name = $settings['option_name'];
    $key = $args['field_name'];

    $options = get_option( $option_name );
    $value = empty( $options[$key] ) ? '' : esc_attr( $options[$key] );

    echo '<input type="text" name="'.$option_name.'['.$key.']" value="'.$value.'" />';
}

function webpay_checkout_private_key($args) {
    $settings = webpay_checkout_get_settings();
    $option_name = $settings['option_name'];
    $key = $args['field_name'];

    $options = get_option( $option_name );
    $value = empty( $options[$key] ) ? '' : esc_attr( $options[$key] );

    echo '<input type="text" name="'.$option_name.'['.$key.']" value="'.$value.'" />';
}