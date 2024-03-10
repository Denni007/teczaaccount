<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice', function (Blueprint $table) {
            $table->id();
            $table->integer('pi_id')->default('0')->comment('Perfoma Invoice Reference');
            $table->integer('company_id')->default('0');
            $table->integer('vendor_id')->default('0');
            $table->integer('user_id')->default('0')->comment('bill genrator user id');
            $table->string('bill_no',100);
            $table->integer('bill_unique_id')->default('0');
            $table->integer('bill_type')->default('1')->comment('1 = Cash Invoice,2 = Bank Transfer');
            $table->integer('bank_id')->default('0')->comment('Copmany bank account');
            $table->string('delivery_note',255)->nullable();
            $table->string('supplier_ref',255)->nullable();
            $table->string('buyers_no',255)->nullable();
            $table->date('dated')->nullable();
            $table->string('dispatch_doc_no',255)->nullable();
            $table->date('delivery_note_date')->nullable();
            $table->string('dispatch_through',255)->nullable();
            $table->string('destination',255)->nullable();
            $table->date('bill_of_landing')->nullable();
            $table->string('motor_vehicle_no',255)->nullable();
            $table->string('terms_of_delivery',255)->nullable();
            $table->float('amount',10,2)->unsigned()->default('0.00')->comment('Amount excluding GST');
            $table->float('gst',10,2)->unsigned()->default('0.00')->comment('GST Amount');
            $table->float('cgst',10,2)->unsigned()->default('0.00')->comment('CGST Amount');
            $table->float('sgst',10,2)->unsigned()->default('0.00')->comment('SGST Amount');
            $table->float('igst',10,2)->unsigned()->default('0.00')->comment('IGST Amount');
            $table->float('total_amount',10,2)->unsigned()->default('0.00')->comment('Total Amount including GST');
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
        Schema::dropIfExists('invoice');
    }
}
