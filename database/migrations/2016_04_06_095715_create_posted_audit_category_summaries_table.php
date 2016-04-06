<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostedAuditCategorySummariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posted_audit_category_summaries', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('posted_audit_id')->unsigned();
            $table->foreign('posted_audit_id')->references('id')->on('posted_audits');
            $table->string('category');
            $table->string('group');
            $table->string('passed');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('posted_audit_category_summaries');
    }
}
