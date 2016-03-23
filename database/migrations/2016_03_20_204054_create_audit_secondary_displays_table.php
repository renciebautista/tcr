<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuditSecondaryDisplaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('audit_secondary_displays', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('audit_id')->unsigned();
            $table->foreign('audit_id')->references('id')->on('audits');

            $table->integer('form_category_id')->unsigned();
            $table->foreign('form_category_id')->references('id')->on('form_categories');

            $table->string('brand');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('audit_secondary_displays');
    }
}
