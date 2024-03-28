<?php

return [
    'name' => 'Nicepay',

    'call_back_url'          => env('NICEPAY_CALL_BACK_URL'),
    'db_process_url'         => env('NICEPAY_DB_PROCESS_URL'),
    'imid'                   => env('NICEPAY_IMID', 'IONPAYTEST'),
    'ip_address_development' => env('NICEPAY_IP_ADDRESS_DEVELOPMENT', '103.20.51.39'),
    'ip_address_production'  => env('NICEPAY_IP_ADDRESS_PRODUCTION', '103.20.51.34'),
    'merchant_key'           => env('NICEPAY_MERCHANT_KEY', 'DuXlxlO1UAmWVYTJV3/XtHDiFRF4Ah+9U3eIP9TwivCOYoZ82Js5+ph56+3m+Xq+fiQdrCmqBlE5v2XPhrvjhQ=='),
    'mode'                   => env('NICEPAY_MODE', 'development'),
];
