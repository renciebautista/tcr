<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuditTemplatesTable extends Migration
{
     /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('audit_templates', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('audit_id')->unsigned();
            $table->foreign('audit_id')->references('id')->on('audits');
            $table->string('channel_code');
            $table->string('description');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('audit_templates');
    }
}
