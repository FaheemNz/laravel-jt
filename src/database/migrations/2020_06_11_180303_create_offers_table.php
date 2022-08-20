<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->string('description')->nullable();
            $table->enum('status', \App\Utills\Constants\OfferStatus::ALL)->default(\App\Utills\Constants\OfferStatus::OPEN);
            $table->double('price');
            $table->double('reward');
            $table->double('service_charges');
            $table->date('expiry_date');
            $table->foreignId('currency_id')->constrained("currencies")->restrictOnDelete();
            $table->foreignId('trip_id')->constrained("trips")->restrictOnDelete();
            $table->foreignId("order_id")->constrained("orders")->restrictOnDelete();
            $table->foreignId('user_id')->constrained("users")->restrictOnDelete();
            $table->boolean('is_disabled')->default(false);
            $table->string('admin_review')->nullable();
            $table->string('reason_text', 255)->nullable();
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
        Schema::dropIfExists('offers');
    }
}
