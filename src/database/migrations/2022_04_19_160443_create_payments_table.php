<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained("users");
            $table->foreignId('counter_offer_id')->nullable()->constrained("counter_offer");
            $table->foreignId('offer_id')->nullable()->constrained("offers");
            $table->foreignId('order_id')->constrained("orders");
            $table->foreignId('traveler_id')->nullable()->constrained("users");
            $table->foreignId('customer_id')->nullable()->constrained("users");
            $table->double('amount');
            $table->double('pkr_amount');
            $table->enum('status', ['progress', 'paid', 'purchased', 'custom_paid', 'handed_over', 'completed']); // Purchased and Custom -> Picture and Amount will be saved in Database
            $table->string('type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
