<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsReviewToOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      

        if (! Schema::hasColumn('order_details', 'isReview')) {
            Schema::table('order_details', function (Blueprint $table) {
                $table->string('isReview')->after('order_id');
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
        if (! Schema::hasColumn('order_details', 'isReview')) {
            Schema::table('order_details', function (Blueprint $table) {
                $table->dropColumn('isReview');
            });
        }
       
    }
}
