<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuditOsaLookupDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('audit_osa_lookup_details', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('audit_id')->unsigned();
            $table->foreign('audit_id')->references('id')->on('audits');
            $table->integer('audit_osa_lookup_id')->unsigned();
            $table->foreign('audit_osa_lookup_id')->references('id')->on('audit_osa_lookups');
            $table->integer('form_category_id')->unsigned();
            $table->foreign('form_category_id')->references('id')->on('form_categories');
            $table->integer('target');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('audit_osa_lookup_details');
    }
}
