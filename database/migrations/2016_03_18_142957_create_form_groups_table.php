<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_groups', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('audit_id')->unsigned();
            $table->foreign('audit_id')->references('id')->on('audits');
            $table->string('group_desc');
            $table->integer('sos');
            $table->integer('second_display');
            $table->integer('osa');
            $table->integer('custom');
            $table->integer('perfect_store');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('form_groups');
    }
}
