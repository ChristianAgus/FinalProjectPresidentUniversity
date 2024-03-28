<?php
include_once "lib/NicepayLib.php";

$amount = 100;
$referenceNo = 'Invoice-001';

$nicepay = new NicepayLib();
$nicepay->set('amt', $amount);
$nicepay->set('referenceNo', $referenceNo);
$nicepay->merchantToken();

?>
<!DOCTYPE html>
<html lang="en">
    <head>
      <title>NICEPAY - Secure Checkout</title>
      <link rel='stylesheet' href='index.css' type='text/css'/>
      <!-- Please include featherlight.js for lightbox demo -->
      <link rel='stylesheet' href='featherlight.min.css' type='text/css'/>
      <meta charset="UTF-8">
      <!-- Please include nicepay.js and jquery in the head block -->
      <script type="text/javascript" src="jquery.1.11.3.min.js"></script>
      <script type="text/javascript" src="nicepay.js"></script>
      <script type="text/javascript" src="featherlight.min.js"></script>
    </head>
    <body>
    <div class="wrapper">
      <div class="btn-group" id="form-selector">
        <button type="button" class="btn btn-selector active" id="va-btn">Bank Transfer</button>
        <button type="button" class="btn btn-selector" id="cc-btn">Credit Card </button>
      </div>
      <br />
      <div>
        <button type="button" class="btn btn-selector" id="reckeyin-btn">Recurring Non-CVV</button>
        <button type="button" class="btn btn-selector" id="recthreeds-btn">Recurring CVV & 3DS</button>
      </div>

      <div class="form" id="va-form">
        <form name="vaForm" action="charge.php" method="post">
          <h2 class="form-title">Bank Transfer</h2>
          <h4>Total</h4>
          <hr>
          <h3>Rp. 12.000,00</h3>
          <hr>
           <select name="bankCd">
              <option value="CENA">BCA</option>
              <option value="BNIN">BNI</option>
              <option value="BMRI">Mandiri</option>
              <option value="BBBA">Permata</option>
              <option value="IBBK">BII Maybank</option>
              <option value="BRIN">BRI</option>
              <option value="HNBN">KEB Hana Bank</option>
              <option value="HNBN">ATM Bersama</option>
              <option value="BNIA">CIMB Niaga</option>
              <option value="BDIN">Danamon</option>
            </select>
            <input type="hidden" name="payMethod" value="02">
          <button type="submit" class="btn-submit" id="va">Get Virtual Account!</button>
        </form>
      </div>

      <div class="form" id="cc-form">
        <form id="ccForm" action="charge.php" method="post">
          <h2 class="form-title">Credit Card</h2>
          <h4>Total</h4>
          <hr>
          <h3>Rp. 100,00</h3>
          <hr>
          <input name="billingNm" type="text" class="input-std" id="name" placeholder="Cardholder's Name">
          <input name="cardNo" type="text" class="input-std" id="number" placeholder="Card Number" maxlength="16">
          <input name="cardCvv" type="password" class="input-half" id="cvc" placeholder="CVV" maxlength="3">
          <input name="cardExpYymm" type="text" class="input-quarter" id="exp-month" placeholder="YYMM" maxlength="4">
          <input type="hidden" name="payMethod" value="01" id="payMethod">
          <input type="hidden" name="referenceNo" value="<?php echo $referenceNo; ?>" id="referenceNo">
          <input type="hidden" name="amt" value="<?php echo $amount; ?>" id="amt">
          <input type="hidden" name="resultMsg" value="" id="resultMsg">
          <input type="hidden" name="resultCd" value="" id="resultCd">
          <input type="hidden" name="onePassToken" id="onePassToken" value="">
          <button type="button" class="btn-submit" id="cc" onclick="javascript:nicepay('<?=$nicepay->merchantToken();?>','<?=$nicepay->get('iMid');?>');">Make Payment!</button>
      </form>
      </div>

      <div class="form" id="reckeyin-form">
        <form id="reckeyinForm" action="charge.php" method="post">
          <h2 class="form-title">Recurring Non-CVV</h2>
          <h4>Recurring Token</h4>
          <hr>
          <h3 style="padding-left:13%">
            <input type="text" id="recurringKeyIn" name="recurringKeyIn" placeholder="Fill Recurring Token" value="" required />
          </h3>
          <hr>
          <input type="hidden" name="payMethod" value="03">
          <button type="submit" class="btn-submit" id="reckeyin">Charge Recurring Token</button>
        </form>
      </div>

      <div class="form" id="recthreeds-form">
        <form id="recthreedsForm" action="charge.php" method="post">
          <h2 class="form-title">Recurring With CVV & 3DS</h2>
          <h4>Recurring Token</h4>
          <hr>
          <h3 style="padding-left:13%">
            <input type="text" id="recurringThreeDS" name="recurringThreeDS" placeholder="Fill Recurring Token" value="" required />
            <input type="password" id="cvvThreeDS" name="cvvThreeDS" placeholder="Fill CVV" value="" required />
            <input type="hidden"  id="referenceNoThreeDS" name="referenceNoThreeDS" value="<?php echo $referenceNo; ?>">
            <input type="hidden" id="amtThreeDS" name="amtThreeDS" value="<?php echo $amount; ?>">
            <input type="hidden" id="resultMsg" name="resultMsg" value="">
            <input type="hidden" id="resultCd" name="resultCd" value="">
          </h3>
          <hr>
          <input type="hidden" name="payMethod" value="04">
          <button type="button" class="btn-submit" id="recthreeds" onclick="javascript:recProcessThreeDS('<?=$nicepay->merchantToken();?>','<?=$nicepay->get('iMid');?>');">Charge Recurring Token</button>
        </form>
      </div>


    </div>

    <script type="text/javascript" src="index.js"></script>
    <body>
</html>
