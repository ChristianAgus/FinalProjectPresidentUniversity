<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Nicepay\Models\Enterprise\Registration;

class NicepayV1EnterpriseRegistration extends Migration
{
    protected $model;

    public function __construct()
    {
        $this->model = new Registration;
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->model->getTable(), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('iMid')->nullable()->comment('Merchant ID');
            $table->string('merchantKey')->nullable()->comment('Merchant Key');
            $table->string('payMethod')->nullable()->comment('Payment Method');
            $table->string('currency')->nullable()->comment('Currency');

            $table->bigInteger('amt')->nullable()->comment('Goods Amount');
            $table->string('referenceNo')->nullable()->comment('Merchant Order No');
            $table->string('goodsNm')->nullable()->comment('Goods Name');
            $table->string('billingNm')->nullable()->comment('Billing Name');
            $table->string('billingPhone')->nullable()->comment('Billing Phone Number');

            $table->string('billingEmail')->nullable()->comment('Billing Email');
            $table->string('billingCity')->nullable()->comment('Billing City');
            $table->string('billingState')->nullable()->comment('Billing State');
            $table->string('billingPostCd')->nullable()->comment('Billing Post Number');
            $table->string('billingCountry')->nullable()->comment('Billing Country');

            $table->string('callBackUrl')->nullable()->comment('Payment Result Forward Url (On Browser)');
            $table->string('dbProcessUrl')->nullable()->comment('Payment Result Receive Url (Server Side)');
            $table->string('description')->nullable()->comment('Description');
            $table->string('merchantToken')->nullable()->comment('Merchant Token');
            $table->string('userIP')->nullable()->comment('User IP (Customer)');

            $table->longText('cartData')->nullable()->comment('Cart Data (Json Format)');
            $table->string('instmntType')->nullable()->comment('Credit Card.Installment Type');
            $table->string('instmntMon')->nullable()->comment('Credit Card.Installment Month');
            $table->string('cardCvv')->nullable()->comment('Credit Card.Card CVV');
            $table->string('onePassToken')->nullable()->comment('Credit Card.One time use transaction token(Created by onePassToken.do)');

            $table->string('recurrOpt')->nullable()->comment('Credit Card.Recurring option');
            $table->string('bankCd')->nullable()->comment('Virtual Account.Bank Code');
            $table->string('vacctValidDt')->nullable()->comment('Virtual Account.VA expiry date (YYYYMMDD)');
            $table->string('vacctValidTm')->nullable()->comment('Virtual Account.VA expiry time (HH24MISS)');
            $table->string('mitraCd')->nullable()->comment('ClickPay/Convenience Store/E-Wallet.Mitra Code');

            $table->string('clickPayNo')->nullable()->comment('ClickPay.Clickpay card number');
            $table->string('dataField3')->nullable()->comment('ClickPay.Token input 3 for Clickpay');
            $table->string('clickPayToken')->nullable()->comment('ClickPay.Code response from token');
            $table->string('payValidDt')->nullable()->comment('Convenience Store.CVS valid date');
            $table->string('payValidTm')->nullable()->comment('Convenience Store.CVS valid time');

            $table->string('billingAddr')->nullable()->comment('Billing Address');
            $table->string('deliveryNm')->nullable()->comment('Delivery Name');
            $table->string('deliveryPhone')->nullable()->comment('Delivery Phone');
            $table->string('deliveryAddr')->nullable()->comment('Delivery Address');
            $table->string('deliveryEmail')->nullable()->comment('Delivery Email');

            $table->string('deliveryCity')->nullable()->comment('Delivery City');
            $table->string('deliveryState')->nullable()->comment('Delivery State');
            $table->string('deliveryPostCd')->nullable()->comment('Delivery Post Number');
            $table->string('deliveryCountry')->nullable()->comment('Delivery Country');
            $table->string('vat')->nullable()->comment('Vat');

            $table->bigInteger('fee')->nullable()->comment('Service Tax');
            $table->bigInteger('notaxAmt')->nullable()->comment('Tax Free Amount');
            $table->string('reqDt')->nullable()->comment('Request Date(YYYYMMDD)');
            $table->string('reqTm')->nullable()->comment('Request Time(HH24MISS)');
            $table->string('reqDomain')->nullable()->comment('Request Domain');

            $table->string('reqServerIP')->nullable()->comment('Request Server IP');
            $table->string('reqClientVer')->nullable()->comment('Request Client Version');
            $table->string('userSessionID')->nullable()->comment('User Session ID');
            $table->string('userAgent')->nullable()->comment('User Agent Information');
            $table->string('userLanguage')->nullable()->comment('User language');

            $table->string('tXid')->nullable()->comment('Transaction ID');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->model->getTable());
    }
}
