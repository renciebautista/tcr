<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuditStoresTable extends Migration
{
     /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('audit_stores', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('audit_id')->unsigned();
            $table->foreign('audit_id')->references('id')->on('audits');
            $table->string('account');
            $table->string('customer_code');
            $table->string('customer');
            $table->string('area');
            $table->string('region_code');
            $table->string('region');
            $table->string('remarks')->nullable();
            $table->string('distributor_code');
            $table->string('distributor');
            $table->string('store_code');
            $table->string('store_name');
            $table->integer('audit_enrollment_type_mapping_id')->unsigned();
            $table->foreign('audit_enrollment_type_mapping_id')->references('id')->on('audit_enrollment_type_mappings');
            $table->string('channel_code')->nullable();
            $table->string('template');
            $table->string('agency_code')->nullable();
            $table->string('agency_description')->nullable();
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::drop('audit_stores');
    }
}
