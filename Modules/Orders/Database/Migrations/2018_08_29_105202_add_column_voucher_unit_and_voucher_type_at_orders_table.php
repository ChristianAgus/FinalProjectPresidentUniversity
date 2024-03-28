<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnVoucherUnitAndVoucherTypeAtOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasColumn('orders', 'voucher_unit')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->string('voucher_unit')->after('voucher_value');
                
            });
        }

        if (! Schema::hasColumn('orders', 'voucher_type')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->string('voucher_type')->after('voucher_unit');
                
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
        if (! Schema::hasColumn('orders', 'voucher_unit')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('voucher_unit');
            });
        }

          if (! Schema::hasColumn('orders', 'voucher_type')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('voucher_type');
            });
        }
    }
}
