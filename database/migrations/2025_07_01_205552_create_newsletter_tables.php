<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create the 'newsletter_subscribers' table
        Schema::create('newsletter_subscribers', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->string('email')->unique(); // Unique email address for the subscriber
            $table->string('unsubscribe_token')->unique(); // Unique token for unsubscribing
            $table->boolean('is_active')->default(true); // Status of the subscription (active or inactive)
            $table->timestamps(); // Adds 'created_at' and 'updated_at' columns
        });

        // Create the 'newsletter_campaigns' table
        Schema::create('newsletter_campaigns', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->string('subject'); // Subject of the newsletter campaign
            $table->text('content'); // Full content of the newsletter
            $table->integer('sent_count')->default(0); // Number of times this campaign has been sent
            $table->timestamp('sent_at')->nullable(); // Timestamp when the campaign was last sent (can be null if not yet sent)
            $table->timestamps(); // Adds 'created_at' and 'updated_at' columns
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the 'newsletter_campaigns' table if it exists when rolling back
        Schema::dropIfExists('newsletter_campaigns');
        // Drop the 'newsletter_subscribers' table if it exists when rolling back
        Schema::dropIfExists('newsletter_subscribers');
    }
};

