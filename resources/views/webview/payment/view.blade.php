<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
</head>

<body>

    <p style="margin: 0"><b>Order ID:</b> {{ $data['txtIndex'] }}</p>
    <p style="margin: 0"><b>Order Amount:</b> {{ $data['txtAmount'] }}</p>
    <p style="margin: 0"><b>Currency:</b> @if($data['txtCurrency'] == 422) LBP @elseif($data['txtCurrency'] == 840) USD @endif</p>
    <p style="margin: 0"><b>Name:</b> {{ $user['first_name'] . ' ' . $user['last_name'] }}</p>
    <p style="margin: 0"><b>Email:</b> {{ $user['email'] }}</p>

    <form id="payment-form" method="POST" action="https://www.netcommercepay.com/iPAY/">
        <input type="hidden" name="payment_mode" value="">
        <input type="hidden" name="txtAmount" value="{{ $data['txtAmount'] }}">
        <input type="hidden" name="txtCurrency" value="{{ $data['txtCurrency'] }}">
        <input type="hidden" name="txtIndex" value="{{ $data['txtIndex'] }}">
        <input type="hidden" name="txtMerchNum" value="{{ $data['txtMerchNum'] }}">
        <input type="hidden" name="txthttp" value="{{ $data['txthttp'] }}">
        <input type="hidden" name="signature" value="{{ $signature }}">
        <input type="hidden" name="first_name" value="{{ $user['first_name'] }}">
        <input type="hidden" name="last_name" value="{{ $user['last_name'] }}">
        <input type="hidden" name="email" value="{{ $user['email'] }}">
        <input type="hidden" name="mobile" value="{{ $user['mobile_number'] }}">
        <input type="hidden" name="address_line1" value="{{ $user['street'] . ' - ' . $user['building'] }}">
        <input type="hidden" name="city" value="{{ $user['city'] }}">
        <input type="hidden" name="country" value="Lebanon">

        <br>

        <button type="submit">Proceed to checkout</button>
    </form>

    <br>

    <div>
        <img src="https://www.netcommercepay.com/commun/img/m000001.gif" alt="NetCommerce Security Seal">
    </div>

    <!-- <script>
        document.getElementById('payment-form').submit();
    </script> -->
</body>
</html>
