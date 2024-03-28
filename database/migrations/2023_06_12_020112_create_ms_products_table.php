<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMsProductsTable extends Migration
{

    public function up()
    {

        Schema::create('ms_categories', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('slug');
            $table->string('status')->default('Active');
            $table->text('image')->nullable();
            $table->timestamps();
        });

        Schema::create('ms_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id')->nullable();

            $table->string('name');
            $table->string('sku')->unique();

            $table->string('cat_name');

            $table->string('slug');
            $table->string('size');
            $table->string('uom');
            $table->string('price');
            $table->text('image');
            $table->string('status')->default('Active');            

            $table->timestamps();
            $table->foreign('category_id')->references('id')->on('ms_categories')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->string('oc_number')->nullable()->unique();
            $table->string('customer_name')->nullable();
            $table->string('customer_phone')->nullable();
            $table->string('customer_address')->nullable();

            $table->enum('status',['Cart','Closed'])->default('Cart');
            $table->string('grand_total')->nullable();

            $table->string('pay_category')->default('Cash');
            $table->text('proof_of_payment')->nullable();
            $table->date('order_date')->nullable();
            $table->text('order_notes')->nullable();
            
            $table->string('sales_code')->nullable();
            $table->string('sales_name')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('order_id');

            $table->string('name');
            $table->string('size');
            $table->string('uom');
            $table->string('price');
            $table->string('qty');
            $table->string('sub_total');

            $table->timestamps();
            $table->foreign('product_id')->references('id')->on('ms_products')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade')->onUpdate('cascade');
        });
    }


    public function down()
    {
        Schema::dropIfExists('order_details');
        Schema::dropIfExists('orders');

        Schema::dropIfExists('ms_products');
        Schema::dropIfExists('ms_categories');
    }
}
