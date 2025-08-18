<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('call_logs', function (Blueprint $table) {
            // Add only the missing assigned_at column
            $table->timestamp('assigned_at')->nullable()->after('assigned_to');
        });
    }

    public function down()
    {
        Schema::table('call_logs', function (Blueprint $table) {
            $table->dropColumn('assigned_at');
        });
    }
};