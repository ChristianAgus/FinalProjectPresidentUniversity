<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOrderIdAndOrderDetailIdToReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       

        if (! Schema::hasColumn('reviews', 'order_id')) {
            Schema::table('reviews', function (Blueprint $table) {
                $table->integer('order_id')->default(0)->after('id');
                
            });
        }

        if (! Schema::hasColumn('reviews', 'order_details_id')) {
            Schema::table('reviews', function (Blueprint $table) {
                $table->integer('order_details_id')->default(0)->after('order_id');
                
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
       

         if (! Schema::hasColumn('reviews', 'order_id')) {
            Schema::table('reviews', function (Blueprint $table) {
                $table->dropColumn('order_id');
            });
        }

          if (! Schema::hasColumn('reviews', 'order_details_id')) {
            Schema::table('reviews', function (Blueprint $table) {
                $table->dropColumn('order_details_id');
            });
        }
    }
}
