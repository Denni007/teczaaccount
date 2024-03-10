<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('company_id')->default('0')->after('id');
            $table->string('text_pass',255)->nullable()->after('password');
            $table->string('address',500)->nullable()->after('phone');
            $table->tinyInteger('user_type')->default('1')->comment('1 = Cash Login,2 = Cash & Bill Login')->after('address');
            $table->string('bank_name',255)->nullable()->after('user_type');
            $table->string('ifsc_code',255)->nullable()->after('bank_name');
            $table->string('swift_code',255)->nullable()->after('ifsc_code');
            $table->string('beneficary_name',255)->nullable()->after('swift_code');
            $table->string('account_no',255)->nullable()->after('beneficary_name');
            $table->string('account_type',255)->nullable()->after('account_no');
            $table->string('branch_name',255)->nullable()->after('account_type');
            $table->string('designation',255)->nullable()->after('branch_name');
            $table->tinyInteger('shift_type')->default('1')->comment('1 => day,2 => Night,3 => Full time,4 => Daily Worker')->after('designation');
            $table->integer("created_by")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('company_id');
            $table->dropColumn('address');
            $table->dropColumn('user_type');
            $table->dropColumn('bank_name');
            $table->dropColumn('ifsc_code');
            $table->dropColumn('swift_code');
            $table->dropColumn('beneficary_name');
            $table->dropColumn('account_no');
            $table->dropColumn('account_type');
            $table->dropColumn('branch_name');
            $table->dropColumn('designation');
            $table->dropColumn('shift_type');
            $table->dropColumn('created_by');
            $table->dropColumn('text_pass');
        });
    }
}
