<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormFormulasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_formulas', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('audit_template_id')->unsigned();
            $table->foreign('audit_template_id')->references('id')->on('audit_templates');
            $table->integer('form_id')->unsigned();
            $table->foreign('form_id')->references('id')->on('forms');
            $table->string('formula');
            $table->string('formula_desc');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('form_formulas');
    }
}
