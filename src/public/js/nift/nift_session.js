// JavaScript source code
"use strict";

var PGWHPCCARDSessionID;
var PGWHPCTokenRequested = false;
var PGWHPCResendOTPTimer;
var PGWHPCResendOTPDelay = 60; //Timer in seconds to resend OTP
var PGWHPCOTPRequestSent = false;
const PGWHPCTokenURL = "https://uat-apis.niftepay.pk/IRISPGW2/api/v1/oauth2/token";
const PGWHPCTransactionURL = "https://uat-apis.niftepay.pk/IRISPGW2Core/";
const PGWHPCAuthToken = "cGd3cHI6cGd3cHJfbmlmdF81MTA==";
const SuiteURL = "https://uat-apis.niftepay.pk/IRISPGW2/";
var PGWHPCRequestToken;
var PGWHPCIntervalVar;
var PGWHPC3DSPopup;
var PGWHPC3DSecureID;
var PGWHPCCtxnkey;
var IsEscrowEnable;
//Error Code Mappings
const PGWHPCErrorMap = {
    onInvalidTransactionDetails: function (msg) {
        //console.log(msg);
        //Your code to handle this error
    },
    onInvalidContainerArea: function (msg) {
        //console.log(msg);
        //Your code to handle this error
    },
    onInvalidCardNumber: function (msg) {
        ///console.log(msg);
        //Your code to handle this error
    },
    onInvalidExpiryYear: function (msg) {
        //console.log(msg);
        //Your code to handle this error
    },
    onInvalidExpiryMonth: function (msg) {
        //console.log(msg);
        //Your code to handle this error
    },
    onInvalidSecurityCode: function (msg) {
        //console.log(msg);
        //Your code to handle this error
    },
    onInvalidCNIC: function (msg) {
        //console.log(msg);
        //Your code to handle this error
    },
    onInvalidMobile: function (msg) {
        //console.log(msg);
        //Your code to handle this error
    },
    onInvalidEmail: function (msg) {
        //console.log(msg);
        //Your code to handle this error
    },

    onInvalidContact: function (msg) {
        //console.log(msg);
        //Your code to handle this error
    },
    onInvalidBank: function (msg) {
        //console.log(msg);
        //Your code to handle this error
    },
    onInvalidAccount: function (msg) {
        //console.log(msg);
        //Your code to handle this error
    },
    onInvalidEmail: function (msg) {
        //console.log(msg);
        //Your code to handle this error
    },
    onInvalidOTP: function (msg) {
        //console.log(msg);
        //Your code to handle this error
    },
    onRequestTimedOut: function (msg) {
        //console.log(msg);
        //Your code to handle this error
    },
    onSystemError: function (msg) {
        //console.log(msg);
        //Your code to handle this error
    },
    onGenericError: function (msg) {
        //console.log(msg);
        //Your code to handle this error
    },
    onExpiredOTP: function (msg) {
        //console.log(msg);
        //Your code to handle this error
    },
}

//Success Code Mappings
const PGWHPCSuccessMap = {
    onValidSecurityCode: function (msg) {
        //console.log(msg);
        //Your code to handle this success message
    },
    onValidMasterCard: function (msg) {
        //console.log(msg);
        //Your code to handle this success message
    },
    onValidEmail: function (msg) {
        //console.log(msg);
        //Your code to handle this error
    },
    onValidSessionResponse: function (msg) {
        //console.log(msg);
        //Your code to handle this success message
    },
    onPurchaseProcessedViaCard: function (msg) {
        //console.log(msg);
        //Your code to handle this success message
    },
    onPurchaseProcessedViaAccount: function (msg) {
        //console.log(msg);
        //Your code to handle this success message
    },
    onPurchaseProcessedViaWallet: function (msg) {
        //console.log(msg);
        //Your code to handle this success message
    },
    onSuccessPaymentValidation: function (msg) {
        //console.log(msg);
        //Your code to handle this success message
    },
    onSuccessProcessAccountDetails: function (msg) {
        //console.log(msg);
    },
    onWalletTriggered: function (msg) {
        //console.log(msg);
    }
}

function generateValideTXNDateTime() {
    var _dt = new Date();
    var _dtE = new Date();
    var _dtExp = _dtE.setHours(_dtE.getHours() + 24);

    return {
        txndt: returnFormattedDT(_dt),
        txnexp: returnFormattedDT(_dtExp),
    }
}

var _trnDTnEDT = generateValideTXNDateTime();

//Transaction Parameters
const PGWHPCTransactionParameters = {
    __00amt__: "", //amount                           pp_Amount
    __01brn__: "", //bill reference number            pp_BillReference
    __17ced__: "", //ComplainExpiryDays                pp_ComplainExpiryDays
    __15dd__: "", //DeliverydateTime                  pp_DeliverydateTime 
    __02des__: "", //description                      pp_Description
    __16igs__: "", //IsRegisteredCustomer             pp_IsRegisteredCustomer
    __03lan__: "EN", //Language                       pp_Language
    __04mid__: "", //merchant ID                      pp_MerchantID
    __05pwd__: "", //merchant PWD                     pp_Password
    __06url__: "", //return URL                       pp_ReturnURL
    __08seh__: "", //secure #                         pp_SecureHash
    __07smid__: "", //Sub merchant ID                 pp_SubMerchantID
    __09cur__: "PKR", //currency                      pp_TxnCurrency
    __10tdt__: "", //transaction date time            pp_TxnDateTime
    __11edt__: "", //transaction expiry date time     pp_TxnExpiryDateTime
    __12trn__: "", //transaction reference number     pp_TxnRefNo
    __13ver__: "1.1", //version                       pp_Version
    __14fbu__: "", //Fall Back URL incase of user initiated transaction abort  
}

function EscrowEnable(escrow) {
    if (escrow == 0) {

        var mobile = document.querySelectorAll(".pgwhpcfield_MobileNumber_Container");
        mobile.forEach(function (element) {
            element.parentNode.removeChild(element);
        });

        var email = document.querySelectorAll(".pgwhpcfield_EmailAdress_Container");
        email.forEach(function (element) {
            element.parentNode.removeChild(element);
        });

    }
    else {
        var mobile = document.querySelectorAll(".pgwhpcfield_MobileNumber_Container");
        mobile.forEach(function (element) {
            element.style.display = 'block';;
        });
        var email = document.querySelectorAll(".pgwhpcfield_EmailAdress_Container");
        email.forEach(function (element) {
            element.style.display = 'block';;
        });
    }
}

