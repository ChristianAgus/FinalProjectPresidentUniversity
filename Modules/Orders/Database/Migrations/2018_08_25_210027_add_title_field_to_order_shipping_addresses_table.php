<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTitleFieldToOrderShippingAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasColumn('order_shipping_addresses', 'title')) {
            Schema::table('order_shipping_addresses', function (Blueprint $table) {
                $table->string('title')->after('order_id');
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
        if (Schema::hasColumn('order_shipping_addresses', 'title')) {
            Schema::table('order_shipping_addresses', function (Blueprint $table) {
                $table->dropColumn('title');
            });
        }
    }
}
