<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostedAuditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posted_audits', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('audit_id')->unsigned();
            $table->foreign('audit_id')->references('id')->on('audits');
            $table->string('account');
            $table->string('customer_code');
            $table->string('customer');
            $table->string('area');
            $table->string('region_code');
            $table->string('region');
            $table->string('distributor_code');
            $table->string('distributor');
            $table->string('store_code');
            $table->string('store_name');
            $table->string('channel_code')->nullable();
            $table->string('template');
            $table->boolean('perfect_store');
            $table->decimal('osa',5,2);
            $table->decimal('npi',5,2);
            $table->decimal('planogram',5,2);
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
        Schema::drop('posted_audits');
    }
}
