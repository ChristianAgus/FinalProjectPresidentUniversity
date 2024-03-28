<?php
/*
 * ____________________________________________________________
 *
 * Copyright (C) 2016 NICE IT&T
 *
 *
 * This config file may used as it is, there is no warranty.
 *
 * @ description : PHP SSL Client module.
 * @ name        : NicepayConfig.php
 * @ author      : NICEPAY_V1_ENTERPRISE I&T (tech@nicepay.co.kr)
 * @ date        : 09.03.2016
 * @ modify      : 30.01.2017
 *
 * 2017.01.30 Update Log
 *
 * ____________________________________________________________
 */

// Please set the following

define("NICEPAY_V1_ENTERPRISE_IMID",              "BMRITEST01");                                                  // Merchant ID
define("NICEPAY_V1_ENTERPRISE_MERCHANT_KEY",      "33F49GnCMS1mFYlGXisbUDzVf2ATWCl9k3R++d5hDd3Frmuos/XLx8XhXpe+LDYAbpGKZYSwtlyyLOtS/8aD7A=="); // API Key

define("NICEPAY_V1_ENTERPRISE_CALLBACK_URL",      "http://httpresponder.com/nicepay");                            // Merchant's result page URL
define("NICEPAY_V1_ENTERPRISE_DBPROCESS_URL",     "http://httpresponder.com/nicepay");                            // Merchant's notification handler URL

/* TIMEOUT - Define as needed (in seconds) */
define( "NICEPAY_V1_ENTERPRISE_TIMEOUT_CONNECT", 15 );
define( "NICEPAY_V1_ENTERPRISE_TIMEOUT_READ", 25 );


// Please do not change

define("NICEPAY_V1_ENTERPRISE_PROGRAM",           "NicepayDirect");
define("NICEPAY_V1_ENTERPRISE_VERSION",           "1.11");
define("NICEPAY_V1_ENTERPRISE_BUILDDATE",         "20160309");
define("NICEPAY_V1_ENTERPRISE_REQ_VA_URL",        "https://www.nicepay.co.id/nicepay/api/onePass.do");            // Request Virtual Account API URL
define("NICEPAY_V1_ENTERPRISE_3DSECURE_URL",      "https://www.nicepay.co.id/nicepay/api/secureVeRequest.do");    // 3D Secure API URL
define("NICEPAY_V1_ENTERPRISE_CANCEL_VA_URL",     "https://www.nicepay.co.id/nicepay/api/onePassAllCancel.do");   // Cancel Virtual Account API URL
define("NICEPAY_V1_ENTERPRISE_CHARGE_URL",        "https://www.nicepay.co.id/nicepay/api/onePass.do");            // Charge Credit Card API URL
define("NICEPAY_V1_ENTERPRISE_CANCEL_URL",        "https://www.nicepay.co.id/nicepay/api/onePassAllCancel.do");   // Cancellation URL
define("NICEPAY_V1_ENTERPRISE_ORDER_STATUS_URL",  "https://www.nicepay.co.id/nicepay/api/onePassStatus.do");      // Check payment status URL
define("NICEPAY_V1_ENTERPRISE_RECURRING_TOKEN",   "https://www.nicepay.co.id/nicepay/api/recurringTrans.do");      // Register Token Recurring API URL
define("NICEPAY_V1_ENTERPRISE_READ_TIMEOUT_ERR",  "10200");

/* LOG LEVEL */

define("NICEPAY_V1_ENTERPRISE_LOG_CRITICAL", 1);
define("NICEPAY_V1_ENTERPRISE_LOG_ERROR", 2);
define("NICEPAY_V1_ENTERPRISE_LOG_NOTICE", 3);
define("NICEPAY_V1_ENTERPRISE_LOG_INFO", 5);
define("NICEPAY_V1_ENTERPRISE_LOG_DEBUG", 7);
