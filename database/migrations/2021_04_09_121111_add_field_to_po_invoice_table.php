<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldToPoInvoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('po_invoice', function (Blueprint $table) {
            $table->unsignedTinyInteger('status')->default('0')->comment('0-Pending,1-Approved,2-Rejected')->after('bill_date');
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
            $table->dropColumn('status');
        });
    }
}
