<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Nicepay\Models\CheckTransactionStatus;

class NicepayV1CheckTransactionStatus extends Migration
{
    protected $model;

    public function __construct()
    {
        $this->model = new CheckTransactionStatus;
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
            $table->string('iMid')->nullable()->comment('Merchant ID');

            $table->string('referenceNo')->nullable()->comment('Merchant Order No');
            $table->string('payMethod')->nullable()->comment('Payment method');
            $table->bigInteger('amt')->nullable()->comment('Payment amount');
            $table->bigInteger('cancelAmt')->nullable()->comment('Cancel amount');
            $table->string('reqDt')->nullable()->comment('Transaction request date');

            $table->string('reqTm')->nullable()->comment('Transaction request time');
            $table->string('transDt')->nullable()->comment('Transaction date');
            $table->string('transTm')->nullable()->comment('Transaction time');
            $table->string('depositDt')->nullable()->comment('Transaction deposit date');
            $table->string('depositTm')->nullable()->comment('Transaction deposit time');

            $table->string('currency')->nullable()->comment('Currency');
            $table->string('goodsNm')->nullable()->comment('Goods name');
            $table->string('billingNm')->nullable()->comment('Billing name');
            $table->string('status')->nullable()->comment('Transaction status');
            $table->string('authNo')->nullable()->comment('Approval number');

            $table->string('IssueBankCd')->nullable()->comment('Issue bank code');
            $table->string('acquBankCd')->nullable()->comment('Acquire bank code');
            $table->string('cardNo')->nullable()->comment('Card no with masking');
            $table->string('InstmntMon')->nullable()->comment('Installment month');
            $table->string('instmntType')->nullable()->comment('Installment Type');

            $table->string('preauthToken')->nullable()->comment('Preauth Token');
            $table->string('recurringToken')->nullable()->comment('Recurring token');
            $table->string('ccTransType')->nullable()->comment('Credit card transaction type');
            $table->string('acquStatus')->nullable()->comment('Purchase status');
            $table->string('vat')->nullable()->comment('Vat number');

            $table->bigInteger('fee')->nullable()->comment('service fee');
            $table->bigInteger('notaxAmt')->nullable()->comment('tax free amount');
            $table->string('mitraCd')->nullable()->comment('Others Payment Method.Mitra Code');
            $table->string('payNo')->nullable()->comment('Others Payment Method.CVS number');
            $table->string('payValidDt')->nullable()->comment('Others Payment Method.CVS expiry date');

            $table->string('payValidTm')->nullable()->comment('Others Payment Method.CVS expiry time');
            $table->string('receiptCode')->nullable()->comment('Others Payment Method.Authorization number');
            $table->string('mRefNo')->nullable()->comment('Others Payment Method.Bank reference No');
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
