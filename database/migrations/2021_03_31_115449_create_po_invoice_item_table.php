<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePoInvoiceItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('po_invoice_item', function (Blueprint $table) {
            $table->id();
            $table->integer('po_id')->default('0');
            $table->integer('product_type')->default('1')->comment('1 => Rawmaterial and 2 => Other product');
            $table->integer('product_id')->default('0');
            $table->string('hsn',255)->nullable();
            $table->string('unit',255)->nullable();
            $table->float('quantity',8,2)->default('0.00');
            $table->float('rate',8,2)->default('0.00');
            $table->float('gst_percentage',8,2)->default('0.00');
            $table->float('amount',8,2)->default('0.00');
            $table->float('tax',8,2)->default('0.00');
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
        Schema::dropIfExists('po_invoice_item');
    }
}
