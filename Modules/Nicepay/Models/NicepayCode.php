<?php

namespace Modules\Nicepay\Models;

use Modules\Nicepay\Models\NicepayCode\BankCode;
use Modules\Nicepay\Models\NicepayCode\InstallmentType;
use Modules\Nicepay\Models\NicepayCode\MitraCode;
use Modules\Nicepay\Models\NicepayCode\PaymentMethod;

class NicepayCode
{
    public function getBankCodeOptions()
    {
        return [
            BankCode::$bankMandiri => 'Bank Mandiri',
            BankCode::$bankInternationalIndonesiaMaybank => 'Bank International Indonesia Maybank',
            BankCode::$bankPermata => 'Bank Permata',
            BankCode::$bankCentralAsia => 'Bank Central Asia',
            BankCode::$bankNegaraIndonesia46 => 'Bank Negara Indonesia 46',

            BankCode::$bankKEBHanaIndonesia => 'Bank KEB Hana Indonesia',
            BankCode::$bankRakyatIndonesia => 'Bank Rakyat Indonesia',
            BankCode::$bankCIMBNiagaTBK => 'Bank PT. BANK CIMB NIAGA, TBK.',
            BankCode::$bankDanamonIndonesiaTBK => 'Bank PT. BANK DANAMON INDONESIA, TBK',
            BankCode::$etcUnknown => 'etc, unknown',
        ];
    }

    public function getInstallmentTypeOptions()
    {
        return [
            InstallmentType::$customerCharge => 'Customer charge',
            InstallmentType::$merchantCharge => 'Merchant charge',
        ];
    }

    public function getMitraCodeOptions()
    {
        return [
            MitraCode::$cvsAlfamart => 'CVS Alfamart',
            MitraCode::$cvsIndomaret => 'CVS Indomaret',
            MitraCode::$cvsLawson => 'CVS Lawson',
            MitraCode::$cvsAlfaMidi => 'CVS AlfaMidi',
            MitraCode::$cvsDanDan => 'CVS Dan+Dan',

            MitraCode::$clickPayMandiri => 'ClickPay Mandiri',
            MitraCode::$clickPayBca => 'ClickPay BCA',
            MitraCode::$clickPayCimb => 'ClickPay CIMB',
            MitraCode::$eWalletMandiri => 'E-Wallet Mandiri',
            MitraCode::$eWalletBcaSakuku => 'E-Wallet BCA(Sakuku)',
        ];
    }

    public function getPaymentMethodOptions()
    {
        return [
            PaymentMethod::$creditCard => 'Credit Card',
            PaymentMethod::$virtualAccount => 'Virtual Account',
            PaymentMethod::$cvsConvenienceStore => 'CVS (Convenience Store)',
            PaymentMethod::$clickPay => 'ClickPay',
            PaymentMethod::$eWallet => 'E-Wallet',
        ];
    }
}
