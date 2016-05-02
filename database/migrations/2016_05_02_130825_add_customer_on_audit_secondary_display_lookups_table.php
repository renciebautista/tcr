<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCustomerOnAuditSecondaryDisplayLookupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('audit_secondary_display_lookups', function (Blueprint $table) {
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
        Schema::table('audit_secondary_display_lookups', function (Blueprint $table) {
            $table->dropColumn('customer');
        });
    }
}
