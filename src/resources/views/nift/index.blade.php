<!doctype html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <style>
        .flex-checkout{
            display: flex;
            flex-direction: row;
            justify-content: space-around;
            height: 100vh;
            background-color: #eee;
        }
        button {
            position: relative;
            display: inline-block;
            cursor: pointer;
            outline: none;
            border: 0;
            vertical-align: middle;
            text-decoration: none;
            background: transparent;
            padding: 0;
            font-size: inherit;
            font-family: inherit;
        }
        button.learn-more {
            width: 20rem;
            height: auto;
        }
        button.learn-more .circle {
            transition: all 0.45s cubic-bezier(0.65, 0, 0.076, 1);
            position: relative;
            display: block;
            margin: 0;
            width: 3rem;
            height: 3rem;
            background: #282936;
            border-radius: 1.625rem;
        }
        button.learn-more .circle .icon {
            transition: all 0.45s cubic-bezier(0.65, 0, 0.076, 1);
            position: absolute;
            top: 0;
            bottom: 0;
            margin: auto;
            background: #fff;
        }
        button.learn-more .circle .icon.arrow {
            transition: all 0.45s cubic-bezier(0.65, 0, 0.076, 1);
            left: 0.625rem;
            width: 1.125rem;
            height: 0.125rem;
            background: none;
        }
        button.learn-more .circle .icon.arrow::before {
            position: absolute;
            content: '';
            top: -0.25rem;
            right: 0.0625rem;
            width: 0.625rem;
            height: 0.625rem;
            border-top: 0.125rem solid #fff;
            border-right: 0.125rem solid #fff;
            transform: rotate(45deg);
        }
        button.learn-more .button-text {
            transition: all 0.45s cubic-bezier(0.65, 0, 0.076, 1);
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            padding: 0.75rem 0;
            margin: 0 0 0 1.85rem;
            color: #282936;
            font-weight: 700;
            line-height: 1.6;
            text-align: center;
            text-transform: uppercase;
        }
        button:hover .circle {
            width: 100%;
        }
        button:hover .circle .icon.arrow {
            background: #fff;
            transform: translate(1rem, 0);
        }
        button:hover .button-text {
            color: #fff;
        }

    </style>

    <!-- CSS only -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
          integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/crypto-js.min.js"></script>

    <!-- JS, Popper.js, and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
            integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
            crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
            integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
            crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
            integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI"
            crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/b0164b108d.js" crossorigin="anonymous"></script>
