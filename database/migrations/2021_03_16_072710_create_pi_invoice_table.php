<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePiInvoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pi_invoice', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id')->default('0');
            $table->integer('vendor_id')->default('0');
            $table->integer('user_id')->default('0')->comment('bill genrator user id');
            $table->string('bill_no',100);
            $table->integer('bill_unique_id')->default('0');
            $table->integer('bill_type')->default('1')->comment('1 = Cash Invoice,2 = Bank Transfer');
            $table->integer('bank_id')->default('0')->comment('vendor bank account');
            $table->float('amount',8,2)->default('0.00')->comment('Amount excluding GST');
            $table->float('gst',8,2)->default('0.00')->comment('GST Amount');
            $table->float('cgst',8,2)->default('0.00')->comment('CGST Amount');
            $table->float('sgst',8,2)->default('0.00')->comment('SGST Amount');
            $table->float('igst',8,2)->default('0.00')->comment('IGST Amount');
            $table->float('total_amount',8,2)->default('0.00')->comment('Total Amount including GST');
            $table->date('bill_date')->nullable();
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
        Schema::dropIfExists('pi_invoice');
    }
}
