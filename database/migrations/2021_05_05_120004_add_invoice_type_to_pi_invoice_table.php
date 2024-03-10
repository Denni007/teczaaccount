<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInvoiceTypeToPiInvoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pi_invoice', function (Blueprint $table) {
            $table->integer('invoice_type')->default('1')->comment('1 = Retail Invoice,2 = Tax Transfer');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pi_invoice', function (Blueprint $table) {
            $table->dropColumn('invoice_type');
        });
    }
}
