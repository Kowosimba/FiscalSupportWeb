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
        $table->string('approved_by_name')->after('approved_by');
    });
}

public function down()
{
    Schema::table('call_logs', function (Blueprint $table) {
        $table->dropColumn('approved_by_name');
    });
}
};
