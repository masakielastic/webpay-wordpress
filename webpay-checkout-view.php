<div>
<form id="webpayCheckout" action="#" method="post">
  <?php if ($any === 'true') { ?>
  <p><input id="webpay_user_input" placeholder="<?php echo $placeholder; ?>" type="number" /></p>
  <?php } ?>
  <script 
    src="https://checkout.webpay.jp/v2/"
    class="webpay-button"
    data-key="<?php echo $public_key ?>"
    data-lang="<?php echo $locale ?>"
    data-partial="true"
  ></script>
  <?php if ($any === 'true' || $amount > 0) { ?>
  <p><input id="webpayDoCheckout" type="submit" value="<?php echo $label; ?>" /></p>
  <?php } else { ?>
  <p><?php echo $placeholder; ?></p>
  <?php } ?>
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

    <?php if ($any === 'true') { ?>
    data['amount'] = $('#webpay_user_input').val();
    if (data['amount'] === '' || 0 >= data['amount']) {
      $ret.html( msg['no_amount'] );
      return false;
    }
    <?php } ?>

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