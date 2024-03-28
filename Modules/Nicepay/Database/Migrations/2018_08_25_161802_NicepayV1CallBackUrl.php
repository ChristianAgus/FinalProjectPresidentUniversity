<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Nicepay\Models\CallBackUrl;

class NicepayV1CallBackUrl extends Migration
{
    protected $model;

    public function __construct()
    {
        $this->model = new CallBackUrl;
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

            $table->bigInteger('amount')->nullable()->comment('Transaction Amount');
            $table->string('transDt')->nullable()->comment('Registration date');
            $table->string('transTm')->nullable()->comment('Registration time');
            $table->text('description')->nullable()->comment('Transaction Description');
            $table->string('receiptCode')->nullable()->comment('Click Pay.Authorization Number');

            $table->string('payNo')->nullable()->comment('Convenience Store.Payment Number');
            $table->string('mitraCd')->nullable()->comment('Convenience Store.Mitra Code');
            $table->string('authNo')->nullable()->comment('Credit Card.Authorization Number');
            $table->string('bankVacctNo')->nullable()->comment('Virtual Account.Bank Virtual Account Number');
            $table->string('bankCd')->nullable()->comment('Virtual Account.Bank Code');

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
