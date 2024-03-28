<?php
/*
 * ____________________________________________________________
 *
 * Copyright (C) 2016 NICE IT&T
 *
 * Please do not modify this module.
 * This module may used as it is, there is no warranty.
 *
 * @ description : PHP SSL Client module.
 * @ name        : 3dsecure.php
 * @ author      : NICEPAY I&T (tech@nicepay.co.kr)
 * @ date        :
 * @ modify      : 26.05.2016
 *
 * 2016.02.22 Update Log
 * Please contact it.support@ionpay.net for inquiry
 *
 * ____________________________________________________________
 */
    if(isset($_GET['resultCd'])
        && isset($_GET['resultMsg'])
        && ($_GET['resultCd'] == "0000")
        )
    {
    $resultCd       = $_GET['resultCd'];
    $resultMsg      = $_GET['resultMsg'];
    } else {
        // Wrong CC Number or not supported CC
        echo "<script type='text/javascript'>alert('Wrong Credit Card Number or 3D Secure Not Supported. Please Contact Your Card Issuer.');";
        echo "top.$.featherlight.close();";
        echo "</script>";
        die();
    }
?>

<html>
<head>
<script type="text/javascript" src="jquery.1.11.3.min.js"></script>
<script type = "text/javascript">
function setAcsInfoRecThreeDS(){

    if(document.tranMgr.resultCd.value != "0000" ){
        window.top.errMsg(document.tranMgr.resultCd.value, document.tranMgr.resultMsg.value);
        console.log("resultCd: "+document.tranMgr.resultCd.value);
        top.$.featherlight.close();
        return;
    }
    window.top.recurringThreeDSSubmit();
    top.$.featherlight.close();
}
</script>
</head>

<body onLoad="javascript:setAcsInfoRecThreeDS();">
    <form name="tranMgr" method="post">
        <input type="hidden" name="resultCd"  id="resultCd" value="<?php echo $resultCd;?>">
        <input type="hidden" name="resultMsg" id="resultMsg" value="<?php echo $resultMsg;?>">
    </form>
</body>
</html>
