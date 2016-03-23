<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuditTemplateFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('audit_template_forms', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('audit_template_id')->unsigned();
            $table->foreign('audit_template_id')->references('id')->on('audit_templates');

            $table->integer('audit_template_group_id')->unsigned();
            $table->foreign('audit_template_group_id')->references('id')->on('audit_template_groups');

            $table->integer('form_id')->unsigned();
            $table->foreign('form_id')->references('id')->on('forms');
            $table->integer('order');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('audit_template_forms');
    }
}
