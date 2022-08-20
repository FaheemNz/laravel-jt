<!DOCTYPE html>
<html>

<head>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/crypto-js.min.js"></script>
    <style>
        .textboxStyle {
            width: 20%;
        }

        .biggerTextBox {
            width: 50%;
        }

        body {
            padding: 40px 0 100px 20px;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            /* Green */
            border: none;
            color: white;
            padding: 15px 32px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
        }

        div {
            margin-bottom: 15px;
            padding: 12px;
        }

        .info {
            background-color: #e7f3fe;
            border-left: 6px solid #2196F3;
        }

        .warning {
            background-color: #ffffcc;
            border-left: 6px solid #ffeb3b;
        }
    </style>
</head>

<body onload="initialize()">

<h1>Transaction Payload NIFT UAT</h1>
<div class="info">This is sample code for posting request to <strong>UAT NIFTePay</strong>. Kindly replace the
    values of <b>Secret Key, pp_MerchantID, pp_SubMerchantID, pp_Password, and pp_ReturnURL</b> with values provided
    to you by NIFT. </br>On submitting, form will be posted in browser's new tab.</div>

<div class="warning"><strong>Secret Key</strong> is taken here just for demonstration purpose of Secure Hash
    calculation. It must be kept secret and hash calculation must be done on server side to avoid risk of exposing
    sensitive information to User.</div>

<br><br>Salt / Secrert Key: <input class="textboxStyle" id="Salt" name="Salt" value=""
                                   placeholder="Enter secret key" \>
<form id="s2bpay-ctgry1-form" target="s2bpay_popup_window" onsubmit="return calculateHash()" name="niftForm"
      method="POST" action="https://uat-merchants.niftepay.pk/CustomerPortal/transactionmanagement/merchantform" />
<br><br>pp_Version: <input class="textboxStyle" id="pp_Version" name="pp_Version" value="1.1" \>
<br><br>pp_Language: <input class="textboxStyle" id="pp_Language" name="pp_Language" value="EN" \>
<br><br>pp_MerchantID: <input class="textboxStyle" id="pp_MerchantID" name="pp_MerchantID" Value=""
                              placeholder="enter parent merhcant ID" \>
<br><br>pp_SubMerchantID: <input class="textboxStyle" id="pp_SubMerchantID" name="pp_SubMerchantID"
                                 value="Uniquegro" placeholder="Enter sub merchant id (if any)" \>
<br><br>pp_Password: <input class="textboxStyle" id="pp_Password" name="pp_Password" value=""
                            placeholder="enter merhcant password" \>
<br><br>pp_Amount: <input class="textboxStyle" id="pp_Amount" name="pp_Amount" value="100" \>
<br><br>pp_TxnRefNo: <input class="textboxStyle" id="pp_TxnRefNo" name="pp_TxnRefNo" value="T20200219172009" \>
<br><br>pp_TxnDateTime: <input class="textboxStyle" id="pp_TxnDateTime" name="pp_TxnDateTime" value="20200219172008"
                               \>
<br><br>pp_TxnExpiryDateTime: <input class="textboxStyle" id="pp_TxnExpiryDateTime" name="pp_TxnExpiryDateTime"
                                     value="20200220172008" \>
<br><br>pp_BillReference: <input class="textboxStyle" id="pp_BillReference" name="pp_BillReference"
                                 value="20000122455" \>
<br><br>pp_Description: <input class="textboxStyle" id="pp_Description" name="pp_Description" value="SAMPLE_DES" \>
<br><br>pp_ReturnURL: <input class="textboxStyle biggerTextBox" id="pp_ReturnURL" name="pp_ReturnURL" value=""
                             placeholder="enter merhcant return url" \>
<br><br>pp_TxnCurrency: <input class="textboxStyle" id="pp_TxnCurrency" name="pp_TxnCurrency" value="PKR" \>
<br><br>ppmpf_1: <input class="textboxStyle" id="ppmpf_1" name="ppmpf_1" value=""
                        placeholder="Parameter bounced back" \>
<br><br>ppmpf_2: <input class="textboxStyle" id="ppmpf_2" name="ppmpf_2" value=""
                        placeholder="Parameter bounced back" \>
<br><br>ppmpf_3: <input class="textboxStyle" id="ppmpf_3" name="ppmpf_3" value=""
                        placeholder="Parameter bounced back" \>
<br><br>ppmpf_4: <input class="textboxStyle" id="ppmpf_4" name="ppmpf_4" value=""
                        placeholder="Parameter bounced back" \>
<br><br>ppmpf_5: <input class="textboxStyle" id="ppmpf_5" name="ppmpf_5" value=""
                        placeholder="Parameter bounced back" \>
<br><br>pp_SecureHash: <input class="textboxStyle" id="pp_SecureHash" name="pp_SecureHash" value="" \>
<br><br><input type="submit" value="Submit">
</form>

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
        document.getElementById("pp_TxnRefNo").value = "T" + dateTime;
        document.getElementById("pp_TxnDateTime").value = dateTime;
        document.getElementById("pp_TxnExpiryDateTime").value = getDateTime(2);
        return true;
    }

    function calculateHash() {
        var inputs = {
            "pp_Amount": document.getElementById("pp_Amount").value,
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

        console.log(concatenatedString);
        var hash = CryptoJS.HmacSHA256(concatenatedString, document.getElementById("Salt").value);
        var hashInBase64 = CryptoJS.enc.Base64.stringify(hash);

        console.log(hash.toString());
        document.getElementById("pp_SecureHash").value = hash.toString();


        return true;
    }
</script>

</body>

</html>
