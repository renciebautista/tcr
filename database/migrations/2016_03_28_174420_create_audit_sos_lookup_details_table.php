<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuditSosLookupDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('audit_sos_lookup_details', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('audit_id')->unsigned();
            $table->foreign('audit_id')->references('id')->on('audits');
            $table->integer('audit_sos_lookup_id')->unsigned();
            $table->foreign('audit_sos_lookup_id')->references('id')->on('audit_sos_lookups');
            $table->integer('form_category_id')->unsigned();
            $table->foreign('form_category_id')->references('id')->on('form_categories');
            $table->integer('sos_type_id')->unsigned();
            $table->foreign('sos_type_id')->references('id')->on('sos_types');
            $table->decimal('less',5,3);
            $table->decimal('value',5,3);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('audit_sos_lookup_details');
    }
}
