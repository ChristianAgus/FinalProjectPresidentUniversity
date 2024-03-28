<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('name');
            $table->string('username')->unique();
            $table->string('password');
            $table->string('email')->unique();
            $table->date('birth_date')->nullable();
            $table->enum('gender', ['Male', 'Female', 'Other'])->nullable();
            $table->string('address')->nullable();
            $table->string('phone_number')->nullable();
            $table->enum('role', ['User', 'Sales', 'Admin'])->default('User');
            $table->timestamps();
        });
        

        DB::table('users')->insert([
            [
                'name'         => 'Admin',
                'username'     => 'pameran_haldinfoods',
                'password'     => bcrypt('haldinfoods2023'),
                'role'         => 'admin',
                'address'      => 'Alamat Admin',
                'phone_number' => '08123456789',
                'first_name'   => 'Admin',
                'last_name'    => 'HaldinFoods',
                'birth_date'   => '1990-01-01',
                'gender'       => 'male',
                'email'        => 'admin@example.com',
            ]
        ]);
    }
    
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
