<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInvoiceTypeToPoInvoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('po_invoice', function (Blueprint $table) {
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
        Schema::table('po_invoice', function (Blueprint $table) {
            $table->dropColumn('invoice_type');
        });
    }
}
