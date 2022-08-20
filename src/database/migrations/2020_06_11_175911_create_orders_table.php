<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->text('thumbnail')->nullable();

            $table->string('name');
            $table->longText('description');
            $table->string('url')->nullable();

            $table->foreignId("from_city_id")->constrained("cities")->restrictOnDelete();
            $table->foreignId("destination_city_id")->constrained("cities")->restrictOnDelete();

            $table->foreignId("category_id")->constrained("categories")->restrictOnDelete();
            $table->foreignId("currency_id")->constrained("currencies")->restrictOnDelete();

            $table->enum('weight',[1,2,3])->default('1');
            $table->double('quantity');
            $table->double('price');
            $table->double('reward');

            $table->boolean('with_box')->default(0);
            $table->boolean('is_doorstep_delivery')->default(0);
            $table->boolean('is_disputed')->default(0);
            $table->boolean('is_disabled')->default(0);

            $table->date('needed_by')->nullable();
            $table->enum('status', \App\Utills\Constants\OrderStatus::ALL)->default('new');

            $table->unsignedInteger('customer_rating')->nullable();
            $table->string('customer_review')->nullable();

            $table->unsignedInteger('traveler_rating')->nullable();
            $table->string('traveler_review')->nullable();

            $table->string('admin_review')->nullable();

            $table->string('pin_code')->nullable();
            $table->dateTime('pin_time_to_live')->nullable();

            $table->text('item_purchased_receipt')->nullable();
            $table->double('item_purchased_amount')->default(0);

            $table->text('custom_duty_charges_receipt')->nullable();
            $table->double('custom_duty_charges_amount')->default(0);

            $table->double("traveler_service_charges_percentage")->default(0);
            $table->double("customer_service_charges_percentage")->default(0);
            $table->double("customer_duty_charges_percentage")->default(0);
            $table->boolean("is_traveler_paid")->default(false);
            $table->boolean("is_customer_paid")->default(false);

            $table->double("profit")->default(0);

            $table->foreignId('traveler_id')->nullable()->constrained("users")->restrictOnDelete();
            $table->foreignId('user_id')->nullable()->constrained("users")->restrictOnDelete();

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
        Schema::dropIfExists('orders');
    }
}
