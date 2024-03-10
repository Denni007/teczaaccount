<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToPurchaseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase', function (Blueprint $table) {
            $table->float('payment_due',10,2)->default('0.00')->unsigned()->after('total_amount');
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
        Schema::table('purchase', function (Blueprint $table) {
            $table->dropColumn('payment_due');
            $table->dropColumn('status');
        });
    }
}
