<!DOCTYPE html>
<html>
    <head>
        <link
            rel="stylesheet"
            href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css"
            integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l"
            crossorigin="anonymous"
        />

        <link
            rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"
        />

        <style>
            body {
                background-color: #2b3990;
                max-height: 100vh;
                height: 100vh;
                justify-content: center;
                align-items: center;
                display: flex;
                font-size: 1.5rem;
                overflow-y: auto;
            }

            .order-image {
                width: 150px;
                height: 150px;
            }

            .form-control {
                font-size: 1.5rem;
            }
            .btn-primary {
                background-color: #2b3990 !important;
            }
            .bg-primary {
                background-color: #2b3990 !important;
            }
            .brrring-logo {
                align-self: center;
                background-color: #2b3990;
                width: 150px;
                border-radius: 20px;
                padding: 20px;
            }
            input[type="button"] {
                font-size: 1.5rem;
            }

            .card {
                border-radius: 10px;
            }

            .card-header {
                border-top-left-radius: 10px !important;
                border-top-right-radius: 10px !important;
            }

            .order-container {
                display: flex;
            }
            .order-image-container {
                display: flex;
                flex: 1;
                flex-direction: row;
                margin: 20px 0px;
            }
            .order-image {
                border-radius: 10px;
                border: 5px solid black;
                object-fit: cover;
                width: 200px;
                height: 200px;
            }
            .order-title {
                flex: 1;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                padding: 5px 10px;
            }

            .source-destination-container {
                display: flex;
                flex-direction: row;
                justify-content: space-around;
            }
            .source-destination-container div {
                flex: 1;
                display: flex;
                justify-content: center;
                align-items: center;
                text-align: center;
            }
            .plane {
                display: flex;
                flex-direction: row;
                justify-content: space-around;
            }

            .plane div {
                flex: 1;
                width: 100%;
                height: 1px;
                border-top: 4px dotted black;
            }
            .plane i {
                flex: 1;
            }
            td {
                padding: 5px 0.75rem !important;
            }

            tr td:last-child,
            tr th:last-child {
                text-align: right !important;
            }

            span.currency {
                font-size: 1rem;
            }

        </style>

    </head>

    <body>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-body">
                            <div class="row justify-content-center mb-3">
                                <img
                                    src="{{ asset('img/brrring_logo.png') }}"
                                    alt=""
                                    width="50%"
                                />
                            </div>

                            <div class="order-container">
                                <div class="order-image-container">
                                    <img
                                        src="{{asset('images/'.$order->image)}}"
                                        alt=""
                                        class="order-image"
                                    />
                                    <div class="order-title">
                                        <div class="text-center">
                                            <h1>
                                                <strong>{{$order->name}}</strong>
                                            </h1>
                                            <h4 class="text-secondary">
                                                {{$order->category->name}}
                                            </h4>
                                        </div>
                                        <div>
                                            <div class="source-destination-container">
                                                <div class="source">
                                                    {{$order->from}}
                                                </div>
                                                <div class="plane">
                                                    <div class="line"></div>
                                                    <i class="fas fa-plane"></i>
                                                    <div class="line"></div>
                                                </div>
                                                <div class="destination">
                                                    {{$order->to}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-3">
                                <div class="card-header bg-primary text-white">
                                    <div class="card-title m-0">
                                        Payment Breakdown
                                    </div>
                                </div>
                                <div class="card-body p-0">
                                    @php setlocale(LC_MONETARY,"en_US"); @endphp
                                    <table
                                        class="
                                            table table-hover table-stripe
                                            m-0
                                        "
                                    >
                                        <tbody>
                                            <tr>
                                                <td>Item Price</td>
                                                <td>
                                                    <span class="currency"
                                                        >{{$order->user->currency->symbol}}</span
                                                    >
                                                    {{number_format($order->price, 2)}}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Reward Price</td>
                                                <td>
                                                    <span class="currency"
                                                        >{{$order->user->currency->symbol}}</span
                                                    >
                                                    {{number_format($order->reward, 2)}}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Duty Charges</td>
                                                <td>
                                                    <span class="currency"
                                                        >{{$order->user->currency->symbol}}</span
                                                    >
                                                    {{number_format($order->duty_charges, 2)}}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Brrring Service Fee(5%)</td>
                                                <td>
                                                    <span class="currency"
                                                        >{{$order->user->currency->symbol}}</span
                                                    >
                                                    {{number_format($order->service_fee, 2)}}
                                                </td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr class="bg-primary">
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            @if($order->wallet_amount)
                                            <tr>
                                                <td>Sub Total </td>
                                                <td>
                                                    <span class="currency"
                                                        >{{$order->user->currency->symbol}}</span
                                                    >
                                                    {{number_format($order->sub_total_payable, 2)}}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Deducted from Wallet</td>
                                                <td>
                                                    <span class="currency"
                                                        >{{$order->user->currency->symbol}}</span
                                                    >
                                                    -{{number_format($order->wallet_amount, 2)}}
                                                </td>
                                            </tr>
                                            @endif
                                            <tr>
                                                <th>Grand Total Payable</th>
                                                <th>
                                                    <span class="currency"
                                                        >{{$order->user->currency->symbol}}</span
                                                    >
                                                    {{number_format($order->total_payable, 2)}}
                                                </th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>

                            <div class="card shadow">
                                <div class="card-body">
                                    <div
                                        class="alert alert-danger"
                                        id="AlertMessage"
                                        hidden
                                    ></div>
                                    <div id="PGWHPCACCTContainer2"></div>
                                    <div class="text-center mt-3">
                                        <span style="color: #acacac"
                                            >Powered by</span
                                        >
                                        <img
                                            src="{{
                                                asset('img/nift_logo.png')
                                            }}"
                                            alt="NiftePay"
                                            width="100px"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    <!-- PGW HPC CARD PLUGIN -->
    <script
        type="text/javascript"
        src="https://uat-merchants.niftepay.pk/HPS/PGWHPCPlugin.js"
    ></script>

    <!-- CrytoJS -->
    <script
        type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.0.0/crypto-js.min.js"
    ></script>

    <!-- INITIALIZE PLUGIN -->
    <script type="text/javascript">

        const urlParams = new URLSearchParams(window.location.search);
        const currencyIds = urlParams.getAll('currency_ids[]');
        const deductAmounts = urlParams.getAll('deduct_amounts[]');
        const bearer = "{{$bearer}}";

        console.log(currencyIds, deductAmounts);

        var url = new URL(window.location.href);

        document.addEventListener("message", function(data) {
            console.log("MESSAGE FROM RN:", data);
            // bearer = data;
        });

        const SALT = "XFgJzjdk0jY=";

        function changeDateFormat() {
            Date.prototype.yyyymmdd = function () {
                function pad2(n) {
                    return (n < 10 ? "0" : "") + n;
                }

                return [
                    this.getFullYear(),
                    pad2(this.getMonth() + 1),
                    pad2(this.getDate()),
                    pad2(this.getHours()),
                    pad2(this.getMinutes()),
                    pad2(this.getSeconds()),
                ].join("");
            };
        }

        changeDateFormat();

        let date = new Date();
        let expDate = new Date(
            date.getTime() + 7 * 24 * 60 * 60 * 1000
        ).yyyymmdd();
        console.log("DateTime: " + date.yyyymmdd());
        console.log("Expiry DateTime: " + expDate);
        // let txnRefNo = ["T", date.yyyymmdd()].join("");
        // let txnRefNo = ["T", date.yyyymmdd()].join("");
        let txnRefNo = "{{$ref_no}}";

        const totalPayable = {{$order->total_payable_pkr}};
        const amount = "{{(int)(round($order->total_payable_pkr, 2)*100)}}";

        //Update transaction parameters
        PGWHPCTransactionParameters.__00amt__ = amount; //pp_Amount
        PGWHPCTransactionParameters.__01brn__ = `billref`; //new Date().getTime().toString(); //pp_BillReference
        PGWHPCTransactionParameters.__02des__ = `{{$order->description}}`; //pp_Description
        PGWHPCTransactionParameters.__03lan__ = `EN`; //pp_Language
        PGWHPCTransactionParameters.__04mid__ = `MR5016`; //pp_MerchantID
        PGWHPCTransactionParameters.__05pwd__ = `ydQKxki2k9A=`; //pp_Password
        PGWHPCTransactionParameters.__06url__ = `https://apis.brrring.co/paymentCallback`; //pp_ReturnURL
        PGWHPCTransactionParameters.__07smid__ = ``; //pp_SubMerchantID
        PGWHPCTransactionParameters.__08seh__ = SALT; //pp_SecureHash
        PGWHPCTransactionParameters.__09cur__ = `PKR`; //pp_TxnCurrency
        PGWHPCTransactionParameters.__10tdt__ = date.yyyymmdd(); //pp_TxnDateTime
        PGWHPCTransactionParameters.__11edt__ = expDate; //pp_TxnExpiryDateTim
        PGWHPCTransactionParameters.__12trn__ = txnRefNo; //pp_TxnRefNo
        PGWHPCTransactionParameters.__13ver__ = `1.1`; //pp_Version

        delete PGWHPCTransactionParameters.__08seh__; //pp_SecureHash

        var keys = Object.keys(PGWHPCTransactionParameters).sort(function (
            a,
            b
        ) {
            if (a.toLowerCase() < b.toLowerCase()) return -1;
            if (a.toLowerCase() > b.toLowerCase()) return 1;
            return 0;
        });
        var message = "";
        keys.forEach((key) => {
            message += PGWHPCTransactionParameters[key]
                ? [PGWHPCTransactionParameters[key], "&"].join("")
                : "";
        });
        message = [SALT, "&", message.slice(0, -1)].join("");

        // calculate secureHash
        var hash = CryptoJS.HmacSHA256(message, SALT);

        PGWHPCTransactionParameters.__08seh__ = hash.toString().toUpperCase();

        let accNumberField;
        let CNICField;
        let OTPField;

        //Initialize
        document.addEventListener("DOMContentLoaded", function (event) {
            PGWHPCAcct.initialize("PGWHPCACCTContainer2"); //Render Account Fields
            let formGroups = document.getElementsByClassName("pgwhpcacctfield");
            for (const formGroup of formGroups) {
                formGroup.classList.add("form-group");
            }

            let formControls = document.getElementsByTagName("select");
            for (const formControl of formControls) {
                formControl.classList.add("form-control");
            }
            formControls = document.getElementsByTagName("input");
            for (const formControl of formControls) {
                if (formControl.getAttribute("type") == "button") {
                    formControl.classList.add("btn");
                    formControl.classList.add("btn-primary");
                    formControl.classList.add("btn-block");
                } else {
                    formControl.classList.add("form-control");
                }
            }

            accNumberField = document.getElementById("pgwhpcfld_AccountNumber");
            CNICField = document.getElementById("pgwhpcfld_CNIC");

            accNumberField.setAttribute("placeholder", "Account Number");
            CNICField.setAttribute("placeholder", "CNIC");
        });

        PGWHPCSuccessMap.onSuccessPaymentValidation = function (msg) {
            document.getElementById("pgwhpcfld_Bank").value = "BOPBank";
            console.log("on success PV",msg);

            request = $.ajax({
                type: "POST",
                url: "{{url('/api/v1/wallet/credit')}}",
                headers: {
                    "X-CSRF-TOKEN": "{{csrf_token()}}",
                    "Authorization": bearer,
                },
                contentType: "application/json",
                dataType: "json",
                data: JSON.stringify({
                    amount: {{$order->total_payable}},
                    source: "bankaccount",
                    transaction_details: {
                        offer_id: {{$offer->id}}
                    },
                    ref_no: txnRefNo,
                }),
                success: function(response) {
                    console.log("WALLER CREDIT RESPONSE:", response);
                },
                error : function(error) {
                    showErrorMessage(error.responseJSON.message);
                }
            });
        };

        PGWHPCSuccessMap.onPurchaseProcessedViaAccount = function (msg) {
            console.log("purchase done - Account");
            console.log(msg);

            request = $.ajax({
                type: "POST",
                url: "{{url('/api/v1/wallet/status')}}",
                headers: {
                    "X-CSRF-TOKEN": "{{csrf_token()}}",
                    "Authorization": bearer,
                },
                contentType: "application/json",
                dataType: "json",
                data: JSON.stringify({
                    transaction_details: {
                        offer_id: {{$offer->id}},
                        response: msg
                    },
                    ref_no: txnRefNo,
                }),
                success: function(updateStatusResponse) {
                    console.log(updateStatusResponse);

                    let wallets = [];
                    for (let index = 0; index < currencyIds.length; index++) {
                        let wallet = {
                            currency_id: currencyIds[index],
                            deduct: deductAmounts[index],
                        };

                        // if(wallet.currency_id == 87) {
                        //     wallet.deduct = parseFloat(wallet.deduct)+parseFloat(totalPayable);
                        // }
                        wallets.push(wallet);
                    }

                    console.log("WALLETS:",wallets);
                    $.ajax({
                        type: "POST",
                        url: "{{url('/api/v1/orders/'.$order->id.'/acceptOffer')}}",
                        headers: {
                            "X-CSRF-TOKEN": "{{csrf_token()}}",
                            "Authorization": bearer,
                        },
                        contentType: "application/json",
                        dataType: "JSON",
                        data: JSON.stringify({
                            offer_id: {{$offer->id}},
                            wallets: wallets
                        }),
                        success: function(response) {
                            console.log("SUCCESS RESPONSE");
                            console.log(response);
                            showSuccessMessage("Offer Accepted Successfully");
                            // POst message to React Native
                            if(window.ReactNativeWebView){
                                setTimeout(
                                    window.ReactNativeWebView.postMessage("success"), 2000
                                )
                            }
                        },
                        error : function(error) {
                            console.log("ERROR  WHILE TRANSACATIAONASD:A");
                            showErrorMessage(JSON.stringify(error));
                            console.log(JSON.stringify(error));
                            console.error(error);
                        }
                    });
                },
                error : function(error) {
                    showErrorMessage(JSON.stringify(error));
                    console.error(error);
                }
            });

        };

        PGWHPCSuccessMap.onSuccessProcessAccountDetails = function (msg) {
            console.log("onSuccessProcessAccountDetails:0", msg);
        };

        PGWHPCErrorMap.onInvalidCNIC = function (msg) {
            console.log(msg);
            //Your code to handle this error
            // // ;
            showErrorMessage(msg, CNICField);
        };

        PGWHPCErrorMap.onInvalidContact = function (msg) {
            console.log(msg);
            //Your code to handle this error
            // // ;
            showErrorMessage("Invalid Contact");
        };

        PGWHPCErrorMap.onInvalidBank = function (msg) {
            console.log(msg);
            //Your code to handle this error
            // ;
            showErrorMessage("Invalid Bank");
        };

        PGWHPCErrorMap.onInvalidAccount = function (msg) {
            console.log("onInvalidAccount");
            console.log(msg);
            //Your code to handle this error
            // ;
            showErrorMessage(msg.pp_ResponseMessage, accNumberField);
        };

        PGWHPCErrorMap.onInvalidOTP = function (msg) {
            alert("Invalid OTP");
            console.log(msg);
            //Your code to handle this error
            // ;
        };

        PGWHPCErrorMap.onExpiredOTP = function (msg) {
            alert("OTP Expired");
            console.log(msg);
            //Your code to handle this error
            // ;
        };

        PGWHPCErrorMap.onInvalidTransactionDetails = function (msg) {
            alert("Invalid Transaction Details");
            console.log(msg);
            //Your code to handle this error
        };

        PGWHPCErrorMap.onInvalidContainerArea = function (msg) {
            console.log(msg);
            //Your code to handle this error
            // // ;
        };

        PGWHPCErrorMap.onRequestTimedOut = function (msg) {
            alert("Request Timedout");
            console.log(msg);
            //Your code to handle this error
            // ;
        };

        PGWHPCErrorMap.onSystemError = function (msg) {
            console.error("onSystemError:", msg);
            //Your code to handle this error
            // ;
            showErrorMessage(
                "Apologies! we are facing some technical issues temporarily, Please try again later"
            );
        };

        PGWHPCErrorMap.onGenericError = function (msg) {
            // alert('Error')
            console.log(msg);
            //Your code to handle this error
            // ;
        };

        function showErrorMessage(message, field = null) {
            let element = document.getElementById("AlertMessage");
            element.innerHTML = message;
            element.hidden = false;

            if (field && !field.classList.contains("is-invalid")) {
                field.classList.add("is-invalid");
            }
        }

        function showSuccessMessage(message) {
            console.log("SHOW SUCCESS MESSAGE");
            let element = document.getElementById("AlertMessage");

            element.classList.remove('alert-danger');
            element.classList.add('alert-success');

            element.innerHTML = message;
            element.hidden = false;
            let inputs = document.getElementsByTagName("input");


            for (const input of inputs) {
                if (input.classList.contains("is-invalid")) {
                    input.classList.remove("is-invalid");
                }
            }
        }
    </script>
</html>