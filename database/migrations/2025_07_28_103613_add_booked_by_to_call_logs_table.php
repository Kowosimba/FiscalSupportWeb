<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('call_logs', function (Blueprint $table) {
            // Add booked_by as nullable string (adjust length as needed)
            $table->string('booked_by')->nullable()->after('approved_by_name');
            // Also check if you want to add approved_by_name if it's missing
            // $table->string('approved_by_name')->nullable()->after('approved_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('call_logs', function (Blueprint $table) {
            $table->dropColumn('booked_by');
            // Uncomment if you added approved_by_name above
            // $table->dropColumn('approved_by_name');
        });
    }
};
