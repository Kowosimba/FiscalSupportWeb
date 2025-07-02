<?php

// database/migrations/YYYY_MM_DD_create_service_resources_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
       Schema::create('service_resources', function (Blueprint $table) {
    $table->id();
    $table->foreignId('service_id')->constrained()->onDelete('cascade');
    $table->string('title');
    $table->string('file_path');
    $table->string('file_size');
    $table->string('file_type');
    $table->integer('download_count')->default(0);
    $table->timestamps();
});
    }

    public function down()
    {
        Schema::dropIfExists('service_resources');
    }
};