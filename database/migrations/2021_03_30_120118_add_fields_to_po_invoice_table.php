<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToPoInvoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('po_invoice', function (Blueprint $table) {
            $table->string('delivery_note',255)->nullable()->after('bank_id');
            $table->string('supplier_ref',255)->nullable()->after('delivery_note');
            $table->string('buyers_no',255)->nullable()->after('supplier_ref');
            $table->date('dated')->nullable()->after('buyers_no');
            $table->string('dispatch_doc_no',255)->nullable()->after('dated');
            $table->date('delivery_note_date')->nullable()->after('dispatch_doc_no');
            $table->string('dispatch_through',255)->nullable()->after('delivery_note_date');
            $table->string('destination',255)->nullable()->after('dispatch_through');
            $table->date('bill_of_landing')->nullable()->after('destination');
            $table->string('motor_vehicle_no',255)->nullable()->after('bill_of_landing');
            $table->string('terms_of_delivery',255)->nullable()->after('motor_vehicle_no');
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
            $table->dropColumn('delivery_note');
            $table->dropColumn('supplier_ref');
            $table->dropColumn('buyers_no');
            $table->dropColumn('dated');
            $table->dropColumn('dispatch_doc_no');
            $table->dropColumn('delivery_note_date');
            $table->dropColumn('dispatch_through');
            $table->dropColumn('destination');
            $table->dropColumn('bill_of_landing');
            $table->dropColumn('motor_vehicle_no');
            $table->dropColumn('terms_of_delivery');
        });
    }
}
