<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddWaybillToOrderShippingMethodTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       
       
        if (! Schema::hasColumn('order_shipping_method', 'waybill')) {
            Schema::table('order_shipping_method', function (Blueprint $table) {
                $table->string('waybill')->after('order_id');
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
        if (! Schema::hasColumn('order_shipping_method', 'waybill')) {
            Schema::table('order_shipping_method', function (Blueprint $table) {
                $table->dropColumn('waybill');
            });
        }
        
    }
}
