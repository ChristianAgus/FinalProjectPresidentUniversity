<?php

namespace Modules\Nicepay\Models\NicepayCode\PaymentStatusCode;

class CreditCard
{
    public static $success = 0;
    public static $failed = 1;
    public static $voidOrRefund = 2;
    public static $initializationOrReversal = 9;
}
