<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Orders\Models\OrderShippingAddress;

class AlterTableOrderShippingAddressesAddColoumnProvinceRegencyDistrict extends Migration
{
    protected $model;

    public function __construct()
    {
        $this->model = new OrderShippingAddress;
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasColumn($this->model->getTable(), 'province')) {
            Schema::table($this->model->getTable(), function(Blueprint $table) {
                $table->string('province')->after('province_id');
            });
        }

        if (! Schema::hasColumn($this->model->getTable(), 'regency')) {
            Schema::table($this->model->getTable(), function(Blueprint $table) {
                $table->string('regency')->after('regency_id');
            });
        }

        if (! Schema::hasColumn($this->model->getTable(), 'district')) {
            Schema::table($this->model->getTable(), function(Blueprint $table) {
                $table->string('district')->after('district_id');
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
        if (Schema::hasColumn($this->model->getTable(), 'province')) {
            Schema::table($this->model->getTable(), function(Blueprint $table) {
                $table->dropColumn('province');
            });
        }

        if (Schema::hasColumn($this->model->getTable(), 'regency')) {
            Schema::table($this->model->getTable(), function(Blueprint $table) {
                $table->dropColumn('regency');
            });
        }

        if (Schema::hasColumn($this->model->getTable(), 'district')) {
            Schema::table($this->model->getTable(), function(Blueprint $table) {
                $table->dropColumn('district');
            });
        }
    }
}