const PGWHPCCard = {
    pgwhpcCARDDIVName: "PGWHPCCARDContainer",
    initialize: function (divID) {
        if (PGWHPCTransactionParameters.__04mid__ == "") {
            PGWHPCErrorMap.onInvalidTransactionDetails("Invalid transaction parameters.");
            return false;
        }
        if (!divID) {
            divID = this.pgwhpcCARDDIVName;
        }

        if (!document.getElementById(divID)) {
            PGWHPCErrorMap.onInvalidContainerArea("Container to populate fields not found.");
            return false;
        }

        document.getElementById(divID).innerHTML = '<div class="pgwhpccardelemscontainer">'
            + '<div class="pgwhpcfield_NameOnCard_Container pgwhpccardfield">'
            + '<label for="pgwhpcfld_NameOnCard">Name On Card</label>'
            + '<input type="text" readonly id="pgwhpcfld_NameOnCard" />'
            + '</div>'

            + '<div class="pgwhpcfield_CardNumber_Container pgwhpccardfield">'
            + '<label for="pgwhpcfld_CardNumber">Card Number</label>'
            + '<input type="text" readonly  id="pgwhpcfld_CardNumber" />'
            + '</div>'

            + '<div class="pgwhpcfield_CardCVV_Container pgwhpccardfield">'
            + '<label for="pgwhpcfld_CardCVV">CVV</label>'
            + '<input type="text" readonly id="pgwhpcfld_CardCVV" />'
            + '</div>'

            + '<div class="pgwhpcfield_CardExpiry_Container pgwhpccardfield">'
            + '<label for="pgwhpcfld_CardExpiryMonth">Card Expiry (MM / YY)</label>'
            + '<input type="text" readonly id="pgwhpcfld_CardExpiryMonth" />'
            + " / "
            + '<input type="text" readonly id="pgwhpcfld_CardExpiryYear" />'
            + '</div>'

            + '<div class="pgwhpcfield_MobileNumber_Container pgwhpcacctfield" id="__pgwhpcfield_MobileNumber_Container__" style="display:none;">'
            + '<label for="pgwhpcaccfld_MobileNumber">Mobile Number</label>'
            + '<input type="text"  id="pgwhpcaccfld_MobileNumber" maxlength=15/>'
            + '</div>'

            + '<div class="pgwhpcfield_EmailAdress_Container pgwhpcacctfield" id="__pgwhpcfield_EmailAdress_Container__" style="display:none;">'
            + '<label for="pgwhpcaccfld_EmailAdress">Email Address</label>'
            + '<input type="text" id="pgwhpcaccfld_EmailAdress"/>'
            + '</div>'
            + '</div>'

            + '<input type="button" id="__btnProcessCard__" class="btn_Process_Card" onclick="PGWHPCCard.processCard(event)" value="Initializing..." disabled />';

        if (self === top) {
            var antiClickjack = document.getElementById("antiClickjack");
            antiClickjack.parentNode.removeChild(antiClickjack);
        } else {
            top.location = self.location;
        }

        document.getElementById("pgwhpcaccfld_MobileNumber").addEventListener("input", function (e) {
            var _val = e.target.value;
            e.target.value = _val.replace(/[^0-9]/gi, "");
        });

        document.getElementById("pgwhpcaccfld_EmailAdress").addEventListener("keyup", function (e) {
            var _val = e.target.value;
            var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
            if (reg.test(_val) == false) {
                PGWHPCErrorMap.onInvalidEmail("The email you entered is invalid");
                return false;
            }
            else {
                PGWHPCSuccessMap.onValidEmail("The email you entered is valid");
            }
        });

        initializeHPSElements();

        if (!PGWHPCTokenRequested) {
            generateRequestToken();
        }
    },
    processCard: function (e) {
        e.target.setAttribute("disabled", true);
        e.target.setAttribute("value", "Processing...");
        // UPDATE THE SESSION WITH THE INPUT FROM HOSTED FIELDS
        PaymentSession.updateSessionFromForm('card');

        var _mobile = document.getElementById("pgwhpcaccfld_MobileNumber");
        var _email = document.getElementById("pgwhpcaccfld_EmailAdress");
        var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;

        var _valid = true;

        if (_mobile && _email != null && _email !== undefined) {
            if ((_mobile.value == "") || (_email.value == "")) {
                _valid = true;
            }

            if ((reg.test(_email.value) == false) && (_email.value != "")) {
                PGWHPCErrorMap.onInvalidEmail("Invalid Email Address");
                _valid = false;
            }
            else {
                PGWHPCSuccessMap.onValidEmail("The email you entered is valid");
            }

            if ((_mobile.value == "") && (_email.value == "")) {
                PGWHPCErrorMap.onInvalidEmail("Either Provide an email or a mobile number");
                _valid = false;
            }
            if (_mobile.value !== "") {
                if (_mobile.value.length < 6) {
                    PGWHPCErrorMap.onInvalidMobile("Invalid Mobile Number");
                    _valid = false;
                }

            }
        }
    }
}

