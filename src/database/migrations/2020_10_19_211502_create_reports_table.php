<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('entity_id')->nullable();
            $table->enum('type',['order','offer','other']);
            $table->enum('reason',['reason1','reason2','reason3','reason4','reason5','other']);
            $table->string('description')->nullable();
            $table->boolean('is_reviewed')->default(0);
            $table->boolean('is_resolved')->default(0);
            $table->string('admin_review')->nullable();
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
        Schema::dropIfExists('report_orders');
    }
}
