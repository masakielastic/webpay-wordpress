<?php
function webpay_ajax_response() {
  $slug = webpay_checkout_get_settings('slug');
  load_plugin_textdomain( $slug, false,
    dirname(plugin_basename( __FILE__ )). '/languages/'
  );

  if (!check_ajax_referer( webpay_checkout_get_settings('nonce'), 'security', false )) {
    http_response_code(400);
    wp_send_json( array(
      'msg' =>  'nonce が一致しません。'
    ) );
  }

  $key = webpay_get_private_key();

  $data = array(
    'amount' => $_POST['amount'],
    'currency' => webpay_get_currency(),
    'card' => $_POST['token']
  );

  try {
    $webpay = new WebPay\WebPay($key);
    $webpay->charge->create($data);
    $status = 200;
    $msg = __( 'Thank you', $slug );
  } catch (\Exception $e) {
    $status = $e->getStatus();
    $msg = __( 'Failed', $slug ).' '.$e->getMessage();
  }

  http_response_code($status);
  wp_send_json( array( 'msg' =>  $msg ) );
}

function webpay_checkout_shortcode($atts) {

  if (!is_ssl()) {
    return 'SSL/TLS で接続してください。';
  }

  $slug = webpay_checkout_get_settings('slug');
  load_plugin_textdomain( $slug, false,
    dirname(plugin_basename( __FILE__ )). '/languages/'
  );

  $ret = shortcode_atts(array(
    'amount' => 0,
    'label' => __( 'purchase', $slug )
  ), $atts);

  $amount = $ret['amount'];
  $label = esc_attr( $ret['label'] );

  $json_options = JSON_HEX_QUOT | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_TAG;

  $url = json_encode( admin_url( 'admin-ajax.php' ), $json_options );

  $data = json_encode( array(
    'security' => wp_create_nonce( webpay_checkout_get_settings('nonce') ),
    'action' => webpay_checkout_get_settings('action'),
    'amount' => $amount
  ), $json_options );

  $msg = json_encode( array(
    'no_input' => __( 'Input card number', $slug ),
    'no_amount' => __( 'Input amount', $slug )
  ), $json_options );

  $locale = get_locale() === 'ja' ? 'ja' : 'en';
  $public_key = webpay_get_public_key();

  include 'webpay-checkout-view.php';
}
