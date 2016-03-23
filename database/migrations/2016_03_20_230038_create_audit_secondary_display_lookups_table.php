<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuditSecondaryDisplayLookupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('audit_secondary_display_lookups', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('audit_id')->unsigned();
            $table->foreign('audit_id')->references('id')->on('audits');

            $table->integer('audit_store_id')->unsigned();
            $table->foreign('audit_store_id')->references('id')->on('audit_stores');

            $table->integer('secondary_display_id')->unsigned();
            $table->foreign('secondary_display_id')->references('id')->on('audit_secondary_displays');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('audit_secondary_display_lookups');
    }
}
