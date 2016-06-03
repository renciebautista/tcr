<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPjpOnAuditStores extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('audit_stores', function (Blueprint $table) {
            $table->boolean('pjp')->default(0)->after('user_id');
            $table->integer('freq')->default(1)->after('pjp');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('audit_stores', function (Blueprint $table) {
            $table->dropColumn(['pjp', 'freq']);
        });
    }
}
