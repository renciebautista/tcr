<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('forms', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('audit_template_id')->unsigned();
            $table->foreign('audit_template_id')->references('id')->on('audit_templates');
            $table->integer('form_type_id')->unsigned();
            $table->foreign('form_type_id')->references('id')->on('form_types');
            $table->string('code');
            $table->string('sku_code')->nullable();
            $table->string('prompt');
            $table->boolean('required');
            $table->string('expected_answer')->nullable();
            $table->string('default_answer')->nullable();
            $table->string('image')->nullable();
            $table->boolean('exempt');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('forms');
    }
}
