<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuditMultiSelectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('audit_multi_selects', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('audit_template_id')->unsigned();
            $table->foreign('audit_template_id')->references('id')->on('audit_templates');
            $table->string('option');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('audit_multi_selects');
    }
}
