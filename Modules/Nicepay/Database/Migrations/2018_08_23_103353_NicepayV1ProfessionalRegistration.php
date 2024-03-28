<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class NicepayV1ProfessionalRegistration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('nicepay__v1__nicepay_professional__transaction_registration')) {
            Schema::create('nicepay__v1__nicepay_professional__transaction_registration', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('iMid')->nullable()->comment('Merchant ID');
                $table->string('merchantKey')->nullable()->comment('Merchant Key');
                $table->string('merchantToken')->nullable()->comment('Merchant Token');
                $table->string('payMethod')->nullable()->comment('Payment Method');

                $table->string('currency')->nullable()->comment('Currency');
                $table->bigInteger('amt')->nullable()->comment('Transaction Amount');
                $table->string('instmntType')->nullable()->comment('Installment Type');
                $table->string('instmntMon')->nullable()->comment('Installment Month');
                $table->string('referenceNo')->nullable()->comment('Merchant Order Number');

                $table->string('goodsNm')->nullable()->comment('Goods Name');
                $table->string('billingNm')->nullable()->comment('Billing Name');
                $table->string('billingPhone')->nullable()->comment('Billing phone number');
                $table->string('billingEmail')->nullable()->comment('Billing email');
                $table->string('billingAddr')->nullable()->comment('Billing address');

                $table->string('billingCity')->nullable()->comment('Billing city');
                $table->string('billingState')->nullable()->comment('Billing state');
                $table->string('billingPostCd')->nullable()->comment('Billing postcode');
                $table->string('billingCountry')->nullable()->comment('Billing country');
                $table->string('deliveryNm')->nullable()->comment('Delivery name');

                $table->string('deliveryPhone')->nullable()->comment('Delivery phone number');
                $table->string('deliveryAddr')->nullable()->comment('Delivery address');
                $table->string('deliveryCity')->nullable()->comment('Delivery city');
                $table->string('deliveryState')->nullable()->comment('Delivery state');
                $table->string('deliveryPostCd')->nullable()->comment('Delivery postcode');

                $table->string('deliveryCountry')->nullable()->comment('Delivery Country');
                $table->string('callBackUrl')->comment('Payment result forward url');
                $table->string('dbProcessUrl')->comment('Payment notification');
                $table->string('vat')->nullable()->comment('Vat Number');
                $table->bigInteger('fee')->nullable()->comment('Service fee');

                $table->bigInteger('notaxAmt')->nullable()->comment('Tax free amount');
                $table->string('description')->comment('Transaction description');
                $table->string('reqDt')->nullable()->comment('Request date');
                $table->string('reqTm')->nullable()->comment('Request time');
                $table->string('reqDomain')->nullable()->comment('Request domain');

                $table->string('reqServerIP')->nullable()->comment('Request Server IP address');
                $table->string('reqClientVer')->nullable()->comment('Request client version');
                $table->string('userIP')->nullable()->comment('User IP address');
                $table->string('userSessionID')->nullable()->comment('User session ID');
                $table->string('userAgent')->nullable()->comment('User agent information');

                $table->string('userLanguage')->nullable()->comment('User language');
                $table->string('recurrOpt')->nullable()->comment('Recurring option');
                $table->longText('cartData')->nullable()->comment('JSON Format');
                $table->string('worker')->nullable()->comment('worker');
                $table->string('merFixAcctId')->nullable()->comment('Merchant fix virtual account sign value');

                $table->string('vacctValidDt')->nullable()->comment('Virtual account valid date');
                $table->string('vacctValidTm')->nullable()->comment('Virtual account valid time');
                $table->string('paymentExpDt')->nullable()->comment('Permit time check date');
                $table->string('paymentExpTm')->nullable()->comment('Permit time check time');
                $table->string('payValidDt')->nullable()->comment('CVS valid date');

                $table->string('payValidTm')->nullable()->comment('CVS valid time');
                $table->string('tXid')->nullable()->comment('Direct migs 3rd party add.');
                $table->string('mitraCd')->nullable()->comment('Mitra Code');
                $table->string('mRefNo')->nullable()->comment('Bank Reference No.');
                $table->string('timeStamp')->nullable()->comment('Timestamp');

                $table->string('version')->nullable();
                $table->string('optDisplayCB')->nullable()->comment('Option display change button');
                $table->string('optDisplayBL')->nullable()->comment('Option display back URL link');
                $table->string('isCheckPaymentExptDt')->nullable()->comment('Check Payment Expiry Date');

                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nicepay__v1__nicepay_professional__transaction_registration');
    }
}
