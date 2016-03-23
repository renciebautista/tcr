<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormSingleSelectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_single_selects', function(Blueprint $table) {
            $table->integer('audit_template_id')->unsigned();
            $table->foreign('audit_template_id')->references('id')->on('audit_templates');
            $table->integer('form_id')->unsigned();
            $table->foreign('form_id')->references('id')->on('forms');
            $table->integer('audit_single_select_id')->unsigned();
            $table->foreign('audit_single_select_id')->references('id')->on('audit_single_selects');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('form_single_selects');
    }
}
