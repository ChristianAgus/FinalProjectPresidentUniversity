function cardPaySubmit(){
    var ccForm = document.getElementById("ccForm");
        ccForm.submit();
}

//3DSecure Result Setting Value in 3dsecure.php
function setAcsInfo(name,values) {
      var names = document.getElementById(name);
      names.value = values;
}

function getOnePassToken(merchantToken,imid)
{
    var ajaxUrl = "https://www.nicepay.co.id/nicepay/api/onePassToken.do";
    var dataObject = new Object();
    var paymentType = "1";
    var iMid = imid;

    dataObject.iMid = iMid;
    dataObject.referenceNo = $("#referenceNo").val();
    dataObject.amt = $("#amt").val();

    var threeDsecurephp = "http://localhost/nicepay-php-enterprize-ricko/3dsecure.php";
    dataObject.cardNo = $("#number").val();
    dataObject.cardExpYymm = $("#exp-month").val();
    dataObject.merchantToken = merchantToken;

    $.ajax({
        url : ajaxUrl,
        type : "post",
        crossDomain: true,
        data : "jsonData=" + JSON.stringify(dataObject),
        dataType: "jsonp",
        timeout : 30000,
        success : function(data) {

            var get_data = eval(data);
            var resultCd = get_data["resultCd"];
            var resultMsg = get_data["resultMsg"];
            if(resultCd == "0000"){
                $("#onePassToken").val(get_data["cardToken"]);
                var amount = $("#amt").val();
                var onePassToken = get_data["cardToken"];

                //3d secure
                if(onePassToken == null || onePassToken == ""){
                    alert("onePassToken failed.")
                    return;
                }
                $("#onePassToken").val(onePassToken);
                    var targetUrl = "https://www.nicepay.co.id/nicepay/api/secureVeRequest.do?country=360" + "&callbackUrl="+threeDsecurephp+ "&onePassToken=" + onePassToken;
                    $.featherlight({iframe: targetUrl, iframeMaxWidth: '100%', iframeWidth: 450, iframeHeight: 450});
            }
            else{
                alert(resultMsg);
            }
        },
        error : function(request,status,error){
            alert(status);
        }
    });
}

/**
    cancel Button
*/
function cancelOK(sel){
    var cancelForm = document.getElementById("cancelForm");
    if(sel == "1"){
        //void data
        document.getElementsByName("cancelType")[0].value = 1;
    }else{
        //refund Data
        document.getElementsByName("cancelType")[0].value = 2;
    }
    cancelForm.submit();
}

/**
    payment button
*/
function nicepay(merchantToken,imid) {
    var ccForm = document.ccForm;
    var ccPayMethod = "01";
    //card payment
    if( ccPayMethod== "01"){
        //OnePass Token
        getOnePassToken(merchantToken,imid);
    }
    else{
        return;
    }
}

/**
    OnePass Inquiry Submit
*/
function inquiry()
{
    var onePassInquiryForm = document.getElementById("onePassInquiryForm");
    onePassInquiryForm.submit();
}

function errMsg(code,message)
{
    console.log("3D Secure failed");
    console.log(code+" : "+message);
}

/**
	recurring 3ds
*/

function recProcessThreeDS(merchantToken,imid) {
	var recthreedsForm  = document.getElementById("recthreedsForm");
	var recPayMethod  = "04";
	//card payment
	if( recPayMethod == "04"){
		//OnePass Token
		getRecurringToken(merchantToken,imid);
	}
	else{
		return;
	}
}

function getRecurringToken(merchantToken,imid){
	var ajaxUrl = "https://www.nicepay.co.id/nicepay/api/recurringToken.do";
	var dataObject = new Object();
	var iMid = imid;

	var threeDsecureRecphp = "http://localhost/nicepay-php-enterprize-ricko/recurringThreeDSecure.php";

	dataObject.amt = $("#amtThreeDS").val();
	dataObject.referenceNo = $("#referenceNoThreeDS").val();
	dataObject.merchantToken = merchantToken;
	dataObject.iMid = iMid;
	dataObject.recurringToken = $("#recurringThreeDS").val();

	$.ajax({
		url : ajaxUrl,
		type : "post",
		crossDomain: true,
		data : "jsonData=" + JSON.stringify(dataObject),
		dataType: "jsonp",
		timeout : 30000,
		success : function(data) {

			var get_data = eval(data);
			var resultCd = get_data["resultCd"];
			var resultMsg = get_data["resultMsg"];
			if(resultCd == "0000"){
				var onePassTokenRecurring = get_data["cardToken"];

				//3d secure
				if(onePassTokenRecurring == null || onePassTokenRecurring == ""){
					alert("onePassTokenRecurring failed.")
					return;
				}
				var targetUrl = "https://www.nicepay.co.id/nicepay/api/secureVeRequest.do?country=360" + "&callbackUrl="+threeDsecureRecphp+ "&onePassToken=" + onePassTokenRecurring;
				$.featherlight({iframe: targetUrl, iframeMaxWidth: '100%', iframeWidth: 450, iframeHeight: 450});
			}else{
				alert(resultMsg);
			}
		},
		error : function(request,status,error){
			alert(status);
		}
	});
}

//3DSecure Result Setting Value in 3dsecure.php
function setAcsInfoRecThreeDS(name,values) {
	var names = document.getElementById(name);
	names.value = values;
}

function recurringThreeDSSubmit(){
	var recthreedsForm  = document.getElementById("recthreedsForm");
    recthreedsForm.submit();
}
