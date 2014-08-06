<form id="webpayCheckout" action="#" method="post">
  <script>
  var token = '';

  function onCreate(data) {
    token = data.id;

    return false;
  }
  </script>
  <script 
    src="https://checkout.webpay.jp/v2/"
    class="webpay-button"
    data-key="<?php echo $public_key ?>"
    data-lang="<?php echo $locale ?>"
    data-partial="true"
    data-on-created="onCreate"
  ></script>
  <input id="webpayDoCheckout" type="submit" value="購入する" />
</form>

<div id="webpay_result" style="color: red"></div>
<script>
jQuery(function($) {

  var url = <?php echo $url ?>;
  var data = <?php echo $json ?>;

  $('#webpayDoCheckout').click(function(event) {
    event.preventDefault();

    $ret = $('#webpay_result');

    if ( token === '' ) {
      $ret.html( 'カード番号を入力してください。' );
      return false;
    }

    data['token'] = token;

    $.post(  url, data, function(res) {

      if ( res['code'] === 201 ) {
        $ret.html( 'ありがとうございました。' );
      } else {
        $ret.html( '投稿が失敗しました。' );
      }
      
      return false;
    }, 'json' );

  });

});
</script>