<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor', function (Blueprint $table) {
            $table->id();
            $table->string('vendor_name');
            $table->string('contact_no');
            $table->string('email_id')->nullable();
            $table->string('mobile_no');
            $table->string('contact_person_name');
            $table->string('gst_no')->nullable();
            $table->string('address');
            $table->string('country');
            $table->string('state');
            $table->integer('pincode');
            $table->string('cin')->nullable();
            $table->string("website")->nullable();
            $table->string('currency');
            $table->string('pan_no');
            $table->integer("created_by")->nullable();
            $table->integer("updated_by")->nullable();
            $table->integer("deleted_by")->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vendor');
    }
}