const PGWHPCAcct = {
    pgwhpcACCTDIVName: "PGWHPCACCTContainer",
    initialize: function (divID) {
        if (PGWHPCTransactionParameters.__04mid__ == "") {
            PGWHPCErrorMap.onInvalidTransactionDetails("Invalid transaction parameters.");
            return false;
        }
        if (!divID) {
            divID = this.pgwhpcACCTDIVName;
        }

        if (!document.getElementById(divID)) {
            PGWHPCErrorMap.onInvalidContainerArea("Container to populate fields not found.");
            return false;
        }

        document.getElementById(divID).innerHTML = "";
        var html = '<div class="pgwhpcacctelemscontainer">'

            + '<div class="pgwhpcfield_Bankt_Container pgwhpcacctfield">'
            + '<label for="pgwhpcfld_Bank">Bank</label>'
            + '<select id="pgwhpcfld_Bank"><option value="">Select</option></select>'
            + '</div>'

            + '<div class="pgwhpcfield_AccountNumber_Container pgwhpcacctfield">'
            + '<label for="pgwhpcfld_AccountNumber">Account Number</label>'
            + '<input type="text" id="pgwhpcfld_AccountNumber" maxlength=20 />'
            + '</div>';


        html += '<div class="pgwhpcfield_MobileNumber_Container pgwhpcacctfield" id="__pgwhpcfield_MobileNumber_Container__" style="display:none;">'
            + '<label for="pgwhpcaccfld_MobileNumber">Mobile Number</label>'
            + '<input type="text" id="pgwhpcaccfld_MobileNumber" minlength=6 maxlength=15/>'
            + '</div>'

            + '<div class="pgwhpcfield_EmailAdress_Container pgwhpcacctfield"  id="__pgwhpcfield_EmailAdress_Container__" style="display:none;">'
            + '<label for="pgwhpcaccfld_EmailAdress">Email Address</label>'
            + '<input type="text" id="pgwhpcaccfld_EmailAdress" />'
            + '</div>';

        html += '<div class="pgwhpcfield_CNIC_Container pgwhpcacctfield">'
            + '<label for="pgwhpcfld_CNIC">CNIC</label>'
            + '<input type="text" id="pgwhpcfld_CNIC" maxlength=13 />'
            + '</div>'

            + '<div class="pgwhpcfield_OTP_Container pgwhpcacctfield" style="display: none;" id="__pgwhpcfield_OTP_Container__">'
            + '<label for="pgwhpcfld_OTP">OTP</label>'
            + '<input type="text" id="pgwhpcfld_OTP" maxlength=6 />'
            + ' &nbsp; '
            + '<input type="button" id="btn_Resend_OTP" value="Resend OTP" onclick="processAccountDetails()" disabled />'
            + '</div>'

            + '</div>'
            + '<input type="button" id="__btnProcessAcct__" class="btn_Process_Acct" onclick="PGWHPCAcct.processAcct(event)" value="Initializing..." disabled />';

        document.getElementById(divID).innerHTML = html;
        document.getElementById("pgwhpcfld_CNIC").addEventListener("input", function (e) {
            var _val = e.target.value;
            e.target.value = _val.replace(/[^0-9]/gi, "");
        });

        document.getElementById("pgwhpcaccfld_MobileNumber").addEventListener("input", function (e) {
            var _val = e.target.value;
            e.target.value = _val.replace(/[^0-9]/gi, "");
        });

        document.getElementById("pgwhpcfld_OTP").addEventListener("input", function (e) {
            var _val = e.target.value;
            e.target.value = _val.replace(/[^0-9]/gi, "");
        });

        document.getElementById("pgwhpcaccfld_EmailAdress").addEventListener("keyup", function (e) {
            var _val = e.target.value;
            // e.target.value = _val.replace( /^\w+([\.-]?\w+)*@@\w+([\.-]?\w+)*(\.\w{2,3})+$/gi, "");
            var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
            if (reg.test(_val) == false) {
                PGWHPCErrorMap.onInvalidEmail("The email you entered is invalid");
                return false;
            }
            else {
                PGWHPCSuccessMap.onValidEmail("The email you entered is valid");
            }
        });
        if (!PGWHPCTokenRequested) {
            generateRequestToken();
        }
    },
    processAcct: function (e) {
        e.target.setAttribute("disabled", true);
        e.target.setAttribute("value", "Processing...");
        // Send Account Details to Bank for OTP

        var _cnic = document.getElementById("pgwhpcfld_CNIC");
        var _cntc = document.getElementById("pgwhpcaccfld_MobileNumber")
        var _bank = document.getElementById("pgwhpcfld_Bank");
        var _acct = document.getElementById("pgwhpcfld_AccountNumber");
        var _emptc = document.getElementById("pgwhpcaccfld_EmailAdress");

        var _valid = true;
        var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;

        if (PGWHPCOTPRequestSent) {
            var _otp = document.getElementById("pgwhpcfld_OTP");
            if (_otp.value == "") {
                PGWHPCErrorMap.onInvalidOTP("Invalid OTP");
                _valid = false;
            } else if (_otp.value.length < 5) {
                PGWHPCErrorMap.onInvalidOTP("Invalid OTP");
                _valid = false;
            } else {
                payViaAccount();
                return;
            }
        }

        if (_cntc && _emptc != null && _emptc !== undefined) {

            if ((_cntc.value == "") || (_emptc.value == "")) {
                _valid = true;
            }

            if ((reg.test(_emptc.value) == false) && (_emptc.value != "")) {
                PGWHPCErrorMap.onInvalidEmail("Invalid Email Address");
                _valid = false;
            }

            if ((_cntc.value == "") && (_emptc.value == "")) {
                PGWHPCErrorMap.onInvalidMobile("Either Provide an email or a mobile number");
                _valid = false;
            }
            if (_cntc.value !== "") {
                if (_cntc.value.length < 6) {
                    PGWHPCErrorMap.onInvalidMobile("Invalid Mobile Number");
                    _valid = false;
                }

            }
        }

        if (isNaN(_cnic.value)) {
            PGWHPCErrorMap.onInvalidCNIC("Invalid CNIC");
            _valid = false;
        } else if (_cnic.value.length != 13) {
            PGWHPCErrorMap.onInvalidCNIC("Invalid CNIC");
            _valid = false;
        }

        if (_bank.value == "") {
            PGWHPCErrorMap.onInvalidBank("Invalid Bank");
            _valid = false;
        }


        if (isNaN(_acct.value)) {
            PGWHPCErrorMap.onInvalidAccount("Invalid Account Number");
            _valid = false;
        } else if (_acct.value.length > 20) {
            PGWHPCErrorMap.onInvalidAccount("Invalid Account Number");
            _valid = false;
        }

        if (_valid) {
            if (_cntc && _emptc != null && _emptc !== undefined) {
                _emptc.setAttribute("readonly", true);
                _cntc.setAttribute("readonly", true);
            }

            processAccountDetails();
        } else {
            enableProcessButton();
        }
    }
}
const PGWHPCWall = {
    pgwhpcWALLTDIVName: "PGWHPCWALLTContainer",
    initialize: function (divID) {
        if (PGWHPCTransactionParameters.__04mid__ == "") {
            PGWHPCErrorMap.onInvalidTransactionDetails("Invalid transaction parameters.");
            return false;
        }
        if (!divID) {
            divID = this.pgwhpcWALLTDIVName;
        }

        if (!document.getElementById(divID)) {
            PGWHPCErrorMap.onInvalidContainerArea("Container to populate fields not found.");
            return false;
        }

        document.getElementById(divID).innerHTML = '<div class="pgwhpcwalletcontainer">'

            + '<div class="pgwhpcfield_Wallet_MobileNumber_Container pgwhpcwalletfield" id="__pgwhpcfield_MobileNumber_Container__">'
            + '<label for="pgwhpcfld_MobileNumber">Mobile Number</label>'
            + '<input type="text" id="pgwhpcaccfld_MobileNumber" maxlength=11/>'
            + '</div>'

            + '<div class="pgwhpcfield_EmailAdress_Container pgwhpcacctfield" id="__pgwhpcfield_EmailAdress_Container__" style="display:none;">'
            + '<label for="pgwhpcfld__EmailAdress">Email Address</label>'
            + '<input type="text" id="pgwhpcaccfld_EmailAdress"/>'
            + '</div>'

            + '<div class="pgwhpcfield_CNIC_Container pgwhpcwalletfield">'
            + '<label for="pgwhpcwalletfld_CNIC">CNIC</label>'
            + '<input type="text" id="pgwhpcwalletfld_CNIC" maxlength=13 />'
            + '</div>'

            + '</div>'
            + '<input type="button" id="__btnProcessWallet__" class="btn_Process_Wallet" onclick="PGWHPCWall.walletfundtransfer(event)" value="Initializing..." disabled />';


        document.getElementById("pgwhpcwalletfld_CNIC").addEventListener("input", function (e) {
            var _val = e.target.value;
            e.target.value = _val.replace(/[^0-9]/gi, "");
        });



        document.getElementById("pgwhpcaccfld_MobileNumber").addEventListener("input", function (e) {
            var _val = e.target.value;
            e.target.value = _val.replace(/[^0-9]/gi, "");
        });

        document.getElementById("pgwhpcaccfld_EmailAdress").addEventListener("keyup", function (e) {
            var _val = e.target.value;
            var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
            if (reg.test(_val) == false) {
                PGWHPCErrorMap.onInvalidEmail("The email you entered is invalid");
                return false;
            }
            else {
                PGWHPCSuccessMap.onValidEmail("The email you entered is valid");
            }
        });

        if (!PGWHPCTokenRequested) {
            generateRequestToken();
        }
    },
    walletfundtransfer: function (e) {
        e.target.setAttribute("disabled", true);
        e.target.setAttribute("value", "Processing...");
        // Send Account Details to Bank for OTP

        var _cnic = document.getElementById("pgwhpcwalletfld_CNIC");
        var _mobile = document.getElementById("pgwhpcaccfld_MobileNumber");
        var _email = document.getElementById("pgwhpcaccfld_EmailAdress");
        var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;

        var _valid = true;

        if (_mobile && _email != null && _email !== undefined) {
            if ((_mobile.value == "") || (_email.value == "")) {
                _valid = true;
            }

            if ((reg.test(_email.value) == false) && (_email.value != "")) {
                PGWHPCErrorMap.onInvalidEmail("Invalid Email Address");
                _valid = false;
            }

            if ((_mobile.value == "") && (_email.value == "")) {
                PGWHPCErrorMap.onInvalidEmail("Either Provide an email or a mobile number");
                _valid = false;
            }
            if (_mobile.value !== "") {
                if (_mobile.value.length < 6) {
                    PGWHPCErrorMap.onInvalidMobile("Invalid Mobile Number");
                    _valid = false;
                }

            }
        }


        if (isNaN(_cnic.value)) {
            PGWHPCErrorMap.onInvalidCNIC("Invalid CNIC");
            _valid = false;
        } else if (_cnic.value.length != 13) {
            PGWHPCErrorMap.onInvalidCNIC("Invalid CNIC");
            _valid = false;
        }


        if (_valid) {
            walletfundtransfer();
            if (_mobile && _email != null && _email !== undefined) {
                _mobile.setAttribute("readonly", true);
                _cnic.setAttribute("readonly", true);
            }
        } else {
            enableProcessButton();
        }
    }
}
//Generic functions start
function enableProcessButton() {
    let btnAcc = document.getElementById("__btnProcessAcct__");
    let btnCard = document.getElementById("__btnProcessCard__");
    let btnWallet = document.getElementById("__btnProcessWallet__");

    if (btnAcc && btnAcc != null && btnAcc !== undefined) {
        document.getElementById("__btnProcessAcct__").removeAttribute("disabled");
        document.getElementById("__btnProcessAcct__").setAttribute("value", "Submit");
    }

    if (btnCard && btnCard != null && btnCard !== undefined) {
        document.getElementById("__btnProcessCard__").removeAttribute("disabled");
        document.getElementById("__btnProcessCard__").setAttribute("value", "Submit");
    }

    if (btnWallet && btnWallet != null && btnWallet !== undefined) {
        document.getElementById("__btnProcessWallet__").removeAttribute("disabled");
        document.getElementById("__btnProcessWallet__").setAttribute("value", "Submit");
    }
}

