<?php
register_deactivation_hook( __FILE__, 'webpay_checkout_deactivate' );
add_action( 'admin_init', 'webpay_checkout_init' );
add_action( 'admin_menu', 'webpay_checkout_menu' );

function webpay_checkout_deactivate() {
    $settings = webpay_checkout_get_settings();
    $option_name = $settings['option_name'];
    delete_option( $option_name );
}

function webpay_checkout_get_settings() {
	return array(
        'slug' => 'webpay-checkout',
        'nonce' => 'webpay-checkout-nonce',
		'group' => 'webpay-checkout-settings-group',
		'section' => 'webpay-checkout-section',
		'option' => 'webpay-checkout-settings',
        'action' => 'webpay_checkout'
	);
}

function webpay_checkout_init() {
	$settings = webpay_checkout_get_settings();
	$slug = $settings['slug'];
	$group = $settings['group'];
	$section = $settings['section'];
    $option_name = $settings['option'];
	$fields = array( 'test-mode', 'currency', 'test-public-key', 'test-private-key', 'public-key', 'private-key' );

	register_setting( $group, $option_name, 'webpay_validate' );

	add_settings_section( $section,
        __( 'Settings for Users', $slug ),
        'webpay_checkout_section', $slug
    );

	add_settings_field( $fields[0],
        __( 'Test Environment', $slug ),
        'webpay_checkout_test_mode', $slug, $section, array( 'field_name' => $fields[0] )
	);

    add_settings_field( $fields[1],
        __( 'Currency', $slug ),
        'webpay_checkout_currency', $slug, $section, array( 'field_name' => $fields[1] )
    );

    add_settings_field( $fields[2],
		__( 'Public Key For Test Environment', $slug ),
        'webpay_checkout_test_public_key', $slug, $section, array( 'field_name' => $fields[2] )
	);
    add_settings_field( $fields[3],
        __( 'Private Key For Test Environment', $slug ),
        'webpay_checkout_test_private_key', $slug, $section, array( 'field_name' => $fields[3] )
    );

    add_settings_field( $fields[4], 
        __( 'Public Key For Production Environment', $slug ),
        'webpay_checkout_public_key', $slug, $section, array( 'field_name' => $fields[4] )
    );
    add_settings_field( $fields[5],
        __( 'Private Key For Production Environment', $slug ),
        'webpay_checkout_private_key', $slug, $section, array( 'field_name' => $fields[5] )
    );
}

function webpay_checkout_menu() {
    $settings = webpay_checkout_get_settings();
    $slug = $settings['slug'];

    load_plugin_textdomain( $slug, false,
        dirname(plugin_basename( __FILE__ )). '/languages/'
    );

    add_options_page( __( 'Settings Page for Simple WebPay Checkout', $slug ),
        'Simple WebPay Checkout', 'manage_options', $slug, 'webpay_checkout_options_page'
    );
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

    printf( __( 'See <a href="%s" target="_blank">%s</a> for the details.', $slug ), $url, $url );
}

function webpay_checkout_test_mode($args) {

	$settings = webpay_checkout_get_settings();
    $option_name = $settings['option'];
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
    $option_name = $settings['option'];
    $key = $args['field_name'];

    $options = get_option( $option_name );
    $value = empty( $options[$key] ) ? '' : esc_attr( $options[$key] );

    echo '<select name="'.$option_name.'['.$key.']">'
    .'<option value="jpy" selected="selected">jpy</option>'
    .'</select>';
}

function webpay_checkout_test_public_key($args) {
	$settings = webpay_checkout_get_settings();
    $option_name = $settings['option'];
    $key = $args['field_name'];

	$options = get_option( $option_name );
	$value = empty( $options[$key] ) ? '' : esc_attr( $options[$key] );

	echo '<input type="text" name="'.$option_name.'['.$key.']" value="'.$value.'" />';
}

function webpay_checkout_test_private_key($args) {
	$settings = webpay_checkout_get_settings();
    $option_name = $settings['option'];
    $key = $args['field_name'];

    $options = get_option( $option_name );
    $value = empty( $options[$key] ) ? '' : esc_attr( $options[$key] );

    echo '<input type="text" name="'.$option_name.'['.$key.']" value="'.$value.'" />';
}

function webpay_checkout_public_key($args) {
    $settings = webpay_checkout_get_settings();
    $option_name = $settings['option'];
    $key = $args['field_name'];

    $options = get_option( $option_name );
    $value = empty( $options[$key] ) ? '' : esc_attr( $options[$key] );

    echo '<input type="text" name="'.$option_name.'['.$key.']" value="'.$value.'" />';
}

function webpay_checkout_private_key($args) {
    $settings = webpay_checkout_get_settings();
    $option_name = $settings['option'];
    $key = $args['field_name'];

    $options = get_option( $option_name );
    $value = empty( $options[$key] ) ? '' : esc_attr( $options[$key] );

    echo '<input type="text" name="'.$option_name.'['.$key.']" value="'.$value.'" />';
}


function webpay_get_public_key() {
    $settings = webpay_checkout_get_settings();
    $option_name = $settings['option'];
    $options = get_option( $option_name );

    if ( isset( $options['test-mode'] ) && $options['test-mode'] === 'on' ) {
        return isset( $options['test-public-key'] ) ? $options['test-public-key'] : '';
    }

    return isset( $options['public-key'] ) ? $options['public-key'] : '';
}

function webpay_get_private_key() {
    $settings = webpay_checkout_get_settings();
    $option_name = $settings['option'];
    $options = get_option( $option_name );

    if ( isset( $options['test-mode'] ) && $options['test-mode'] === 'on' ) {
        return isset($options['test-private-key']) ? $options['test-private-key'] : '';
    }

    return isset($options['private-key']) ? $options['private-key'] : '';
}

function webpay_get_currency() {
    $settings = webpay_checkout_get_settings();
    $option_name = $settings['option'];
    $options = get_option( $option_name );

    return isset( $options['currency'] ) ? $options['currency'] : '';
}