<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CretaeExpenseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expense', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id')->default('0');
            $table->integer('user_id')->default('0');
            $table->string('expense_name',255);
            $table->float('amount',10,2)->unsigned()->default('0.00');
            $table->unsignedTinyInteger('status')->default('0')->comment('0-Pending,1-Approved,2-Rejected');
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
        Schema::dropIfExists('expense');
    }
}