function returnFormattedDT(_dt) {
    _dt = new Date(_dt);
    var _mnth = _dt.getMonth() < 9 ? "0" + Math.abs(_dt.getMonth() + 1) : Math.abs(_dt.getMonth() + 1);
    var _date = _dt.getDate() < 10 ? "0" + _dt.getDate() : _dt.getDate();
    var _hour = _dt.getHours() < 10 ? "0" + _dt.getHours() : _dt.getHours();
    var _mint = _dt.getMinutes() < 10 ? "0" + _dt.getMinutes() : _dt.getMinutes();
    var _secs = _dt.getSeconds() < 10 ? "0" + _dt.getSeconds() : _dt.getSeconds();
    var _formattedDT = _dt.getFullYear() + "" + _mnth + "" + _date + "" + _hour + "" + _mint + "" + _secs;
    return _formattedDT;
}

function generateRequestToken() { //Create Token
    PGWHPCTokenRequested = true;
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {

        if (this.readyState == 4 && this.status == 200) {
            var _data = this;
            var _respKong = JSON.parse(_data.response);
            PGWHPCRequestToken = _respKong.access_token;
            validatePaymentDetails();
        }
    };

    xhttp.open("POST", PGWHPCTokenURL, true);
    xhttp.setRequestHeader("Content-Type", "application/json");
    xhttp.setRequestHeader("Authorization", "Basic " + PGWHPCAuthToken);
    xhttp.send(JSON.stringify({
        "grant_type": "client_credentials",
    }));
}

function validatePaymentDetails() { //Call Payment Validation API
    var xhttp = new XMLHttpRequest();
    var _url = PGWHPCTransactionURL + "v2/transaction/paymentvalidation";
    xhttp.onreadystatechange = function () {
        var _data = this;
        if (this.readyState == 4 && this.status == 200) {
            var _responseObj = JSON.parse(_data.response);
            IsEscrowEnable = _responseObj.isEscrowEnable;
            EscrowEnable(IsEscrowEnable);
            PGWHPCCtxnkey = _responseObj.transactionKey;
            let pgwhpcfld_Bank = document.getElementById("pgwhpcfld_Bank")
            if (pgwhpcfld_Bank) {
                for (var ab = 0; ab < _responseObj.allowedBanks.length; ab++) {
                    document.getElementById("pgwhpcfld_Bank").innerHTML += '<option value="' + _responseObj.allowedBanks[ab].bankCode + '">' + _responseObj.allowedBanks[ab].bankName + '</option>';
                }
            }
            PGWHPCSuccessMap.onSuccessPaymentValidation("PaymentValidation_Success");
            enableProcessButton();
        }
        else if (this.readyState == 4 && this.status == 400) {
            //PGWHPCErrorMap.onRequestTimedOut(JSON.parse(_data.response));
            genericResponseHandler(JSON.parse(_data.response), "TO");
        }
        else if (this.readyState == 4 && this.status == 500) {
            //PGWHPCErrorMap.onSystemError(JSON.parse(_data.response));
            genericResponseHandler(JSON.parse(_data.response), "SE");
        }
        else if (this.readyState == 4 && this.status == 503) {
            //PGWHPCErrorMap.onSystemError(JSON.parse(_data.response));
            genericResponseHandler(JSON.parse(_data.response), "SE");
        }
        else if (this.readyState == 4) {
            //PGWHPCErrorMap.onSystemError(JSON.parse(_data.response));
            genericResponseHandler(JSON.parse(_data.response), "GE");
        }
    };

    xhttp.open("POST", _url, true);
    xhttp.setRequestHeader("Content-Type", "application/json");
    xhttp.setRequestHeader("Authorization", "Bearer " + PGWHPCRequestToken);
    xhttp.send(JSON.stringify({
        "pp_Amount": PGWHPCTransactionParameters.__00amt__,
        "pp_BillReference": PGWHPCTransactionParameters.__01brn__,
        "pp_Description": PGWHPCTransactionParameters.__02des__,
        "pp_Language": PGWHPCTransactionParameters.__03lan__,
        "pp_MerchantID": PGWHPCTransactionParameters.__04mid__,
        "pp_Password": PGWHPCTransactionParameters.__05pwd__,
        "pp_ReturnURL": PGWHPCTransactionParameters.__06url__,
        "pp_SubMerchantID": PGWHPCTransactionParameters.__07smid__,
        "pp_TxnCurrency": PGWHPCTransactionParameters.__09cur__,
        "pp_TxnDateTime": PGWHPCTransactionParameters.__10tdt__, // _trnDTnEDT.txndt.toString(),
        "pp_TxnExpiryDateTime": PGWHPCTransactionParameters.__11edt__, //_trnDTnEDT.txnexp.toString(),
        "pp_TxnRefNo": PGWHPCTransactionParameters.__12trn__,
        "pp_Version": PGWHPCTransactionParameters.__13ver__,
        "pp_SecureHash": PGWHPCTransactionParameters.__08seh__,
        "usageMode": "HPC",
        "pp_BankID": "",
        "pp_TxnType": "",
        "pp_DeliverydateTime": PGWHPCTransactionParameters.__15dd__,
        "pp_IsRegisteredCustomer": PGWHPCTransactionParameters.__16igs__,
        "pp_ComplainExpiryDays": PGWHPCTransactionParameters.__17ced__,
    }));
}
//Generic functions end


