<?php
add_action( 'wp_ajax_webpay_checkout', 'webpay_ajax_response' );
add_action( 'wp_ajax_nopriv_webpay_checkout', 'webpay_ajax_response' );
add_shortcode( 'webpay', 'webpay_checkout_shortcode' );

function webpay_ajax_response() {
  $settings = webpay_checkout_get_settings();
  check_ajax_referer( $settings['nonce'], 'security' );

  $key = webpay_get_private_key();

  $data = array(
    'amount' => $_POST['amount'],
    'currency' => webpay_get_currency(),
    'card' => $_POST['token']
  );

  $res = webpay_charges( $key, $data );

  wp_send_json( $res );
}

function webpay_checkout_shortcode($atts) {

  $settings = webpay_checkout_get_settings();
  $slug = $settings['slug'];
  load_plugin_textdomain( $slug, false,
    dirname(plugin_basename( __FILE__ )). '/languages/'
  );

  $a = shortcode_atts(array(
    'amount' => 0,
    'label' => __( 'purchase', $slug ),
    'any' => 'false'
  ), $atts);

  $amount = $a['amount'];
  $label = esc_attr($a['label']);
  $any = esc_attr($a['any']);

  $json_options = 0;
  if (version_compare( PHP_VERSION, '5.3.0' ) >= 0) {
    $json_options |= JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_TAG;
  }

  $url = json_encode( admin_url( 'admin-ajax.php' ), $json_options );

  $data = json_encode( array(
    'security' => wp_create_nonce( $settings['nonce'] ),
    'action' => $settings['action'],
    'amount' => $amount
  ), $json_options );

  $placeholder = esc_attr(__( 'Input amount', $slug ));

  $msg = json_encode( array(
    'no_input' => __( 'Input card number', $slug ),
    'no_amount' => $placeholder,
    'success' => __( 'Thank you', $slug ),
    'fail' => __( 'Failed', $slug )
  ), $json_options );

  $locale = get_locale() === 'ja' ? 'ja' : 'en';
  $public_key = webpay_get_public_key();

  include 'webpay-checkout-view.php';
}

function webpay_charges($key, $data) {
  return webpay_post( 'https://api.webpay.jp/v1/charges', $key, $data );
}

function webpay_post( $url, $key, $data ) {

  $res = wp_remote_post( $url, array(
    'headers' => array( 'Authorization' => 'Basic '.base64_encode( $key.':' ) ),
    'body' => $data
  ));

  $code = wp_remote_retrieve_response_code( $res );
  $body = wp_remote_retrieve_body( $res );
  $body = json_decode( $body, true );

  return array_merge( array( 'code' => $code ), $body );
}
