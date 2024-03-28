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
 * @ name        : NicepayLite.php
 * @ author      : NICEPAY I&T (tech@nicepay.co.kr)
 * @ date        :
 * @ modify      : 09.03.2016
 *
 * 09.03.2016 Update Log
 * Please contact it.support@ionpay.net for inquiry
 *
 * ____________________________________________________________
 */
// Include the Nicepay class
// Cancel VA (VA can be canceled only if VA status is not paid)
include_once('lib/NicepayLib.php');

    $nicepay = new NicepayLib();
    if(!empty($_POST['tXid']) && !empty($_POST['tXid']))
    {
    $iMid               = $nicepay->iMid;
    $tXid               = $_POST['tXid'];
    $amt                = $_POST['amt'];

    $nicepay->set('tXid', $tXid);
    $nicepay->set('payMethod', '02');
    $nicepay->set('amt', $amt);
    $nicepay->set('iMid',$iMid);
    $nicepay->set('cancelType', 1);

    $merchantToken = $nicepay->merchantTokenC();
    $nicepay->set('merchantToken', $merchantToken);
//    echo $nicepay->get('merchantToken');
//    exit();
    // <REQUEST to NICEPAY>
    $response = $nicepay->cancelVA($tXid, $amt);

    // <RESPONSE from NICEPAY>
        echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">';
            echo "<pre>";
            var_dump($response);
            echo "</pre>";

    } else {
        echo "Please set amount, referenceNo and tXid";
    }