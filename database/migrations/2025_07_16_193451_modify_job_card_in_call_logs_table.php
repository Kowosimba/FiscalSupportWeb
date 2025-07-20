<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('call_logs', function (Blueprint $table) {
            // Make the job_card column nullable
            $table->string('job_card', 255)->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('call_logs', function (Blueprint $table) {
            // Rollback - make job_card not nullable
            $table->string('job_card', 255)->nullable(false)->change();
        });
    }
};
