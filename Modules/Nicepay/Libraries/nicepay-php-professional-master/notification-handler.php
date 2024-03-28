<?php
/*
 * ____________________________________________________________
 *
 * Copyright (C) 2015 NICE IT&T
 *
 * Please do not modify this module.
 * This module may used as it is, there is no warranty.
 *
 * @ description : PHP SSL Client module.
 * @ name        : NicepayLite.php
 * @ author      : NICEPAY I&T (tech@nicepay.co.kr)
 * @ date        : 
 * @ modify      : 22.02.2016
 *
 * 2016.02.22 Update Log
 *
 * ____________________________________________________________
 */
// Include the Transferpay class
include_once('lib/NicepayLib.php');

$nicepay = new NicepayLib();

// Listen for parameters passed
$pushParameters = array('tXid',
    'referenceNo',
    'merchantToken',
    'amt'
);

$nicepay->extractNotification($pushParameters);

$iMid               = $nicepay->iMid;
$tXid               = $nicepay->getNotification('tXid');
$referenceNo        = $nicepay->getNotification('referenceNo');
$amt                = $nicepay->getNotification('amt');
$pushedToken        = $nicepay->getNotification('merchantToken');

$nicepay->set('tXid', $tXid);
$nicepay->set('referenceNo', $referenceNo);
$nicepay->set('amt', $amt);
$nicepay->set('iMid',$iMid);

$merchantToken = $nicepay->merchantTokenC();
$nicepay->set('merchantToken', $merchantToken);

// <RESQUEST to NICEPAY>
$paymentStatus = $nicepay->checkPaymentStatus($tXid, $referenceNo, $amt);

// <RESPONSE from NICEPAY>
// Please update the payment status in your database right after you get the latest payment status
if($pushedToken == $merchantToken) {
    // Print only OK, no HTML, no UI, no beauty poem. Also make sure HTTP code = 200
    echo "OK";
    // Update the payment status in your database based on $paymentStatus->StatusDescription,
    // Send email notification to customer to notify payment succeed

    // <RESPONSE from NICEPAY>
    // Please update the payment status in your database right after you get the latest payment status
    echo "<pre>";
    echo "$paymentStatus->status\n"; // This is Payment Status main reference to update Payment Status in merchant database
    /**
     **=========================================================================================================
     ** Credit Card
     **=========================================================================================================
     ** $paymentStatus->status == 0 // Success
     ** $paymentStatus->status == 1 // Failed
     ** $paymentStatus->status == 2 // Void or Refund
     ** $paymentStatus->status == 9 // Initialization or Unpaid
     **=========================================================================================================
     *
     **=========================================================================================================
     ** Virtual Account
     **=========================================================================================================
     ** $paymentStatus->status == 0 // Paid
     ** $paymentStatus->status == 1 // Reversal
     ** $paymentStatus->status == 3 // Cancel
     ** $paymentStatus->status == 4 // Expired
     **=========================================================================================================
     */
    echo "$paymentStatus->tXid\n";
    echo "$paymentStatus->iMid\n";
    echo "$paymentStatus->referenceNo\n";
    echo "$paymentStatus->amt\n";
    // var_dump for more information
    // var_dump($paymentStatus);
    echo "</pre>";

}