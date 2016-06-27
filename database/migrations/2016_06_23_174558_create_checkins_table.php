<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCheckinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('check_ins', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('audit_id');
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
            $table->datetime('checkin');
            $table->double('lat', 11, 7);
            $table->double('long', 11, 7);
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
        Schema::drop('check_ins');
    }
}
