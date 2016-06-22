<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuditUserPjpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('audit_user_pjps', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('audit_id');
            $table->integer('user_id');
            $table->integer('target');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('audit_user_pjps');
    }
}
