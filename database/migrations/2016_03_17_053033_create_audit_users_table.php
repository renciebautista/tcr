<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuditUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('audit_users', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('audit_id')->unsigned();
            $table->foreign('audit_id')->references('id')->on('audits');
            $table->string('username');
            $table->string('fullname');
            $table->string('email')->nullable();
            $table->string('password');
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
        Schema::drop('audit_users');
    }
}
