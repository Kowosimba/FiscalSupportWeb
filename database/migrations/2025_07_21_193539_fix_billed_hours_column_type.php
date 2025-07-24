<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('call_logs', function (Blueprint $table) {
            // Change billed_hours to string with sufficient length
            $table->string('billed_hours', 50)->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('call_logs', function (Blueprint $table) {
            // Revert back if needed
            $table->decimal('billed_hours', 8, 2)->nullable()->change();
        });
    }
};
