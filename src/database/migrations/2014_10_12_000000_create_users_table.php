<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('phone_no');
            $table->string('country')->nullable();
            $table->double('rating')->default(0);
            $table->unsignedBigInteger('currency_id')->nullable();
            $table->unsignedBigInteger('image_id')->nullable();
            $table->boolean('is_disabled')->default(false);
            $table->string('admin_review')->nullable();
            $table->string('device_token')->nullable();
            $table->integer("role")->default(\App\Utills\Constants\UserRole::CUSTOMER);

            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
