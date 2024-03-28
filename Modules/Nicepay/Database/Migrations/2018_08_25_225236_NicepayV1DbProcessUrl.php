<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Nicepay\Models\DbProcessUrl;

class NicepayV1DbProcessUrl extends Migration
{
    protected $model;

    public function __construct()
    {
        $this->model = new DbProcessUrl;
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
            $table->string('tXid')->nullable()->comment('Transaction ID');
            $table->string('merchantToken')->nullable()->comment('Merchant Token');
            $table->string('referenceNo')->nullable()->comment('Merchant Order No');
            $table->string('payMethod')->nullable()->comment('Payment method');

            $table->bigInteger('amt')->nullable()->comment('Payment amount');
            $table->string('transDt')->nullable()->comment('Transaction date');
            $table->string('transTm')->nullable()->comment('Transaction time');
            $table->string('currency')->nullable()->comment('Currency');
            $table->string('goodsNm')->nullable()->comment('Goods name');

            $table->string('billingNm')->nullable()->comment('Billing name');
            $table->string('matchCI')->nullable()->comment('Payment amount match flag');
            $table->string('status')->nullable();
            $table->string('authNo')->nullable()->comment('Credit Card.Approval number');
            $table->string('IssueBankCd')->nullable()->comment('Credit Card.Issue bank code');

            $table->string('IssueBankNm')->nullable()->comment('Credit Card.Issue bank name');
            $table->string('acquBankCd')->nullable()->comment('Credit Card.Acquire bank code');
            $table->string('acquBankNm')->nullable()->comment('Credit Card.Acquire bank name');
            $table->string('cardNo')->nullable()->comment('Credit Card.Card no with masking');
            $table->string('caadExpYymm')->nullable()->comment('Credit Card.Card expiry (YYMM)');

            $table->string('InstmntMon')->nullable()->comment('Credit Card.Installment month');
            $table->string('instmntType')->nullable()->comment('Credit Card.Installment Type');
            $table->string('preauthToken')->nullable()->comment('Credit Card.Preauth Token');
            $table->string('recurringToken')->nullable()->comment('Credit Card.Recurring token');
            $table->string('ccTransType')->nullable()->comment('Credit Card.Credit card transaction type');

            $table->string('vat')->nullable()->comment('Credit Card.Vat number');
            $table->bigInteger('fee')->nullable()->comment('Credit Card.service fee');
            $table->bigInteger('notaxAmt')->nullable()->comment('Credit Card.tax free amount');
            $table->string('mitraCd')->nullable()->comment('Others Payment Method.Mitra Code');
            $table->string('payNo')->nullable()->comment('Others Payment Method.CVS number');

            $table->string('payValidDt')->nullable()->comment('Others Payment Method.CVS expiry date');
            $table->string('payValidTm')->nullable()->comment('Others Payment Method.CVS expiry time');
            $table->string('receiptCode')->nullable()->comment('Others Payment Method.Authorization number');
            $table->string('mRefNo')->nullable()->comment('Others Payment Method.Bank reference No');
            $table->string('depositDt')->nullable()->comment('Others Payment Method/Virtual Account.Deposit date');

            $table->string('depositTm')->nullable()->comment('Others Payment Method/Virtual Account.Deposit time');
            $table->string('bankCd')->nullable()->comment('Virtual Account.Bank Code');
            $table->string('vacctNo')->nullable()->comment('Virtual Account.Bank Virtual Account number');
            $table->string('vacctValidDt')->nullable()->comment('Virtual Account.VA expiry date');
            $table->string('vacctValidTm')->nullable()->comment('Virtual Account.VA expiry time');

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
