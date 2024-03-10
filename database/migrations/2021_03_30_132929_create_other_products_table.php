<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOtherProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('other_product', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id')->default('0');
            $table->string('other_product_name',255);
            $table->float('quantity',8,2)->default('0.00')->comment('quantity');
            $table->string('unit')->nullable();
            $table->float('gst_percentage',8,2)->default('0.00');
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
        Schema::dropIfExists('other_product');
    }
}