//Card functions start
function initializeHPSElements() {
    PaymentSession.configure({
        fields: {
            // ATTACH HOSTED FIELDS TO YOUR PAYMENT PAGE FOR A CREDIT CARD
            card: {
                number: "#pgwhpcfld_CardNumber",
                securityCode: "#pgwhpcfld_CardCVV",
                expiryMonth: "#pgwhpcfld_CardExpiryMonth",
                expiryYear: "#pgwhpcfld_CardExpiryYear",
                nameOnCard: "#pgwhpcfld_NameOnCard",
            }
        },
        //SPECIFY YOUR MITIGATION OPTION HERE
        frameEmbeddingMitigation: ["javascript"],
        callbacks: {
            initialized: function (response) {
                // HANDLE INITIALIZATION RESPONSE
            },
            formSessionUpdate: function (response) {
                // HANDLE RESPONSE FOR UPDATE SESSION
                if (response.status) {
                    if ("ok" == response.status) {

                        //console.log("Session updated with data: " + response.session.id);
                        PGWHPCCARDSessionID = response.session.id;
                        PGWHPCSuccessMap.onValidSessionResponse("Session updated with data: " + response.session.id);
                        check3DS();

                        //check if the security code was provided by the user
                        if (response.sourceOfFunds.provided.card.securityCode) {
                            //console.log("Security code was provided.");
                            PGWHPCSuccessMap.onValidSecurityCode("Security code was provided.");
                        }

                        //check if the user entered a Mastercard credit card
                        if (response.sourceOfFunds.provided.card.scheme == 'MASTERCARD') {
                            //console.log("The user entered a Mastercard credit card.");
                            PGWHPCSuccessMap.onValidMasterCard("The user entered a Mastercard credit card.");
                        }

                    } else if ("fields_in_error" == response.status) {

                        //console.log("Session update failed with field errors.");
                        if (response.errors.cardNumber) {
                            //console.log("Card number invalid or missing.");
                            enableProcessButton();
                            PGWHPCErrorMap.onInvalidCardNumber("Card number invalid or missing.");
                        }
                        if (response.errors.expiryYear) {
                            //console.log("Expiry year invalid or missing.");
                            enableProcessButton();
                            PGWHPCErrorMap.onInvalidExpiryYear("Expiry year invalid or missing.")
                        }
                        if (response.errors.expiryMonth) {
                            //console.log("Expiry month invalid or missing.");
                            enableProcessButton();
                            PGWHPCErrorMap.onInvalidExpiryMonth("Expiry month invalid or missing.");
                        }
                        if (response.errors.securityCode) {
                            //console.log("Security code invalid.");
                            enableProcessButton();
                            PGWHPCErrorMap.onInvalidSecurityCode("Security code invalid.");
                        }
                        if (response.errors.customerMobile) {
                            //console.log("Security code invalid.");
                            enableProcessButton();
                            PGWHPCErrorMap.onInvalidMobile("Mobile invalid.");
                        }
                        if (response.errors.customerEmail) {
                            //console.log("Security code invalid.");
                            enableProcessButton();
                            PGWHPCErrorMap.onInvalidEmail("Email invalid.");
                        }
                    } else if ("request_timeout" == response.status) {
                        //console.log("Session update failed with request timeout: " + response.errors.message);
                        PGWHPCErrorMap.onRequestTimedOut("Session update failed with request timeout: " + response.errors.message);
                    } else if ("system_error" == response.status) {
                        //console.log("Session update failed with system error: " + response.errors.message);
                        PGWHPCErrorMap.onSystemError("Session update failed with system error: " + response.errors.message);
                    }
                } else {
                    //console.log("Session update failed: " + response);
                    PGWHPCErrorMap.onGenericError("Session update failed: " + response);
                }
            }
        },
        interaction: {
            displayControl: {
                formatCard: "EMBOSSED",
                invalidFieldCharacters: "REJECT"
            }
        }
    });
}

function check3DS() {
    var xhttp = new XMLHttpRequest();
    var _url = PGWHPCTransactionURL + "v2/transaction/check3dsenrollment";

    xhttp.onreadystatechange = function () {
        var _data = this;
        //console.log(_data);
        if (this.readyState == 4 && this.status == 200) {
            PGWHPC3DSecureID = JSON.parse(_data.response).c3dSecureID;
            if (JSON.parse(_data.response).aR_Simple_Html) {
                openWindow(JSON.parse(_data.response).aR_Simple_Html);
            } else {
                cardPurchase();
            }
        }
        else if (this.readyState == 4 && this.status == 400) {
            //PGWHPCErrorMap.onRequestTimedOut(JSON.parse(_data.response));
            genericResponseHandler(JSON.parse(_data.response), "TO");
        }
        else if (this.readyState == 4 && this.status == 500) {
            //PGWHPCErrorMap.onSystemError(JSON.parse(_data.response));
            genericResponseHandler(JSON.parse(_data.response), "SE");
        }
        else if (this.readyState == 4 && this.status == 503) {
            //PGWHPCErrorMap.onSystemError(JSON.parse(_data.response));
            genericResponseHandler(JSON.parse(_data.response), "SE");
        }
        else if (this.readyState == 4) {
            //PGWHPCErrorMap.onSystemError(JSON.parse(_data.response));
            genericResponseHandler(JSON.parse(_data.response), "GE");
        }
    };

    xhttp.open("POST", _url, true);
    xhttp.setRequestHeader("Content-Type", "application/json");
    xhttp.setRequestHeader("Authorization", "Bearer " + PGWHPCRequestToken);
    xhttp.send(JSON.stringify({
        "rrn": PGWHPCTransactionParameters.__12trn__,
        "amount": PGWHPCTransactionParameters.__00amt__,
        "currency": "586",
        "sessionId": PGWHPCCARDSessionID,
        "merchantID": PGWHPCTransactionParameters.__04mid__,
        "subMerchantID": PGWHPCTransactionParameters.__07smid__,
        "password": PGWHPCTransactionParameters.__05pwd__,
        "OTP": "",
        "secureHash": PGWHPCTransactionParameters.__08seh__,
        "transmissionDateTime": PGWHPCTransactionParameters.__10tdt__, //_trnDTnEDT.txndt.toString(),
        "usageMode": "HPC",
        "isChild": "true",
    }));
}

function openWindow(data) {
    //PGWHPCIntervalVar = setInterval(function () {
    //  if (PGWHPC3DSPopup.closed === true) {
    //    PGWHPCErrorMap.onGenericError("User aborted 3DS verification.");
    //   clearInterval(PGWHPCIntervalVar);
    //}
    //}, 500);
    var div3DS = document.createElement("div");
    div3DS.setAttribute("id", "iframeDiv3DS");
    document.body.append(div3DS);
    var iframe3DS = document.getElementById("iframeDiv3DS");
    iframe3DS.innerHTML = "<div style='width: 100%; height: 100vh; background: rgba(0, 0, 0, 0.53); position: fixed; left: 0px; top: 0px; z-index: 99999999999999999;'>"
        + "<div id='iframe3DSContent' style='width: 400px; height: 320px; margin-top: calc(35% - 320px); margin-left: auto; margin-right: auto; background: #fff;'>"
        + "<iframe id='fr1' name='fr1' border=0 width='100%' height='100%'></iframe>"
        + "</div></div>";

    var PGWHPC3DSPopup = document.getElementById("fr1");
    PGWHPC3DSPopup.contentWindow.document.write(data);
    //PGWHPC3DSPopup.document.write(data);
    window.removeEventListener('message', processACS_3DS, false);
    window.addEventListener('message', processACS_3DS, false);
    PGWHPC3DSPopup.contentWindow.document.forms[name = 'echoForm'].submit();
}

