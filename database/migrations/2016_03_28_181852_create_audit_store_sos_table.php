<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuditStoreSosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('audit_store_sos', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('audit_id')->unsigned();
            $table->foreign('audit_id')->references('id')->on('audits');
            $table->integer('audit_store_id')->unsigned();
            $table->foreign('audit_store_id')->references('id')->on('audit_stores');
            $table->integer('form_category_id')->unsigned();
            $table->foreign('form_category_id')->references('id')->on('form_categories');
            $table->integer('sos_type_id')->unsigned();
            $table->foreign('sos_type_id')->references('id')->on('sos_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('audit_store_sos');
    }
}
