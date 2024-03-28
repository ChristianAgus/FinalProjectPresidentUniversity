<?php

namespace Modules\Orders\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Nicepay\Models\NicepayCode;
use Modules\Nicepay\Models\NicepayCode\PaymentMethod as NicepayCodePaymentMethod;
use Modules\Nicepay\Models\Enterprise\Registration as NicepayV1EnterpriseRegistration;
use Modules\Nicepay\Models\Professional\Registration as NicepayV1ProfessionalRegistration;
use Modules\Vouchers\Models\Vouchers;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    use SoftDeletes;

    protected $dates = ['date'];

    protected $fillable = [
                            'order_no',
                            'invoice_number',
                            'user_member_id',
                            'status',
                            'payment',
                            'payment_method',
                            'payment_date',
                            'payment_fee_formula',
                            'payment_status',
                            'subtotal',
                            'tax',
                            'total_weight',
                            'total_shipping_cost',
                            'payment_fee',
                            'grand_total',
                            'notes',
                            'voucher_id',
                            'voucher_code',
                            'voucher_value',
                            'voucher_unit',
                            'voucher_type',
                            'date'
                        ];

    public $nicepayCode;

    public static $statusCompleted = 'Completed';
    public static $statusNew = 'New';
    public static $statusPending = 'Pending';
    public static $statusReceived = 'Received';
    public static $statusReturned = 'Returned';
    public static $statusSent = 'Sent';
    public static $paymentNicepay = 'nicepay';
    public static $paymentStatusPaid = 1;
    public static $paymentStatusUnpaid = 0;

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);
        $this->nicepayCode = new NicepayCode;
    }

    public function getIdEncryptAttribute($value)
    {
        return encrypt($this->id);
    }

    public function getGrandTotalFormat()
    {
        return number_format($this->grand_total, 0, ',', '.');
    }

    public function getNicepayCartData()
    {
        $cartData = [
            'count' => 0,
            'item' => [],
        ];

        if ($this->orderDetails) {
            foreach ($this->orderDetails as $orderDetail) {
                $cartData['count'] += 1;
                $cartData['item'][] = [
                    'img_url' => $orderDetail->product->productImage ? $orderDetail->product->productImage->getImageUrl() : '',
                    'goods_name' => $orderDetail->product_name,
                    'goods_detail' => '',
                    'goods_amt' => $orderDetail->quantity * $orderDetail->price,
                ];
            }
        }

        if ($this->tax > 0) {
            $cartData['count'] += 1;
            $cartData['item'][] = [
                'img_url' => '',
                'goods_name' => trans('cms.tax'),
                'goods_detail' => trans('cms.tax_from_param1', ['param1' => $this->subtotal.' ('.trans('cms.subtotal').')']),
                'goods_amt' => $this->tax,
            ];
        }

        if ($this->total_shipping_cost > 0) {
            $cartData['count'] += 1;
            $cartData['item'][] = [
                'img_url' => '',
                'goods_name' => trans('cms.shipping_cost'),
                'goods_detail' => '',
                'goods_amt' => $this->total_shipping_cost,
            ];
        }

        if ($this->payment_fee > 0) {
            $cartData['count'] += 1;
            $cartData['item'][] = [
                'img_url' => '',
                'goods_name' => trans('cms.payment_fee'),
                'goods_detail' => '',
                'goods_amt' => $this->payment_fee,
            ];
        }

        if ($this->voucher_value > 0) {
            $cartData['count'] += 1;
            $cartData['item'][] = [
                'img_url' => '',
                'goods_name' => trans('cms.voucher'),
                'goods_detail' => '',
                'goods_amt' => $this->voucher_value,
            ];
        }

        return $cartData;
    }

    public function getNicepayV1EnterpriseRegistration($data)
    {
        $nicepayV1EnterpriseRegistration = new NicepayV1EnterpriseRegistration;
        $nicepayV1EnterpriseRegistration->fill($data);

        $nicepayV1EnterpriseRegistration->iMid = config('nicepay.imid');
        $nicepayV1EnterpriseRegistration->merchantKey = config('nicepay.merchant_key');
        $nicepayV1EnterpriseRegistration->payMethod = isset($data['payMethod']) ? $data['payMethod'] : $this->payment_method;
        $nicepayV1EnterpriseRegistration->currency = isset($data['currency']) ? $data['currency'] : $nicepayV1EnterpriseRegistration->currency;

        $nicepayV1EnterpriseRegistration->amt = $this->grand_total;
        $nicepayV1EnterpriseRegistration->referenceNo = $this->id;
        $nicepayV1EnterpriseRegistration->goodsNm = isset($data['goodsNm']) ? $data['goodsNm'] : $nicepayV1EnterpriseRegistration->goodsNm;
        $nicepayV1EnterpriseRegistration->billingNm = optional($this->orderShippingAddress)->name;
        $nicepayV1EnterpriseRegistration->billingPhone = optional($this->orderShippingAddress)->phone_number;

        $nicepayV1EnterpriseRegistration->billingEmail = optional($this->orderShippingAddress)->email ? optional($this->orderShippingAddress)->email : 'dotcomsolution@mailinator.com';
        $nicepayV1EnterpriseRegistration->billingCity = isset($data['billingCity']) ? $data['billingCity'] : optional($this->orderShippingAddress)->regency;
        $nicepayV1EnterpriseRegistration->billingState = isset($data['billingState']) ? $data['billingState'] : optional($this->orderShippingAddress)->province;
        $nicepayV1EnterpriseRegistration->billingPostCd = isset($data['billingPostCd']) ? $data['billingPostCd'] : optional($this->orderShippingAddress)->postal_code;
        $nicepayV1EnterpriseRegistration->billingCountry = isset($data['billingCountry']) ? $data['billingCountry'] : $nicepayV1EnterpriseRegistration->billingCountry;

        $nicepayV1EnterpriseRegistration->callBackUrl = config('nicepay.call_back_url');
        $nicepayV1EnterpriseRegistration->dbProcessUrl = config('nicepay.db_process_url');
        $nicepayV1EnterpriseRegistration->description = $this->notes ? $this->notes : $nicepayV1EnterpriseRegistration->description;
        $nicepayV1EnterpriseRegistration->merchantToken = $nicepayV1EnterpriseRegistration->merchantToken;
        $nicepayV1EnterpriseRegistration->userIP = $_SERVER['REMOTE_ADDR'];

        $nicepayV1EnterpriseRegistration->cartData = json_encode($this->getNicepayCartData());
        $nicepayV1EnterpriseRegistration->instmntType = isset($data['instmntType']) ? $data['instmntType'] : $nicepayV1EnterpriseRegistration->instmntType;
        $nicepayV1EnterpriseRegistration->instmntMon = isset($data['instmntMon']) ? $data['instmntMon'] : $nicepayV1EnterpriseRegistration->instmntMon;
        $nicepayV1EnterpriseRegistration->cardCvv = isset($data['cardCvv']) ? $data['cardCvv'] : $nicepayV1EnterpriseRegistration->cardCvv;
        $nicepayV1EnterpriseRegistration->onePassToken = isset($data['onePassToken']) ? $data['onePassToken'] : $nicepayV1EnterpriseRegistration->onePassToken;

        $nicepayV1EnterpriseRegistration->recurrOpt = isset($data['recurrOpt']) ? $data['recurrOpt'] : $nicepayV1EnterpriseRegistration->recurrOpt;
        $nicepayV1EnterpriseRegistration->bankCd = isset($data['bankCd']) ? $data['bankCd'] : $nicepayV1EnterpriseRegistration->bankCd;
        $nicepayV1EnterpriseRegistration->vacctValidDt = isset($data['vacctValidDt']) ? $data['vacctValidDt'] : $nicepayV1EnterpriseRegistration->vacctValidDt;
        $nicepayV1EnterpriseRegistration->vacctValidTm = isset($data['vacctValidTm']) ? $data['vacctValidTm'] : $nicepayV1EnterpriseRegistration->vacctValidTm;
        $nicepayV1EnterpriseRegistration->mitraCd = isset($data['mitraCd']) ? $data['mitraCd'] : $nicepayV1EnterpriseRegistration->mitraCd;

        $nicepayV1EnterpriseRegistration->clickPayNo = isset($data['clickPayNo']) ? $data['clickPayNo'] : $nicepayV1EnterpriseRegistration->clickPayNo;
        $nicepayV1EnterpriseRegistration->dataField3 = isset($data['dataField3']) ? $data['dataField3'] : $nicepayV1EnterpriseRegistration->dataField3;
        $nicepayV1EnterpriseRegistration->clickPayToken = isset($data['clickPayToken']) ? $data['clickPayToken'] : $nicepayV1EnterpriseRegistration->clickPayToken;
        $nicepayV1EnterpriseRegistration->payValidDt = isset($data['payValidDt']) ? $data['payValidDt'] : $nicepayV1EnterpriseRegistration->payValidDt;
        $nicepayV1EnterpriseRegistration->payValidTm = isset($data['payValidTm']) ? $data['payValidTm'] : $nicepayV1EnterpriseRegistration->payValidTm;

        $nicepayV1EnterpriseRegistration->billingAddr = isset($data['billingAddr']) ? $data['billingAddr'] : optional($this->orderShippingAddress)->address;
        $nicepayV1EnterpriseRegistration->deliveryNm = optional($this->orderShippingAddress)->name;
        $nicepayV1EnterpriseRegistration->deliveryPhone = optional($this->orderShippingAddress)->phone_number;
        $nicepayV1EnterpriseRegistration->deliveryAddr = optional($this->orderShippingAddress)->address;
        $nicepayV1EnterpriseRegistration->deliveryEmail = isset($data['deliveryEmail']) ? $data['deliveryEmail'] : optional($this->userMember)->email;

        $nicepayV1EnterpriseRegistration->deliveryCity = optional($this->orderShippingAddress)->regency;
        $nicepayV1EnterpriseRegistration->deliveryState = optional($this->orderShippingAddress)->province;
        $nicepayV1EnterpriseRegistration->deliveryPostCd = optional($this->orderShippingAddress)->postal_code;
        $nicepayV1EnterpriseRegistration->deliveryCountry = isset($data['deliveryCountry']) ? $data['deliveryCountry'] : $nicepayV1EnterpriseRegistration->deliveryCountry;
        $nicepayV1EnterpriseRegistration->vat = isset($data['vat']) ? $data['vat'] : $nicepayV1EnterpriseRegistration->vat;

        $nicepayV1EnterpriseRegistration->fee = isset($data['fee']) ? $data['fee'] : $nicepayV1EnterpriseRegistration->fee;
        $nicepayV1EnterpriseRegistration->notaxAmt = isset($data['notaxAmt']) ? $data['notaxAmt'] : $nicepayV1EnterpriseRegistration->notaxAmt;
        $nicepayV1EnterpriseRegistration->reqDt = date('Ymd');
        $nicepayV1EnterpriseRegistration->reqTm = date('His');
        $nicepayV1EnterpriseRegistration->reqDomain = config('app.url');

        $nicepayV1EnterpriseRegistration->reqServerIP = $_SERVER['SERVER_ADDR'];
        $nicepayV1EnterpriseRegistration->reqClientVer = isset($data['reqClientVer']) ? $data['reqClientVer'] : $nicepayV1EnterpriseRegistration->reqClientVer;
        $nicepayV1EnterpriseRegistration->userSessionID = session_id();
        $nicepayV1EnterpriseRegistration->userAgent = $_SERVER['HTTP_USER_AGENT'];
        $nicepayV1EnterpriseRegistration->userLanguage = $_SERVER['HTTP_ACCEPT_LANGUAGE'];

        return $nicepayV1EnterpriseRegistration;
    }

    public function getNicepayV1ProfessionalRegistration($data)
    {
        $nicepayV1ProfessionalRegistration = new NicepayV1ProfessionalRegistration;
        $nicepayV1ProfessionalRegistration->fill($data);

        $nicepayV1ProfessionalRegistration->iMid = config('nicepay.imid');
        $nicepayV1ProfessionalRegistration->merchantKey = config('nicepay.merchant_key');
        $nicepayV1ProfessionalRegistration->merchantToken = $nicepayV1ProfessionalRegistration->merchantToken;
        $nicepayV1ProfessionalRegistration->payMethod = isset($data['payMethod']) ? $data['payMethod'] : $this->payment_method;

        $nicepayV1ProfessionalRegistration->currency = isset($data['currency']) ? $data['currency'] : $nicepayV1ProfessionalRegistration->currency;
        $nicepayV1ProfessionalRegistration->amt = $this->grand_total;
        $nicepayV1ProfessionalRegistration->instmntType = isset($data['instmntType']) ? $data['instmntType'] : $nicepayV1ProfessionalRegistration->instmntType;
        $nicepayV1ProfessionalRegistration->instmntMon = isset($data['instmntMon']) ? $data['instmntMon'] : $nicepayV1ProfessionalRegistration->instmntMon;
        $nicepayV1ProfessionalRegistration->referenceNo = $this->id;

        $nicepayV1ProfessionalRegistration->goodsNm = isset($data['goodsNm']) ? $data['goodsNm'] : $nicepayV1ProfessionalRegistration->goodsNm;
        $nicepayV1ProfessionalRegistration->billingNm = optional($this->orderShippingAddress)->name;
        $nicepayV1ProfessionalRegistration->billingPhone = optional($this->orderShippingAddress)->phone_number;
        $nicepayV1ProfessionalRegistration->billingEmail = optional($this->userMember)->email ? optional($this->userMember)->email : 'dotcomsolution@mailinator.com';
        $nicepayV1ProfessionalRegistration->billingAddr = isset($data['billingAddr']) ? $data['billingAddr'] : optional($this->orderShippingAddress)->address;

        $nicepayV1ProfessionalRegistration->billingCity = isset($data['billingCity']) ? $data['billingCity'] : optional($this->orderShippingAddress)->regency;
        $nicepayV1ProfessionalRegistration->billingState = isset($data['billingState']) ? $data['billingState'] : optional($this->orderShippingAddress)->province;
        $nicepayV1ProfessionalRegistration->billingPostCd = isset($data['billingPostCd']) ? $data['billingPostCd'] : optional($this->orderShippingAddress)->postal_code;
        $nicepayV1ProfessionalRegistration->billingCountry = isset($data['billingCountry']) ? $data['billingCountry'] : 'Indonesia';
        $nicepayV1ProfessionalRegistration->deliveryNm = optional($this->orderShippingAddress)->name;

        $nicepayV1ProfessionalRegistration->deliveryPhone = optional($this->orderShippingAddress)->phone_number;
        $nicepayV1ProfessionalRegistration->deliveryAddr = optional($this->orderShippingAddress)->address;
        $nicepayV1ProfessionalRegistration->deliveryCity = optional($this->orderShippingAddress)->regency;
        $nicepayV1ProfessionalRegistration->deliveryState = optional($this->orderShippingAddress)->province;
        $nicepayV1ProfessionalRegistration->deliveryPostCd = optional($this->orderShippingAddress)->postal_code;

        $nicepayV1ProfessionalRegistration->deliveryCountry = isset($data['deliveryCountry']) ? $data['deliveryCountry'] : 'Indonesia';
        $nicepayV1ProfessionalRegistration->callBackUrl = config('nicepay.call_back_url');
        $nicepayV1ProfessionalRegistration->dbProcessUrl = config('nicepay.db_process_url');
        $nicepayV1ProfessionalRegistration->vat = isset($data['vat']) ? $data['vat'] : $nicepayV1ProfessionalRegistration->vat;
        $nicepayV1ProfessionalRegistration->fee = isset($data['fee']) ? $data['fee'] : $nicepayV1ProfessionalRegistration->fee;

        $nicepayV1ProfessionalRegistration->notaxAmt = isset($data['notaxAmt']) ? $data['notaxAmt'] : $nicepayV1ProfessionalRegistration->notaxAmt;
        $nicepayV1ProfessionalRegistration->description = $this->notes ? $this->notes : $nicepayV1ProfessionalRegistration->description;
        // $nicepayV1ProfessionalRegistration->reqDt = null;
        // $nicepayV1ProfessionalRegistration->reqTm = null;
        // $nicepayV1ProfessionalRegistration->reqDomain = null;

        // $nicepayV1ProfessionalRegistration->reqServerIP = null;
        // $nicepayV1ProfessionalRegistration->reqClientVer = null;
        // $nicepayV1ProfessionalRegistration->userIP = null;
        // $nicepayV1ProfessionalRegistration->userSessionID = null;
        // $nicepayV1ProfessionalRegistration->userAgent = null;

        // $nicepayV1ProfessionalRegistration->userLanguage = null;
        // $nicepayV1ProfessionalRegistration->recurrOpt = null;
        $nicepayV1ProfessionalRegistration->cartData = json_encode($this->getNicepayCartData());
        // $nicepayV1ProfessionalRegistration->worker = null;
        // $nicepayV1ProfessionalRegistration->merFixAcctId = null;

        // $nicepayV1ProfessionalRegistration->vacctValidDt = null;
        // $nicepayV1ProfessionalRegistration->vacctValidTm = null;
        // $nicepayV1ProfessionalRegistration->paymentExpDt = null;
        // $nicepayV1ProfessionalRegistration->paymentExpTm = null;
        // $nicepayV1ProfessionalRegistration->payValidDt = null;

        // $nicepayV1ProfessionalRegistration->payValidTm = null;
        // $nicepayV1ProfessionalRegistration->tXid = null;
        // $nicepayV1ProfessionalRegistration->mitraCd = null;
        // $nicepayV1ProfessionalRegistration->mRefNo = null;
        // $nicepayV1ProfessionalRegistration->timeStamp = null;

        // $nicepayV1ProfessionalRegistration->version = null;

        return $nicepayV1ProfessionalRegistration;
    }

    public function getPaymentAmount()
    {
        $paymentAmount = 0;

        if ($this->payment == self::$paymentNicepay && $this->nicepayV1EnterpriseRegistrationResponse) {
            $paymentAmount = $this->nicepayV1EnterpriseRegistrationResponse->amount;
        }

        return $paymentAmount;
    }

    public function getPaymentAmountFormat()
    {
        return number_format($this->getPaymentAmount(), 2, ',', '.');
    }

    public function getPaymentBillingName()
    {
        $billingName = null;

        if ($this->payment == self::$paymentNicepay && $this->nicepayV1EnterpriseRegistrationResponse) {
            $billingName = $this->nicepayV1EnterpriseRegistrationResponse->billingNm;
        }

        return $billingName;
    }

    public function getPaymentExpiredAt()
    {
        $paymentExpiredAt = null;

        if ($this->payment == self::$paymentNicepay && $this->nicepayV1EnterpriseRegistrationResponse) {
            $paymentExpiredAt = $this->nicepayV1EnterpriseRegistrationResponse->getPaymentExpiredAt();
        }

        return $paymentExpiredAt;
    }

    public function getPaymentHowToPays()
    {
        $paymentHowToPays = [];

        if ($this->payment == self::$paymentNicepay && $this->nicepayV1EnterpriseRegistrationResponse) {
            $paymentHowToPays = $this->nicepayV1EnterpriseRegistrationResponse->getPaymentHowToPays();
        }

        return $paymentHowToPays;
    }

    public function getPaymentMethodAccount()
    {
        $paymentMethodAccount = null;

        if ($this->payment == self::$paymentNicepay && $this->nicepayV1EnterpriseRegistrationResponse) {
            $paymentMethodAccount = $this->nicepayV1EnterpriseRegistrationResponse->getPaymentMethodAccount();
        }

        return $paymentMethodAccount;
    }

    public function getPaymentMethodAccountLabel()
    {
        $paymentMethodAccountLabel = null;

        if ($this->payment == self::$paymentNicepay && $this->nicepayV1EnterpriseRegistrationResponse) {
            $paymentMethodAccountLabel = $this->nicepayV1EnterpriseRegistrationResponse->getPaymentMethodAccount();
        }

        return $paymentMethodAccountLabel;
    }

    public function getPaymentMethodName()
    {
        $paymentMethodNames = [];

        if ($this->payment == self::$paymentNicepay && $this->nicepayV1EnterpriseRegistration) {
            $nicepayCodePaymentMethodOptions = $this->nicepayCode->getPaymentMethodOptions();
            $paymentMethodNames[] = $nicepayCodePaymentMethodOptions[$this->payment_method];

            if ($this->payment_method == NicepayCodePaymentMethod::$cvsConvenienceStore) {
                $nicepayCodeMitraCodeOptions = $this->nicepayCode->getMitraCodeOptions();
                $paymentMethodNames[] = $nicepayCodeMitraCodeOptions[$this->nicepayV1EnterpriseRegistration->mitraCd];
            } else if ($this->payment_method == NicepayCodePaymentMethod::$virtualAccount) {
                $nicepayCodeBankCodeOptions = $this->nicepayCode->getBankCodeOptions();
                $paymentMethodNames[] = $nicepayCodeBankCodeOptions[$this->nicepayV1EnterpriseRegistration->bankCd];
            }
        }

        return implode(' - ', $paymentMethodNames);
    }

    public function getPaymentNumber()
    {
        $paymentNumber = null;

        if ($this->payment == self::$paymentNicepay && $this->nicepayV1EnterpriseRegistrationResponse) {
            $paymentNumber = $this->nicepayV1EnterpriseRegistrationResponse->getPaymentNumber();
        }

        return $paymentNumber;
    }

    public function getPaymentNumberLabel()
    {
        $paymentNumberLabel = null;

        if ($this->payment == self::$paymentNicepay && $this->nicepayV1EnterpriseRegistrationResponse) {
            $paymentNumberLabel = $this->nicepayV1EnterpriseRegistrationResponse->getPaymentNumberLabel();
        }

        return $paymentNumberLabel;
    }

    public function getStatusOptions()
    {
        return [
            self::$statusPending => 'Pending',
            self::$statusNew => 'New',
            self::$statusSent => 'Sent',
            self::$statusReceived => 'Received',
            self::$statusCompleted => 'Completed',
            self::$statusReturned => 'Returned',
        ];
    }

    public function getSubtotalFormat()
    {
        return number_format($this->subtotal, 2, ',', '.');
    }

    public function getTaxFormat()
    {
        return number_format($this->tax, 2, ',', '.');
    }

    public function getTotalShippingCostFormat()
    {
        return number_format($this->total_shipping_cost, 2, ',', '.');
    }

    public function getUserTypeAttribute()
    {
        return $this->user_member_id > 0 ? trans('cms.member') : trans('cms.guest');
    }

    public function getVoucherAmount()
    {
        $voucherAmount = 0;

        if ($this->voucher_type == Vouchers::$typeShippingOnly) {
            $subtotalOrTotalShippingCost = $this->total_shipping_cost;
        } else if ($this->voucher_type == Vouchers::$typeTotalOnly) {
            $subtotalOrTotalShippingCost = $this->subtotal;
        }
       
        if ($this->voucher_unit == Vouchers::$unitAmount) {
            $voucherAmount = $subtotalOrTotalShippingCost >= $this->voucher_value ? $this->voucher_value : ($subtotalOrTotalShippingCost );
        } else if ($this->voucher_unit == Vouchers::$unitPercentage) {
            $voucherAmount = $subtotalOrTotalShippingCost >= $subtotalOrTotalShippingCost * $this->voucher_value / 100
                ? $subtotalOrTotalShippingCost * $this->voucher_value / 100
                : $subtotalOrTotalShippingCost;
        }

        return $voucherAmount;
    }

    public function getVoucherAmountFormat()
    {
        return number_format($this->getVoucherAmount(), 0, ',', '.');
    }

    public function nicepayV1CallBackUrls()
    {
        return $this->hasMany(\Modules\Nicepay\Models\CallBackUrl::class, 'referenceNo', 'id');
    }

    public function nicepayV1CheckTransactionStatuses()
    {
        return $this->hasMany(\Modules\Nicepay\Models\CheckTransactionStatus::class, 'referenceNo', 'id');
    }

    public function nicepayV1DbProcessUrls()
    {
        return $this->hasMany(\Modules\Nicepay\Models\dbProcessUrl::class, 'referenceNo', 'id');
    }

    public function nicepayV1EnterpriseRegistrationResponse()
    {
        return $this->hasOne(\Modules\Nicepay\Models\Enterprise\RegistrationResponse::class, 'referenceNo', 'id');
    }

    public function nicepayV1EnterpriseRegistrationResponses()
    {
        return $this->hasMany(\Modules\Nicepay\Models\Enterprise\RegistrationResponse::class, 'referenceNo', 'id');
    }

    public function nicepayV1EnterpriseRegistration()
    {
        return $this->hasOne(\Modules\Nicepay\Models\Enterprise\Registration::class, 'referenceNo', 'id')->latest();
    }

    public function nicepayV1EnterpriseRegistrations()
    {
        return $this->hasMany(\Modules\Nicepay\Models\Enterprise\Registration::class, 'referenceNo', 'id');
    }

    public function nicepayV1ProfessionalRegistrations()
    {
        return $this->hasMany(\Modules\Nicepay\Models\Professional\Registration::class, 'referenceNo', 'id');
    }

    public function orderDetails()
    {
        return $this->hasMany(\Modules\Orders\Models\OrderDetail::class, 'order_id');
    }

    public function orderRefund()
    {
        return $this->hasOne(\Modules\Orders\Models\OrderRefund::class, 'order_id');
    }

    public function orderShippingAddress()
    {
        return $this->hasOne(OrderShippingAddress::class);
    }

    public function orderTransactionStatuses()
    {
        return $this->hasMany(\Modules\Orders\Models\OrderTransactionStatus::class, 'order_id');
    }

    public function setInvoiceNumberById($id)
    {
        $intToRoman = new \Romans\Filter\IntToRoman;

        if ($this->created_at) {
            $date = date('d');
            $month = date('m');
            $year = date('y');
        } else {
            $date = $this->created_at->format('d');
            $month = $this->created_at->format('m');
            $year = $this->created_at->format('y');
        }

        $monthRoman = $intToRoman->filter($month);
        $yearRoman = $intToRoman->filter($year);
        $orderNo = str_pad($this->order_no, 4, '0', STR_PAD_LEFT);

        $this->invoice_number = 'INV/'.$yearRoman.'/'.$monthRoman.'/'.$date.$orderNo;
        return $this;
    }

    public function user()
    {
        return $this->belongsTo(\Modules\Users\Models\User::class, 'user_member_id')->withTrashed();
    }

    public function voucher()
    {
        return $this->belongsTo(\Modules\Vouchers\Models\Vouchers::class, 'voucher_id')->withTrashed();
    }
    
    public function getOcNumber($id){
		$kd_oc =0;
		$oc = DB::select("
				SELECT confirmationNumber AS oc_number FROM confirmation_number where confirmation_number.order_id='$id'
		");
		
		 foreach($oc as $row){
			 $kd_oc = $row->oc_number;
		 }
		
		return $kd_oc;
	}

    public function getUserName()
    {
        return $this->user ? $this->user->name : 'Guest';
    }

    public function product()
    {
        return $this->belongsTo(\Modules\Products\Models\Product::class, 'product_id')->withTrashed();
    }

    public function getPaymentDateAttribute($value)
    {
        return $value ? $value : '-';
    }

    public function getPaymentStatusAttribute($value)
    {
        return $value == 0 ? 'Unpaid' : 'Paid';
    }

    public function orderShippingMethod()
    {
        return $this->hasOne('Modules\Orders\Models\OrderShippingMethod');
    }

    public function userMember()
    {
        return $this->belongsTo(\Modules\Users\Models\User::class, 'user_member_id')->withTrashed();
    }

    public function getStatusDate()
    {
        return $this->hasOne('Modules\Orders\Models\OrderTransactionStatus')->where('status',$this->status);
    }

    public function orderTransactionStatus()
    {
        return $this->hasMany('Modules\Orders\Models\OrderTransactionStatus');
    }

    public function getCanRefund()
    {
        $canRefund = false;

        // 1. get order_transaction_status where status is Received and created_at + 2 weeks >= now then can refund = true
        if ($orderTransactionStatuses = $this->orderTransactionStatus) {
            foreach ($orderTransactionStatuses as $orderTransactionStatus) {
                if ($orderTransactionStatus->status == 'Received'
                    && $orderTransactionStatus->created_at->addWeek(2)->gt( \Carbon\Carbon::now() )
                ) {
                    if(!$this->orderRefund){
                        $canRefund = true;
                    }

                }
            }
        }

        return $canRefund;
    }

    public function getReviewCount()
    {
        return \Modules\Products\Models\Review::where('order_id',$this->id)->get();
    }

    public function getStatusText()
    {
        switch ($this->status) {
            case 'Pending':
                return trans('cms.waiting_for_payment');
                break;
            case 'New':
                return trans('cms.in_progress');
                break;
            case 'Sent':
                return trans('cms.shipping');
                break;
            case 'Received':
                return trans('cms.delivered');
                break;
            default:
                return trans('cms.complete');
                break;
        }
    }

    /*
    public function scopeBestMembers($query, $params)
    {
        if (isset($params['from']) && isset($params['to'])) {
            $query->whereBetween('orders.date', [$params['from'], $params['to']]);
        }

        return $query;
    }

    public function scopeBestMembersLocation($query, $params)
    {
        if (isset($params['from']) && isset($params['to'])) {
            $query->whereBetween('orders.date', [$params['from'], $params['to']]);
        }

        return $query;
    }

    public function scopeOrdersAmount($query, $params)
    {
        if (isset($params['from']) && isset($params['to'])) {
            $query->whereBetween('orders.date', [$params['from'], $params['to']]);
        }

        return $query;
    }

    public function scopeSalesAmount($query, $params)
    {
        if (isset($params['from']) && isset($params['to'])) {
            $query->whereBetween('orders.date', [$params['from'], $params['to']]);
        }

        return $query;
    }

    public function scopeQuantityAmount($query, $params)
    {
        if (isset($params['from']) && isset($params['to'])) {
            $query->whereBetween('orders.date', [$params['from'], $params['to']]);
        }

        return $query;
    }

    public function scopeOrders($query, $params)
    {
        if (isset($params['from']) && isset($params['to'])) {
            $query->whereBetween('orders.date', [$params['from'], $params['to']]);
        }

        return $query;
    }
    */

    public function scopeWhereBetweenDate($query, $params)
    {
        if (isset($params['from']) && isset($params['to'])) {
            $query->whereBetween('orders.date', [$params['from'], $params['to']]);
        }

        return $query;
    }

    public function scopeWhereTransactions($query, $params)
    {
        if (isset($params['status'])) {
            $query->where('status', [$params['status']]);
        }

        if (isset($params['from']) && isset($params['to'])) {
            $query->whereBetween('date', [$params['from'], $params['to']]);
        }

        return $query;
    }








}
