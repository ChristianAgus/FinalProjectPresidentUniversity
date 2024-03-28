<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'nicepay' => [
        'call_back_url' => env('NICEPAY_CALL_BACK_URL'),
        'db_process_url'=> env('NICEPAY_DB_PROCESS_URL'),
        'imid'          => env('NICEPAY_IMID', 'IONPAYTEST'),
        'merchant_key'  => env('DuXlxlO1UAmWVYTJV3/XtHDiFRF4Ah+9U3eIP9TwivCOYoZ82Js5+ph56+3m+Xq+fiQdrCmqBlE5v2XPhrvjhQ=='),
        'sandbox'       => env('NICEPAY_MODE', 'development'), 
    ],

];
