<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // First remove the unique constraint if it exists
        Schema::table('call_logs', function (Blueprint $table) {
            $table->dropUnique(['job_card']);
        });

        // Then modify the column to be nullable
        DB::statement('ALTER TABLE call_logs MODIFY job_card VARCHAR(255) NULL');
    }

    public function down()
    {
        // First set all NULL values to a temporary value
        DB::table('call_logs')
            ->whereNull('job_card')
            ->update(['job_card' => 'TEMP-' . DB::raw('UUID()')]);

        // Then modify the column back to NOT NULL
        DB::statement('ALTER TABLE call_logs MODIFY job_card VARCHAR(255) NOT NULL');

        // Finally add back the unique constraint
        Schema::table('call_logs', function (Blueprint $table) {
            $table->unique(['job_card']);
        });
    }
};