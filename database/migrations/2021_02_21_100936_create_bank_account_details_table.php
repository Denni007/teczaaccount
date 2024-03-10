<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBankAccountDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_account_details', function (Blueprint $table) {
            $table->id();
            $table->integer("company_id")->default('0');
            $table->integer("vendor_id")->default('0');
            $table->string("bank_name");
            $table->string("ifsc_code");
            $table->string("swift_code")->nullable();
            $table->string("beneficary_name");
            $table->string("account_no");
            $table->string("account_type")->nullable();
            $table->string("branch_name")->nullable();
            $table->integer("created_by");
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
        Schema::dropIfExists('bank_account_details');
    }
}
