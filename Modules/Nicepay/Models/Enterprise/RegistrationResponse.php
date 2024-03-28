<?php

namespace Modules\Nicepay\Models\Enterprise;

use Illuminate\Database\Eloquent\Model;
use Modules\Nicepay\Models\NicepayCode;
use Modules\Nicepay\Models\NicepayCode\PaymentMethod;
use Modules\Pages\Models\Page;

class RegistrationResponse extends Model
{
    protected $fillable = [
        'resultCd',
        'resultMsg',
        'tXid',
        'referenceNo',

        'payMethod',
        'amount',
        'currency',
        'goodsNm',
        'billingNm',

        'transDt',
        'transTm',
        'description',
        'callbackUrl',
        'authNo',

        'issuBankCd',
        'issuBankNm',
        'cardNo',
        'instmntMon;',
        'istmntType',

        'recurringToken',
        'preauthToken',
        'ccTransType',
        'vat',
        'free',

        'notaxAmt',
        'bankCd',
        'bankVacctNo',
        'vacctValidDt',
        'vacctValidTm',

        'mitraCd',
        'payNo',
        'payValidTm',
        'payValidDt',
        'receiptCode',

        'mRefNo',
        'data',
    ];

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    protected $table = 'nicepay__v1__enterprise__registration__response';

    public $nicepayCode;
    public $page;

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);
        $this->nicepayCode = new NicepayCode;
        $this->page        = new Page;
    }

    public function getAmountFormat()
    {
        return number_format($this->amount);
    }

    public function getBank()
    {
        $bankOptions = $this->nicepayCode->getBankCodeOptions();
        return $bankOptions[$this->bankCd];
    }

    public function getMitra()
    {
        $mitraOptions = $this->nicepayCode->getMitraCodeOptions();
        return $mitraOptions[$this->mitraCd];
    }

    public function getPaymentExpiredAt()
    {
        $paymentMethodExpiredAt = null;

        if ($this->payMethod == PaymentMethod::$virtualAccount) {
            $paymentMethodExpiredAt = \Carbon\Carbon::createFromFormat('YmdHis', $this->vacctValidDt . $this->vacctValidTm)->toDateTimeString();
        } elseif ($this->payMethod == PaymentMethod::$cvsConvenienceStore) {
            $paymentMethodExpiredAt = \Carbon\Carbon::createFromFormat('YmdHis', $this->payValidDt . $this->payValidTm)->toDateTimeString();
        }

        return $paymentMethodExpiredAt;
    }

    public function getPaymentHowToPays()
    {
        $paymentHowToPays = [];

        if ($this->payMethod == PaymentMethod::$virtualAccount) {
            $paymentHowToPays = [
                [
                    'code'    => 'atm',
                    'title'   => trans('cms.atm'),
                    'content' => $this->page->getContentByTemplate('how_to_pay__nicepay_v1__' . $this->payMethod . '__' . $this->bankCd . '__atm'),
                ],
                [
                    'code'    => 'internet_banking',
                    'title'   => trans('cms.internet_banking'),
                    'content' => $this->page->getContentByTemplate('how_to_pay__nicepay_v1__' . $this->payMethod . '__' . $this->bankCd . '__internet_banking'),
                ],
                [
                    'code'    => 'mobile_banking',
                    'title'   => trans('cms.mobile_banking'),
                    'content' => $this->page->getContentByTemplate('how_to_pay__nicepay_v1__' . $this->payMethod . '__' . $this->bankCd . '__mobile_banking'),
                ],
            ];
        } elseif ($this->payMethod == PaymentMethod::$cvsConvenienceStore) {
            $paymentHowToPays = [
                [
                    'code'    => 'mitra',
                    'title'   => $this->getMitra(),
                    'content' => $this->page->getContentByTemplate('how_to_pay__nicepay_v1__' . $this->payMethod . '__' . $this->mitraCd),
                ],
            ];
        }

        return $paymentHowToPays;
    }

    public function getPaymentMethodAccount()
    {
        $paymentMethodAccount = null;

        if ($this->payMethod == PaymentMethod::$virtualAccount) {
            $paymentMethodAccount = $this->getBank();
        } elseif (in_array($this->payMethod, [PaymentMethod::$cvsConvenienceStore, PaymentMethod::$clickPay, PaymentMethod::$eWallet])) {
            $paymentMethodAccount = $this->getMitra();
        }

        return $paymentMethodAccount;
    }

    public function getPaymentMethodAccountLabel()
    {
        $paymentMethodAccountLabel = null;

        if ($this->payMethod == PaymentMethod::$virtualAccount) {
            $paymentMethodAccountLabel = trans('cms.bank');
        } elseif (in_array($this->payMethod, [PaymentMethod::$cvsConvenienceStore, PaymentMethod::$clickPay, PaymentMethod::$eWallet])) {
            $paymentMethodAccountLabel = trans('cms.mitra');
        }

        return $paymentMethodAccountLabel;
    }

    public function getPaymentNumber()
    {
        $paymentNumber = null;

        if ($this->payMethod == PaymentMethod::$virtualAccount) {
            $paymentNumber = $this->vacctNo;
        } elseif ($this->payMethod == PaymentMethod::$cvsConvenienceStore) {
            $paymentNumber = $this->payNo;
        }

        return $paymentNumber;
    }

    public function getPaymentNumberLabel()
    {
        $paymentNumberLabel = null;

        if ($this->payMethod == PaymentMethod::$virtualAccount) {
            $paymentNumberLabel = trans('cms.no_virtual_account');
        } elseif (in_array($this->payMethod, [PaymentMethod::$virtualAccount, PaymentMethod::$cvsConvenienceStore])) {
            $paymentNumberLabel = trans('cms.payment_number');
        }

        return $paymentNumberLabel;
    }

    public function nicepayV1EnterpriseRegistration()
    {
        return $this->belongsTo(\Modules\Nicepay\Models\Enterprise\Registration::class, 'tXid', 'tXid');
    }
}
