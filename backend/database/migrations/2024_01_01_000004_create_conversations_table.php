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
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->string('whatsapp_id')->unique(); // WhatsApp phone number or contact ID
            $table->string('contact_name')->nullable();
            $table->string('contact_phone'); // Customer's phone number
            $table->foreignId('department_id')->constrained('departments')->onDelete('cascade');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('status', ['open', 'pending', 'closed'])->default('open');
            $table->timestamp('last_message_at')->nullable();
            $table->timestamp('first_response_at')->nullable(); // For analytics
            $table->integer('response_count')->default(0); // Number of responses in conversation
            $table->timestamp('follow_up_at')->nullable(); // Scheduled follow-up time
            $table->boolean('is_ai_handled')->default(false); // If Sofia AI handled this conversation
            $table->timestamps();
            $table->softDeletes();

            $table->index(['department_id', 'status']);
            $table->index('assigned_to');
            $table->index('last_message_at');
            $table->index('follow_up_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversations');
    }
};
