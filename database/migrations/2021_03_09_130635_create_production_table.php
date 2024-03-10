<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('production', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id')->default('0');
            $table->integer('product_id')->default('0');
            $table->float('quantity')->default('0.00');
            $table->string('weight',100)->nullable();
            $table->string('batch_no',255);
            $table->tinyInteger('certified')->default('1')->comment('1 = ISO,2 = Non- ISO');
            $table->integer('created_by')->default('0');
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
        Schema::dropIfExists('production');
    }
}
