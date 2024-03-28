PHP is cool!
============

![NICEPAY PHP SDK](http://issue.nicepay.co.id/media/attachments/e/d/e/9/4680dfa59c67c7a0b747366b804bc3caca8857b068f32881ba9243ede719/php.png "NICEPAY PHP SDK")

```
PHP is a Dagger: Agile, light, handy, yet powerful and kills.
```

> _If PHP is a dagger, we are the Ninja!_

NICEPAY PHP SDK
==================

![NICEPAY LITE](http://issue.nicepay.co.id/media/attachments/0/d/0/e/7e3cfb1d450fb176bf3741b3d0eb6521b68724b35549b738cfd372d07533/page_php.png "NICEPAY LITE")

NICEPAY LITE
------------

1. [Requirements and Preresiquites](#Requirements)
2. [Configuration](#Configuration)
3. [Populate and Set Parameters](#Parameters)
4. [Handle Notification](#Notification)

### <a id="Requirements"></a> 1. REQUIREMENTS AND PREREQUISITES

#### NICEPAY LITE PHP SDK FILE

:paperclip:[1. NICEPAY LITE PHP V1.13 SDK DOCUMENTATION](https://git.nicepay.co.id/nicepay-integration/nicepay-php-professional/blob/master/docs/NICEPAY-Lite-For-PHP_v1.13_English.pdf)

:paperclip:[2. NICEPAY LITE PHP V1.13 SDK](https://git.nicepay.co.id/nicepay-integration/nicepay-php-professional/repository/archive.zip?ref=master)

#### PHP 5.3.10

```php
<?php
if (version_compare(phpversion(), '5.3.10', '<')) {
    echo "Please upgrade your PHP";
}
```

#### fsockopen()

Check wether fsockopen() enabled.

```php
<?php
if(function_exists('fsockopen')) {
echo "fsockopen function is enabled";
}
else {
echo "fsockopen is not enabled. Please enable.";
}
```

if fsockopen() not enabled, you should have the following line in php.ini;

```ini
allow_url_fopen = On
```
### <a id="Configuration"></a> 2. Configuration

First, we have to configure MID, API Key, Merchant result page and Merchant notification handler url.

:open_file_folder:File location: `lib/NicepayConfig.php`

```php
<?php
// Please set the following

define("NICEPAY_IMID",              "IONPAYTEST");                                                      // Merchant ID
define("NICEPAY_MERCHANT_KEY",      "33F49GnCMS1mFYlGXisbUDzVf2ATWCl9k3R++d5hDd3Frmuos/XLx8XhXpe+LDYAbpGKZYSwtlyyLOtS/8aD7A=="); // API Key

define("NICEPAY_CALLBACK_URL",      "http://yoursite.tld/to-be-redirected/after/nicepay-payment-page"); // Merchant's result page urldecode(str)
define("NICEPAY_DBPROCESS_URL",     "http://httpresponder.com/nicepay/notification-handler.php");                                // Merchant's notification handler URL

/* TIMEOUT - Define as needed (in seconds) */
define( "NICEPAY_TIMEOUT_CONNECT", 15 );
define( "NICEPAY_TIMEOUT_READ", 25 );
```

As you see in configuration above;

1. **NICEPAY_IMID** is MID given by NICEPay, ask NICEPay representative to get one
2. **NICEPAY_MERCHANT_KEY** is API Key given by NICEPay, ask NICEPay representative to get one
3. **NICEPAY_CALLBACK_URL** is Url of result page once the Payment done
4. **NICEPAY_DBPROCESS_URL** is WebHook, Url of script that handle for notification from nicepay (in sample file, named **notification-handler.php**)


_~NICEPAY ♥ PHP_