function processACS_3DS(pares) {
    clearInterval(PGWHPCIntervalVar);

    setTimeout(function () {
        //PGWHPC3DSPopup.close();
        document.getElementById("iframeDiv3DS").remove();
    }, 500);

    var _recvdPaRes = JSON.parse(pares.data).Data.paRes;

    var xhttp = new XMLHttpRequest();
    var _url = PGWHPCTransactionURL + "v2/transaction/processacs";

    xhttp.onreadystatechange = function () {
        var _data = this;
        if (this.readyState == 4 && this.status == 200) {
            cardPurchase();
        }
        else if (this.readyState == 4) {
            //PGWHPCErrorMap.onSystemError(JSON.parse(_data.response));
            genericResponseHandler(JSON.parse(_data.response), "GE");
        }
    };

    xhttp.open("POST", _url, true);
    xhttp.setRequestHeader("Content-Type", "application/json");
    xhttp.setRequestHeader("Authorization", "Bearer " + PGWHPCRequestToken);
    xhttp.send(JSON.stringify({
        "rrn": PGWHPCTransactionParameters.__12trn__,
        "merchantID": PGWHPCTransactionParameters.__04mid__,
        "subMerchantID": PGWHPCTransactionParameters.__07smid__,
        "password": PGWHPCTransactionParameters.__05pwd__,
        "secureHash": PGWHPCTransactionParameters.__08seh__,
        "3DSecureID": PGWHPC3DSecureID,
        "isChild": "true",
        "expiryDateTime": PGWHPCTransactionParameters.__11edt__,//_trnDTnEDT.txnexp.toString(),
        "paRes": _recvdPaRes,
        "usageMode": "HPC",
        "transmissionDateTime": PGWHPCTransactionParameters.__10tdt__// _trnDTnEDT.txndt.toString(), //PGWHPCTransactionParameters.__09tdt__,
    }));

}

function cardPurchase() {
    var xhttp = new XMLHttpRequest();
    var _url = PGWHPCTransactionURL + "v2/transaction/purchase";

    xhttp.onreadystatechange = function () {
        var _data = this;
        if (this.readyState == 4 && this.status == 200) {
            // PGWHPCSuccessMap.onPurchaseProcessedViaCard(_data);
            if (IsEscrowEnable == "1") {
                InfoResponseHandler();
            }

            genericResponseHandler(JSON.parse(_data.response), "SC");
            document.getElementById("__btnProcessCard__").value = "Completed";
        }
        else if (this.readyState == 4 && this.status == 400) {
            //PGWHPCErrorMap.onRequestTimedOut(JSON.parse(_data.response));
            genericResponseHandler(JSON.parse(_data.response), "TO");
        }
        else if (this.readyState == 4 && this.status == 500) {
            //PGWHPCErrorMap.onSystemError(JSON.parse(_data.response));
            genericResponseHandler(JSON.parse(_data.response), "SE");
        }
        else if (this.readyState == 4 && this.status == 503) {
            //PGWHPCErrorMap.onSystemError(JSON.parse(_data.response));
            genericResponseHandler(JSON.parse(_data.response), "SE");
        }
        else if (this.readyState == 4) {
            //PGWHPCErrorMap.onSystemError(JSON.parse(_data.response));
            genericResponseHandler(JSON.parse(_data.response), "GE");
        }
    };

    xhttp.open("POST", _url, true);
    xhttp.setRequestHeader("Content-Type", "application/json");
    xhttp.setRequestHeader("Authorization", "Bearer " + PGWHPCRequestToken);
    xhttp.send(JSON.stringify({
        "rrn": PGWHPCTransactionParameters.__12trn__,
        "amount": PGWHPCTransactionParameters.__00amt__,
        "currency": "586",
        "sessionId": PGWHPCCARDSessionID,
        "merchantID": PGWHPCTransactionParameters.__04mid__,
        "subMerchantID": PGWHPCTransactionParameters.__07smid__,
        "password": PGWHPCTransactionParameters.__05pwd__,
        "OTP": "",
        "invoiceNo": "",
        "secureHash": "",
        "p3DSecureID": PGWHPC3DSecureID,
        "transmissionDateTime": PGWHPCTransactionParameters.__10tdt__,//_trnDTnEDT.txndt.toString(),
        "usageMode": "HPC",
        "isChild": "true",
    }));
}
//Card functions end


//Account functions start
function processAccountDetails() {
    document.getElementById("btn_Resend_OTP").setAttribute("disabled", true);
    document.getElementById("__btnProcessAcct__").setAttribute("disabled", true);
    document.getElementById("pgwhpcfld_CNIC").setAttribute("readonly", true);
    document.getElementById("pgwhpcfld_Bank").setAttribute("readonly", true);
    document.getElementById("pgwhpcfld_AccountNumber").setAttribute("readonly", true);

    var xhttp = new XMLHttpRequest();
    var _url = PGWHPCTransactionURL + "v2/transaction/sendotp";

    xhttp.onreadystatechange = function () {
        var _data = this;
        if (this.readyState == 4) {
            if (this.status == 200) {
                PGWHPCOTPRequestSent = true;
                var _counter = PGWHPCResendOTPDelay;
                PGWHPCResendOTPTimer = setInterval(function () {
                    if (_counter > 0) {
                        document.getElementById("btn_Resend_OTP").value = "Resend In: " + _counter;
                        _counter -= 1;
                    } else {
                        _counter = PGWHPCResendOTPDelay;
                        clearInterval(PGWHPCResendOTPTimer);
                        document.getElementById("btn_Resend_OTP").value = "Resend OTP";
                        document.getElementById("btn_Resend_OTP").removeAttribute("disabled");
                    }
                }, 1000);
                if (JSON.parse(_data.response).responseCode == "058") {
                    //PGWHPCErrorMap.onRequestTimedOut(JSON.parse(_data.response));
                    genericResponseHandler(JSON.parse(_data.response), "TO");
                    return;
                }
                document.getElementById("__pgwhpcfield_OTP_Container__").removeAttribute("style");
                enableProcessButton();
                document.getElementById("__btnProcessAcct__").removeAttribute("disabled");
                document.getElementById("__btnProcessAcct__").value = "Pay via Account";
                PGWHPCSuccessMap.onSuccessProcessAccountDetails(JSON.parse(_data.response));
            }

            else if (this.status == 400) {
                //PGWHPCErrorMap.onRequestTimedOut(JSON.parse(_data.response));
                genericResponseHandler(JSON.parse(_data.response), "TO");
            }
            else if (this.status == 500) {
                //PGWHPCErrorMap.onSystemError(JSON.parse(_data.response));
                genericResponseHandler(JSON.parse(_data.response), "SE");
            }

            else if (this.status == 403) {
                if (JSON.parse(_data.response).responseCode == "003") {
                    enableProcessButton();
                    PGWHPCErrorMap.onInvalidAccount(JSON.parse(_data.response));
                    document.getElementById("pgwhpcfld_CNIC").readOnly = false;
                    document.getElementById("pgwhpcfld_AccountNumber").readOnly = false;
                }
                else {
                    //PGWHPCErrorMap.onInvalidAccount(JSON.parse(_data.response));
                    genericResponseHandler(JSON.parse(_data.response), "IA");
                }
            }
            else if (this.status == 503) {
                //PGWHPCErrorMap.onSystemError(JSON.parse(_data.response));
                genericResponseHandler(JSON.parse(_data.response), "SE");
                enableProcessButton();
            }
            else {
                //PGWHPCErrorMap.onGenericError(JSON.parse(_data.response));
                genericResponseHandler(JSON.parse(_data.response), "GE");
            }
        }
    };

    xhttp.open("POST", _url, true);
    xhttp.setRequestHeader("Content-Type", "application/json");
    xhttp.setRequestHeader("Authorization", "Bearer " + PGWHPCRequestToken);
    xhttp.send(JSON.stringify({
        txnTypeCode: "ACC",
        merchantRefNo: PGWHPCTransactionParameters.__12trn__,
        amount: PGWHPCTransactionParameters.__00amt__,
        merchantCode: PGWHPCTransactionParameters.__04mid__,
        subMerchantCode: PGWHPCTransactionParameters.__07smid__,
        currency: "586",
        nationalId: document.getElementById("pgwhpcfld_CNIC").value,
        // contactNo: document.getElementById("pgwhpcaccfld_MobileNumber").value,
        bankCode: document.getElementById("pgwhpcfld_Bank").value,
        relationshipId: document.getElementById("pgwhpcfld_AccountNumber").value,
        password: PGWHPCTransactionParameters.__05pwd__,
        cvv: "",
        transmissionDateTime: PGWHPCTransactionParameters.__10tdt__ //_trnDTnEDT.txndt.toString(),
    }));
}

