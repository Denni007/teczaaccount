<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToPiInvoiceItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pi_invoice_item', function (Blueprint $table) {
            $table->dropColumn('production_id');
            $table->integer('product_id')->default('0')->after('pi_id');
            $table->string('hsn',255)->nullable()->after('product_id');
            $table->string('unit',255)->nullable()->after('hsn');
            $table->float('rate',8,2)->default('0.00')->after('quantity');
            $table->float('gst_percentage',8,2)->default('0.00')->after('rate');
            $table->float('amount',8,2)->default('0.00')->after('gst_percentage');
            $table->float('tax',8,2)->default('0.00')->after('amount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pi_invoice_item', function (Blueprint $table) {
            $table->dropColumn('product_id');
            $table->dropColumn('hsn');
            $table->dropColumn('unit');
            $table->dropColumn('rate');
            $table->dropColumn('gst_percentage');
            $table->dropColumn('amount');
            $table->dropColumn('tax');
        });
    }
}
