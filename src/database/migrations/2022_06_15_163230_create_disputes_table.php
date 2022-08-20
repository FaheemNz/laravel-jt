<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDisputesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('disputes', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained("users");
            $table->text("description")->nullable();
            $table->foreignId("reason_id")->constrained("reasons");
            $table->foreignId("order_id")->nullable()->constrained("orders");
            $table->foreignId("offer_id")->nullable()->constrained("offers");
            $table->foreignId("trip_id")->nullable()->constrained("trips");
            $table->foreignId("counter_offer_id")->nullable()->constrained("counter_offer");
            $table->integer("status")->default(1)->comment("1 => pending, 2 => viewed, 3 => resolving, 4 => resolved, 5 => closed");
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
        Schema::dropIfExists('disputes');
    }
}
