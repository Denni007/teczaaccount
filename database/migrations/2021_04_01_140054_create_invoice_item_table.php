<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_item', function (Blueprint $table) {
            $table->id();
            $table->integer('invoice_id')->default('0');
            $table->integer('product_id')->default('0');
            $table->string('hsn',255)->nullable();
            $table->string('unit',255)->nullable();
            $table->float('quantity',10,2)->default('0.00')->unsigned();
            $table->float('rate',10,2)->default('0.00')->unsigned();
            $table->float('gst_percentage',10,2)->default('0.00')->unsigned();
            $table->float('amount',10,2)->default('0.00')->unsigned();
            $table->float('tax',10,2)->default('0.00')->unsigned();
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
        Schema::dropIfExists('invoice_item');
    }
}
