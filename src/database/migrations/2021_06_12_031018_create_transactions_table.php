<?php

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->double('amount');
            $table->double('pkr_amount');
            $table->foreignId('currency_id')->constrained("currencies")->restrictOnDelete();
            $table->enum('source',['creditcard','wallet','platform','checkout']);
            $table->text('transaction_details');
            $table->enum('status',['pending','settled','rejected'])->default('pending');
            $table->text('ref_no');
            $table->foreignId('user_id')->constrained("users")->restrictOnDelete();
            $table->foreignId('order_id')->constrained("orders")->restrictOnDelete();
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
        Schema::dropIfExists('transactions');
    }

}
