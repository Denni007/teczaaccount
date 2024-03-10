<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('phone',100)->nullable()->default(NULL);
            $table->string('wallet_unique_id',100)->unique()->nullable()->default(NULL);
            $table->double('total_wallet',10,4)->default('0');
            $table->tinyInteger('type')->default(2)->comment('1 - Admin, 2 - Staff');
            $table->tinyInteger('is_active')->default(0)->comment('0 = pending, 1 = active, 2 = deactive, 3 = suspended');
            $table->string('password');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
