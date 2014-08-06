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

    header( 'Content-Type: application/json' );
    wp_send_json( $res );
}

function webpay_checkout_shortcode($atts) {

  $a = shortcode_atts(array(
    'amount' => 500
  ), $atts);
  $amount = $a['amount'];

	$settings = webpay_checkout_get_settings();
  $json_options = JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_TAG;
  $url = json_encode( admin_url( 'admin-ajax.php' ), $json_options );

  $json = json_encode( array(
    'security' => wp_create_nonce( $settings['nonce'] ),
    'action' => $settings['action'],
    'amount' => $amount
  ), $json_options );

  $locale = get_locale() === 'ja' ? 'ja' : 'en';
  $public_key = webpay_get_public_key();
  include 'webpay-checkout-view.php';
}

function webpay_charges($key, $data) {
    return webpay_post( 'https://api.webpay.jp/v1/charges', $key, $data );
}

function webpay_post( $url, $key, $data ) {

  $res = wp_remote_post($url, array(
    'headers' => array('Authorization' => 'Basic '.base64_encode($key.':')),
    'body' => $data
  ));

  $code = wp_remote_retrieve_response_code( $res );
  $body = wp_remote_retrieve_body( $res );
  $body = json_decode($body, true);

  return array_merge(array('code' => $code), $body);
}
