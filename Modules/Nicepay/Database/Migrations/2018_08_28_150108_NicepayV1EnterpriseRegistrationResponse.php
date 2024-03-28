<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Nicepay\Models\Enterprise\RegistrationResponse;

class NicepayV1EnterpriseRegistrationResponse extends Migration
{
    protected $model;

    public function __construct()
    {
        $this->model = new RegistrationResponse;
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
            $table->string('resultCd')->nullable()->comment('Result Code');
            $table->string('resultMsg')->nullable()->comment('Result Message');
            $table->string('tXid')->nullable()->comment('Transaction ID');
            $table->string('referenceNo')->nullable()->comment('Merchant Order No');

            $table->string('payMethod')->nullable()->comment('Payment Method');
            $table->bigInteger('amount')->nullable()->comment('Transaction Amount');
            $table->string('currency')->nullable()->comment('Currency');
            $table->string('goodsNm')->nullable()->comment('Goods Name');
            $table->string('billingNm')->nullable()->comment('Buyer Name');

            $table->string('transDt')->nullable()->comment('Transaction date (YYYYMMDD)');
            $table->string('transTm')->nullable()->comment('Transaction time (HH24MISS)');
            $table->string('description')->nullable()->comment('Transaction description');
            $table->string('callbackUrl')->nullable()->comment('Callback Url');
            $table->string('authNo')->nullable()->comment('Credit Card.Authorization Number');

            $table->string('issuBankCd')->nullable()->comment('Credit Card.Issue Bank Code');
            $table->string('issuBankNm')->nullable()->comment('Credit Card.Issue Bank Name');
            $table->string('cardNo')->nullable()->comment('Credit Card.Card Number (Masked)');
            $table->string('instmntMon')->nullable()->comment('Credit Card.Installment month');
            $table->string('istmntType')->nullable()->comment('Credit Card.Installment type');

            $table->string('recurringToken')->nullable()->comment('Credit Card.Token for Recurring Payment');
            $table->string('preauthToken')->nullable()->comment('Credit Card.Token for Preauth Payment');
            $table->string('ccTransType')->nullable()->comment('Credit Card.Credit Card Transaction Type');
            $table->string('vat')->nullable()->comment('Credit Card.Vat number');
            $table->bigInteger('fee')->nullable()->comment('Credit Card.Service fee');

            $table->bigInteger('notaxAmt')->nullable()->comment('Credit Card.Tax free amount');
            $table->string('bankCd')->nullable()->comment('Virtual Account.Bank Code');
            $table->string('bankVacctNo')->nullable()->comment('Virtual Account.Bank Virtual Account Number');
            $table->string('vacctValidDt')->nullable()->comment('Virtual Account.VA expiry date');
            $table->string('vacctValidTm')->nullable()->comment('Virtual Account.Va expiry time');

            $table->string('mitraCd')->nullable()->comment('Convenience Store/E-Wallet.Mitra Code');
            $table->string('payNo')->nullable()->comment('Convenience Store.CVS Number');
            $table->string('payValidTm')->nullable()->comment('Convenience Store.CVS Expiry Time (HH24MISS)');
            $table->string('payValidDt')->nullable()->comment('Convenience Store.CVS Expiry Date (YYYYMMDD)');
            $table->string('receiptCode')->nullable()->comment('Click Pay.Authorization Number');

            $table->string('mRefNo')->nullable()->comment('Click Pay.Bank Reference No');
            $table->longText('data')->nullable();

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
