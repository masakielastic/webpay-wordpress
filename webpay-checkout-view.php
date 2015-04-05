<div>
<form id="webpayCheckout" action="#" method="post">
<?php if (0 >= $amount) { ?>
  <input type="number" id="amountInput" min=0>
<?php } ?>
  <script 
    src="https://checkout.webpay.jp/v2/"
    class="webpay-button"
    data-key="<?php echo $public_key ?>"
    data-lang="<?php echo $locale ?>"
    data-partial="true"
  ></script>
  <p><input id="webpayDoCheckout" type="submit" value="<?php echo $label; ?>" /></p>
</form>
</div>

<div id="webpay_result" style="color: red"></div>
<script>
jQuery(function($) {

  var url = <?php echo $url ?>;
  var formData = <?php echo $data ?>;

  $msg = $('#webpay_result');

  $('#webpayDoCheckout').click(function(event) {
    event.preventDefault();

    <?php if (0 >= $amount) { ?>
    formData['amount'] = $('#amountInput').val();
    <?php } ?>

    if (formData['amount'] === '') {
      $msg.html( msg['no_amount'] );
    }

    var token = $('#webpayCheckout').serializeArray()[0]['value'];

    if ( token === '' ) {
      $msg.html( msg['no_input'] );
      return false;
    }

    formData['token'] = token;
 
    $.post( url, formData, function(data, textStatus, jqXHR) {
      $msg.html( data['msg'] );
    }, 'json' ).fail(function(jqXHR, textStatus, errorThrown) {
      $msg.html( jqXHR.responseJSON['msg'] );
    });
  });

});
</script>