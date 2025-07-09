
<?php
// database/migrations/create_jobs_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->string('job_card')->unique(); // Auto-generated job card number
            $table->text('fault_description');
            $table->string('zimra_ref')->nullable();
            $table->timestamp('date_booked')->useCurrent();
            $table->timestamp('date_resolved')->nullable();
            $table->timestamp('time_start')->nullable();
            $table->timestamp('time_finish')->nullable();
            $table->enum('type', ['maintenance', 'repair', 'installation', 'consultation', 'emergency'])->default('repair');
            $table->decimal('billed_hours', 8, 2)->nullable();
            $table->decimal('amount_charged', 10, 2);
            $table->enum('status', ['pending', 'assigned', 'in_progress', 'completed', 'cancelled'])->default('pending');
            $table->unsignedBigInteger('approved_by'); // User who created the job
            $table->unsignedBigInteger('assigned_to')->nullable(); // Technician assigned
            $table->text('engineer_comments')->nullable();
            
            // Customer information
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone')->nullable();
            $table->text('customer_address')->nullable();
            
            // Additional tracking fields
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->timestamp('assigned_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            
            $table->timestamps();
            
            // Foreign key constraints
            $table->foreign('approved_by')->references('id')->on('users');
            $table->foreign('assigned_to')->references('id')->on('users')->nullOnDelete();
            
            // Indexes for better performance
            $table->index(['status', 'priority']);
            $table->index(['assigned_to', 'status']);
            $table->index(['date_booked']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('jobs');
    }
};