</head>
<body onload="initialize()">
<div class="body-height bg-light">
    <div class="flex-checkout">
        <div class="container text-center">
            <div class="row">
                <div class="col-12 mt-5">
                    <div class="brrring-image">
                        <img class="logo-img" src="{{asset('images/defaults/logo.png')}}" alt="logo" height="50">
                    </div></div>
                <div class="col-12 mt-5">
                    <input class="textboxStyle" id="Salt" type="hidden" name="Salt" value="{{$settings->salt}}" placeholder="Enter secret key" \>
                    <form id="s2bpay-ctgry1-form" target="s2bpay_popup_window" onsubmit="return calculateHash()" name="niftForm" method="POST" action="https://uat-merchants.niftepay.pk/CustomerPortal/transactionmanagement/merchantform">
                        @csrf
                        <input class="textboxStyle" type="hidden" id="pp_Version" name="pp_Version" value="1.1" \>
                        <input class="textboxStyle" type="hidden" id="pp_Language" name="pp_Language" value="EN" \>
                        <input class="textboxStyle" type="hidden" id="pp_MerchantID" name="pp_MerchantID" Value="{{$settings->merchant_id}}"\>
                        <input class="textboxStyle" type="hidden" id="pp_SubMerchantID" name="pp_SubMerchantID" value=""\>
                        <input class="textboxStyle" type="hidden" id="pp_Password" name="pp_Password" value="{{$settings->password}}"\>
                        <input class="textboxStyle" type="hidden" id="pp_Amount" name="pp_Amount" value="{{$settings->amount}}" \>
                        <input class="textboxStyle" type="hidden" id="pp_ProductID" name="pp_ProductID" value="{{$settings->bill_id}}" \>
                        <input class="textboxStyle" type="hidden" id="pp_TxnRefNo" name="pp_TxnRefNo" value="{{$settings->ref_id}}" \>
                        <input class="textboxStyle" type="hidden" id="pp_TxnDateTime" name="pp_TxnDateTime" value="20200219172008" \>
                        <input class="textboxStyle" type="hidden" id="pp_TxnExpiryDateTime" name="pp_TxnExpiryDateTime" value="20200220172008" \>
                        <input class="textboxStyle" type="hidden" id="pp_BillReference" name="pp_BillReference" value="{{$settings->order_id}}" \>
                        <input class="textboxStyle" type="hidden" id="pp_Description" name="pp_Description" value="{{$settings->description}}" \>
                        <input class="textboxStyle biggerTextBox" type="hidden" id="pp_ReturnURL" name="pp_ReturnURL" value="{{$settings->redirect_url}}" \>
                        <input class="textboxStyle" type="hidden" id="pp_TxnCurrency" name="pp_TxnCurrency" value="PKR" \>
                        <input class="textboxStyle" type="hidden" id="ppmpf_1" name="ppmpf_1" value="" placeholder="Parameter bounced back" \>
                        <input class="textboxStyle" type="hidden" id="ppmpf_2" name="ppmpf_2" value="" placeholder="Parameter bounced back" \>
                        <input class="textboxStyle" type="hidden" id="ppmpf_3" name="ppmpf_3" value="" placeholder="Parameter bounced back" \>
                        <input class="textboxStyle" type="hidden" id="ppmpf_4" name="ppmpf_4" value="" placeholder="Parameter bounced back" \>
                        <input class="textboxStyle" type="hidden" id="ppmpf_5" name="ppmpf_5" value="" placeholder="Parameter bounced back" \>
                        <input class="textboxStyle" type="hidden" id="pp_SecureHash" name="pp_SecureHash" value="" \>
                        <button class="learn-more" value="confirm" type="submit" name="pay" onclick="setTimeout(()=>{window.close()}, 500)">
                            <span class="circle" aria-hidden="true">
                              <span class="icon arrow"></span>
                            </span>
                            <span class="button-text">Processed to checkout</span>
                        </button>
                    </form>

                </div>
                <div class="col-12 mt-5">
                    <div class="nift-image">
                        <img class="logo-img" src="{{asset('img/nift_logo_v2.png')}}" alt="logo" height="50">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var x;
    function Xero(x) {
        return x > 9 ? "" + x : "0" + x;
    }

    function getDateTime(incrementDays) {
        var d = new Date();
        var M = d.getMonth() + 1;
        var D = d.getDate() + incrementDays;
        var H = d.getHours();
        var m = d.getMinutes() + 1;
        var s = d.getSeconds() + 1;
        var dateTime = "".concat(d.getFullYear().toString(), Xero(M), Xero(D), Xero(H), Xero(m), Xero(s));
        return dateTime;
    }

    function initialize() {
        let dateTime = getDateTime(0);
        // document.getElementById("pp_TxnRefNo").value = "T" + dateTime;
        document.getElementById("pp_TxnDateTime").value = dateTime;
        document.getElementById("pp_TxnExpiryDateTime").value = getDateTime(2);
        return true;
    }

    function calculateHash() {
        var inputs = {
            "pp_Amount": document.getElementById("pp_Amount").value,
            "pp_ProductID": document.getElementById("pp_ProductID").value,
            "pp_BillReference": document.getElementById("pp_BillReference").value,
            "pp_Description": document.getElementById("pp_Description").value,
            "pp_Language": document.getElementById("pp_Language").value,
            "pp_MerchantID": document.getElementById("pp_MerchantID").value,
            "pp_Password": document.getElementById("pp_Password").value,
            "pp_ReturnURL": document.getElementById("pp_ReturnURL").value,
            "pp_SubMerchantID": document.getElementById("pp_SubMerchantID").value,
            "pp_TxnCurrency": document.getElementById("pp_TxnCurrency").value,
            "pp_TxnDateTime": document.getElementById("pp_TxnDateTime").value,
            "pp_TxnExpiryDateTime": document.getElementById("pp_TxnExpiryDateTime").value,
            "pp_TxnRefNo": document.getElementById("pp_TxnRefNo").value,
            "pp_Version": document.getElementById("pp_Version").value,
            "ppmpf_1": document.getElementById("ppmpf_1").value,
            "ppmpf_2": document.getElementById("ppmpf_2").value,
            "ppmpf_3": document.getElementById("ppmpf_3").value,
            "ppmpf_4": document.getElementById("ppmpf_4").value,
            "ppmpf_5": document.getElementById("ppmpf_5").value
        }

        let orderedInputs = {};
        let concatenatedString = document.getElementById("Salt").value;

        Object.keys(inputs).sort().forEach(key => { concatenatedString = !(inputs[key] === "" || inputs[key] == undefined) ? concatenatedString + "&" + inputs[key] : concatenatedString; });

        var hash = CryptoJS.HmacSHA256(concatenatedString, document.getElementById("Salt").value);
        var hashInBase64 = CryptoJS.enc.Base64.stringify(hash);

        document.getElementById("pp_SecureHash").value = hash.toString();

        return true;
    }
</script>
</body>
</html>


