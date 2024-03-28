<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Nicepay\Models\Professional\Registration;

class AlterTableNicepayV1NicepayProfessionalTransactionRegistrationRenameToNicepayV1ProfessionalRegistration extends Migration
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
        if (Schema::hasTable('nicepay__v1__nicepay_professional__transaction_registration')) {
            Schema::rename('nicepay__v1__nicepay_professional__transaction_registration', $this->model->getTable());
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable($this->model->getTable())) {
            Schema::rename($this->model->getTable(), 'nicepay__v1__nicepay_professional__transaction_registration');
        }
    }
}