function payViaAccount() {
    document.getElementById("btn_Resend_OTP").setAttribute("disabled", true);
    document.getElementById("btn_Resend_OTP").value = "Resend OTP";

    document.getElementById("__btnProcessAcct__").setAttribute("disabled", true);
    document.getElementById("__btnProcessAcct__").value = "Processing...";

    var _cntc = document.getElementById("pgwhpcaccfld_MobileNumber")
    var _emptc = document.getElementById("pgwhpcaccfld_EmailAdress");

    var xhttp = new XMLHttpRequest();
    var _url = PGWHPCTransactionURL + "v2/transaction/accountpayment";

    xhttp.onreadystatechange = function () {
        var _data = this;
        if (this.readyState == 4) {
            if (this.status == 200) {

                if (JSON.parse(_data.response).responseCode == "000") {

                    if (_cntc && _emptc != null && _emptc !== undefined) {
                        if (IsEscrowEnable == "1") {
                            InfoResponseHandler();
                        }
                    }
                }
                if (JSON.parse(_data.response).responseCode == "058") {
                    // console.log(_data.response)
                    //PGWHPCErrorMap.onRequestTimedOut(JSON.parse(_data.response));
                    genericResponseHandler(JSON.parse(_data.response), "TO");
                    return;
                }

                if (JSON.parse(_data.response).responseCode == "161") {
                    //PGWHPCErrorMap.onExpiredOTP(JSON.parse(_data.response));
                    genericResponseHandler(JSON.parse(_data.response), "EO");
                    return;
                }
                // else{
                // PGWHPCErrorMap.onSystemError(JSON.parse(_data.response));
                // }
                // PGWHPCSuccessMap.onPurchaseProcessedViaAccount(_data);
                genericResponseHandler(JSON.parse(_data.response), "SA");

                clearInterval(PGWHPCResendOTPTimer);
                //Button status
                document.getElementById("btn_Resend_OTP").style.display = "none";
                document.getElementById("__btnProcessAcct__").value = "Completed";

            }
            else if (this.status == 400) {
                if (JSON.parse(_data.response).responseCode == "178" || JSON.parse(_data.response).responseCode == "145") {
                    PGWHPCErrorMap.onInvalidOTP(JSON.parse(_data.response));
                    document.getElementById("__btnProcessAcct__").removeAttribute("disabled");
                    document.getElementById("__btnProcessAcct__").value = "Pay via Account";
                    return;
                }
                if (JSON.parse(_data.response).responseCode == "058") {
                    //PGWHPCErrorMap.onRequestTimedOut(JSON.parse(_data.response));
                    genericResponseHandler(JSON.parse(_data.response), "TO");
                    return;
                }
            }
            else if (this.status == 500) {
                //PGWHPCErrorMap.onSystemError(JSON.parse(_data.response));
                genericResponseHandler(JSON.parse(_data.response), "SE");
            }
            else if (this.status == 503) {
                //PGWHPCErrorMap.onSystemError(JSON.parse(_data.response));
                genericResponseHandler(JSON.parse(_data.response), "SE");
            }
            else {
                //PGWHPCErrorMap.onGenericError(JSON.parse(_data.response));
                genericResponseHandler(JSON.parse(_data.response), "GE");
            }
        }
    };

    xhttp.open("POST", _url, true);
    xhttp.setRequestHeader("Content-Type", "application/json");
    xhttp.setRequestHeader("Authorization", "Bearer " + PGWHPCRequestToken);
    xhttp.send(JSON.stringify({
        merchantRefNo: PGWHPCTransactionParameters.__12trn__,
        amount: PGWHPCTransactionParameters.__00amt__,
        merchantCode: PGWHPCTransactionParameters.__04mid__,
        subMerchantCode: PGWHPCTransactionParameters.__07smid__,
        currency: "586",
        nationalId: document.getElementById("pgwhpcfld_CNIC").value,
        //contactNo: document.getElementById("pgwhpcfld_ContactNumber").value,
        bankCode: document.getElementById("pgwhpcfld_Bank").value,
        relationshipId: document.getElementById("pgwhpcfld_AccountNumber").value,
        otp: document.getElementById("pgwhpcfld_OTP").value,
        password: PGWHPCTransactionParameters.__05pwd__,
        transmissionDateTime: PGWHPCTransactionParameters.__10tdt__//_trnDTnEDT.txndt.toString(),
    }));
}
//Account functions end

