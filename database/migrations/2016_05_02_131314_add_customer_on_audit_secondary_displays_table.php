<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCustomerOnAuditSecondaryDisplaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('audit_secondary_displays', function (Blueprint $table) {
            $table->string('customer')->nullable()->after('audit_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('audit_secondary_displays', function (Blueprint $table) {
            $table->dropColumn('customer');
        });
    }
}
