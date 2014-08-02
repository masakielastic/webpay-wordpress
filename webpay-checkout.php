<?php

add_action( 'wp_ajax_webpay_checkout', 'webpay_ajax_response' );
add_action( 'wp_ajax_nopriv_webpay_checkout', 'webpay_ajax_response' );
add_shortcode( 'webpay', 'webpay_checkout_shortcode' );

function webpay_ajax_response() {
    $settings = webpay_checkout_get_settings();
    check_ajax_referer( $settings['nonce'], 'security' );

    header( 'Content-Type: application/json' );
    $ret = array('msg' => 'ok');
    wp_send_json( $ret );
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

?>
<div id="webpay_result" style="color: red"></div>
<script>
jQuery(function($) {

  var url = <?php echo $url ?>;
  var data = <?php echo $json ?>;

  $.post(  url, data, function(res) {

    $ret = $('#webpay_result');

     if ( res['msg'] === 'ok' ) {
       $ret.html( 'ありがとうございました。' );
     } else {
       $ret.html( '投稿が失敗しました。' );
     }
   

  }, 'json' );
});
</script>
<?php
}