//Wallet functions start
function walletfundtransfer() {

    PGWHPCSuccessMap.onWalletTriggered("WalletTriggered_Success");
    document.getElementById("__btnProcessWallet__").setAttribute("disabled", true);
    var _mobile = document.getElementById("pgwhpcaccfld_MobileNumber");
    var _email = document.getElementById("pgwhpcaccfld_EmailAdress");
    var xhttp = new XMLHttpRequest();
    var _url = PGWHPCTransactionURL + "v2/transaction/walletfundtransfer";

    xhttp.onreadystatechange = function () {
        var _data = this;
        if (this.readyState == 4) {
            if (this.status == 200) {


                if (JSON.parse(_data.response).responseCode == "000") {
                    if (_mobile && _email != null && _email !== undefined) {
                        if (IsEscrowEnable == "1") {
                            InfoResponseHandler();
                        }
                    }
                }
                if (JSON.parse(_data.response).responseCode == "058") {
                    //PGWHPCErrorMap.onRequestTimedOut(JSON.parse(_data.response));
                    genericResponseHandler(JSON.parse(_data.response), "TO");
                    return;
                }

                genericResponseHandler(JSON.parse(_data.response), "SW");

                //document.getElementById("__btnProcessWallet__").removeAttribute("disabled");
                document.getElementById("__btnProcessWallet__").value = "Completed";
                //PGWHPCSuccessMap.onSuccessProcessAccountDetails(JSON.parse(_data.response));
            }

            else if (this.status == 400) {
                //PGWHPCErrorMap.onRequestTimedOut(JSON.parse(_data.response));
                genericResponseHandler(JSON.parse(_data.response), "TO");
            }
            else if (this.status == 500) {
                //PGWHPCErrorMap.onSystemError(JSON.parse(_data.response));
                genericResponseHandler(JSON.parse(_data.response), "SE");
            }
            else if (this.status == 503) {
                //PGWHPCErrorMap.onSystemError(JSON.parse(_data.response));
                genericResponseHandler(JSON.parse(_data.response), "SE");
                enableProcessButton();
            }
            else {
                //PGWHPCErrorMap.onGenericError(JSON.parse(_data.response));
                genericResponseHandler(JSON.parse(_data.response), "GE");
            }
        }
    };

    xhttp.open("POST", _url, true);
    xhttp.setRequestHeader("Content-Type", "application/json");
    xhttp.setRequestHeader("Authorization", "Bearer " + PGWHPCRequestToken);
    xhttp.send(JSON.stringify({
        transactionType: "MWALLET",
        merchantID: PGWHPCTransactionParameters.__04mid__,
        subMerchantID: PGWHPCTransactionParameters.__07smid__,
        password: PGWHPCTransactionParameters.__05pwd__,
        rrn: PGWHPCTransactionParameters.__12trn__,
        amount: PGWHPCTransactionParameters.__00amt__,
        walletAccountNumber: document.getElementById("pgwhpcaccfld_MobileNumber").value,
        currency: "586",
        transmissionDateTime: PGWHPCTransactionParameters.__10tdt__, //_trnDTnEDT.txndt.toString(),
        invoiceNo: PGWHPCTransactionParameters.__01brn__,
        billDescription: "",
        expiryDateTime: PGWHPCTransactionParameters.__11edt__,//_trnDTnEDT.txnexp.toString(),
        secureHash: PGWHPCTransactionParameters.__08seh__,
        cnic: document.getElementById("pgwhpcwalletfld_CNIC").value,
        isChild: "true",
        usageMode: "HPC"
    }));
}
//Wallet functions end
function InfoResponseHandler() { //Calling Customer api

    var xhttp = new XMLHttpRequest();
    var _url = SuiteURL + "api/v1/customers/CaptureCustomerDetail";
    xhttp.onreadystatechange = function () {
        var _data = this;
        if (this.readyState == 4 && this.status == 200) {
            var _responseObj = JSON.parse(_data.response);
            console.log(_responseObj)
        }
        else if (this.readyState == 4 && this.status == 400) {
            //PGWHPCErrorMap.onRequestTimedOut(JSON.parse(_data.response));
            genericResponseHandler(JSON.parse(_data.response), "TO");
        }
        else if (this.readyState == 4 && this.status == 500) {
            //PGWHPCErrorMap.onSystemError(JSON.parse(_data.response));
            genericResponseHandler(JSON.parse(_data.response), "SE");
        }
        else if (this.readyState == 4 && this.status == 503) {
            //PGWHPCErrorMap.onSystemError(JSON.parse(_data.response));
            genericResponseHandler(JSON.parse(_data.response), "SE");
        }
        else if (this.readyState == 4) {
            //PGWHPCErrorMap.onSystemError(JSON.parse(_data.response));
            genericResponseHandler(JSON.parse(_data.response), "GE");
        }
    };


    xhttp.open("POST", _url, true);
    xhttp.setRequestHeader("Content-Type", "application/json");
    xhttp.setRequestHeader("Authorization", "Bearer " + PGWHPCRequestToken);
    xhttp.send(JSON.stringify({
        transactionKey: PGWHPCCtxnkey,
        MerchantId: PGWHPCTransactionParameters.__04mid__,
        customerMobile: document.getElementById("pgwhpcaccfld_MobileNumber").value,
        customerEmail: document.getElementById("pgwhpcaccfld_EmailAdress").value,
        pp_TxnRefNo: PGWHPCTransactionParameters.__12trn__,
        MerchantReferenceNumber: PGWHPCTransactionParameters.__12trn__,
    }));
}
//Response handler
function genericResponseHandler(resp, code) {

    //Code Mapper
    //TO = Timed Out //PGWHPCErrorMap.onRequestTimedOut(JSON.parse(_data.response));
    //SE = System Error //PGWHPCErrorMap.onSystemError(JSON.parse(_data.response));
    //IA = Invalid Account //PGWHPCErrorMap.onInvalidAccount(JSON.parse(_data.response));
    //GE = Generic Error //PGWHPCErrorMap.onGenericError(JSON.parse(_data.response));
    //EO = Expired OTP //PGWHPCErrorMap.onExpiredOTP(JSON.parse(_data.response));
    //SC = Purchase Success via Card // PGWHPCSuccessMap.onPurchaseProcessedViaCard(JSON.parse(_data.response));
    //SA = Payment Success via Account // PGWHPCSuccessMap.onPurchaseProcessedViaAccount(JSON.parse(_data.response));
    //SW = Payment Success via Wallet // PGWHPCSuccessMap.onPurchaseProcessedViaWallet(JSON.parse(_data.response));
    //console.log(code);

    var xhttp = new XMLHttpRequest();
    var _url = PGWHPCTransactionURL + "v2/transaction/paymentresponse/";

    xhttp.onreadystatechange = function () {
        var _data = this;
        if (this.readyState == 4) {
            //console.log(_data.responseText);
            if (code == "TO") {
                PGWHPCErrorMap.onRequestTimedOut(JSON.parse(_data.response));
            }

            if (code == "SE") {
                PGWHPCErrorMap.onSystemError(JSON.parse(_data.response));
            }

            if (code == "IA") {
                PGWHPCErrorMap.onInvalidAccount(JSON.parse(_data.response));
            }

            if (code == "GE") {
                PGWHPCErrorMap.onGenericError(JSON.parse(_data.response));
            }

            if (code == "EO") {
                PGWHPCErrorMap.onExpiredOTP(JSON.parse(_data.response));
            }

            if (code == "SC") {
                PGWHPCSuccessMap.onPurchaseProcessedViaCard(JSON.parse(_data.response));
            }

            if (code == "SA") {
                PGWHPCSuccessMap.onPurchaseProcessedViaAccount(JSON.parse(_data.response));
            }

            if (code == "SW") {
                PGWHPCSuccessMap.onPurchaseProcessedViaWallet(JSON.parse(_data.response));

            }
        }
    }

    xhttp.open("POST", _url, true);
    xhttp.setRequestHeader("Content-Type", "application/json");
    xhttp.setRequestHeader("Authorization", "Bearer " + PGWHPCRequestToken);
    xhttp.send(JSON.stringify({
        pp_Version: PGWHPCTransactionParameters.__13ver__,
        pp_TxnType: "",
        pp_Language: "",
        pp_MerchantID: PGWHPCTransactionParameters.__04mid__,
        pp_Password: PGWHPCTransactionParameters.__05pwd__,
        pp_SubMerchantID: PGWHPCTransactionParameters.__07smid__,
        pp_BankID: document.getElementById("pgwhpcfld_Bank") ? document.getElementById("pgwhpcfld_Bank").value : "",
        pp_ProductID: "",
        pp_TxnRefNo: PGWHPCTransactionParameters.__12trn__,
        pp_Amount: PGWHPCTransactionParameters.__00amt__,
        pp_TxnCurrency: PGWHPCTransactionParameters.__09cur__,
        pp_TxnDateTime: PGWHPCTransactionParameters.__10tdt__,
        pp_BillReference: PGWHPCTransactionParameters.__01brn__,
        pp_ResponseCode: resp.responseCode,
        pp_ResponseMessage: resp.Status,
        pp_RetreivalReferenceNo: resp.rrn,
        pp_AuthCode: "",
        pp_SettlementExpiry: "",
        pp_TxnExpiryDateTime: PGWHPCTransactionParameters.__11edt__,
        pp_NationalID: document.getElementById("pgwhpcfld_CNIC") ? document.getElementById("pgwhpcfld_CNIC").value : "",
        ppmpf_1: "",
        ppmpf_2: "",
        ppmpf_3: "",
        ppmpf_4: "",
        ppmpf_5: "",
    }));
}
//Response handler end

//Response handler