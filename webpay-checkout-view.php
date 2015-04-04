<div>
<form id="webpayCheckout" action="#" method="post">
  <script 
    src="https://checkout.webpay.jp/v2/"
    class="webpay-button"
    data-key="<?php echo $public_key ?>"
    data-lang="<?php echo $locale ?>"
    data-partial="true"
  ></script>
  <p><input id="webpayDoCheckout" type="submit" value="<?php echo $label; ?>" /></p>
  <p><?php echo $placeholder; ?></p>
</form>
</div>

<div id="webpay_result" style="color: red"></div>
<script>
jQuery(function($) {

  var url = <?php echo $url ?>;
  var data = <?php echo $data ?>;
  var msg = <?php echo $msg ?>;

  $('#webpayDoCheckout').click(function(event) {
    event.preventDefault();

    $ret = $('#webpay_result');

    var token = $('#webpayCheckout').serializeArray()[0]['value'];

    if ( token === '' ) {
      $ret.html( msg['no_input'] );
      return false;
    }

    data['token'] = token;
 
    $.post( url, data, function(res) {

      if ( res['code'] === 201 ) {
        $ret.html( msg['success'] );
      } else {
        $ret.html( msg['fail'] );
      }
      
      return false;
    }, 'json' );

  });

});
</script>