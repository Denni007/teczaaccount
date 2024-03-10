<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceiptTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receipt', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id')->default('0');
            $table->integer('user_id')->default('0');
            $table->integer('type')->default('1')->comment('1-Invoice,2-Purchase');
            $table->integer('invoice_id')->default('0');
            $table->string('invoice_no',255)->nullable();
            $table->integer('receipt_type')->default('1')->comment('1-Cash,2-Bank');
            $table->float('amount',10,2)->default('0.00')->unsigned();
            $table->string('reference',255)->nullable();
            $table->date('receipt_date')->nullable();
            $table->unsignedTinyInteger('status')->default('1')->comment('0-Pending,1-Approved,2-Rejected');
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
        Schema::dropIfExists('receipt');
    }
}
