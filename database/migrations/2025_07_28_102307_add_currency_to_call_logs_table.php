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
        $table->string('currency', 10)->default('USD')->after('amount_charged');
    });
}

public function down()
{
    Schema::table('call_logs', function (Blueprint $table) {
        $table->dropColumn('currency');
    });
}

};
