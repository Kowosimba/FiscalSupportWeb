<?php
// database/migrations/create_call_logs_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
       Schema::create('call_logs', function (Blueprint $table) {
    $table->id();
    $table->string('job_card')->unique();
    $table->text('fault_description');
    $table->string('zimra_ref')->nullable();
    $table->timestamp('date_booked')->useCurrent();
    $table->timestamp('date_resolved')->nullable();
    $table->timestamp('time_start')->nullable();
    $table->timestamp('time_finish')->nullable();
    $table->enum('type', ['normal','emergency'])->default('normal');
    $table->decimal('billed_hours', 8, 2)->nullable();
    $table->decimal('amount_charged', 10, 2);
    $table->enum('status', ['pending', 'in_progress', 'completed', 'cancelled'])->default('pending');
    $table->unsignedBigInteger('approved_by');
    $table->unsignedBigInteger('assigned_to')->nullable(); // Foreign key to users table
    $table->text('engineer_comments')->nullable();
    
    // Customer information
    $table->string('customer_name');
    $table->string('customer_email');
    $table->string('customer_phone')->nullable();
    $table->text('customer_address')->nullable();
    
    $table->timestamps();
    
    // Foreign key constraints
    $table->foreign('approved_by')->references('id')->on('users');
    $table->foreign('assigned_to')->references('id')->on('users')->nullOnDelete();
});

    }

    public function down()
    {
        Schema::dropIfExists('call_logs');
    }
};
