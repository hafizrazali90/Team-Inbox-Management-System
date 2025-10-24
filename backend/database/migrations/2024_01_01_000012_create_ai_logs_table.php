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
        Schema::create('ai_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->nullable()->constrained('conversations')->onDelete('cascade');
            $table->foreignId('message_id')->nullable()->constrained('messages')->onDelete('cascade');
            $table->text('prompt')->nullable(); // Input sent to AI
            $table->text('response')->nullable(); // AI generated response
            $table->string('model')->default('gpt-4-turbo-preview'); // OpenAI model used
            $table->integer('tokens_used')->default(0);
            $table->boolean('was_sent')->default(false); // Whether AI response was actually sent
            $table->boolean('required_handoff')->default(false); // If AI determined human needed
            $table->text('handoff_reason')->nullable();
            $table->timestamps();

            $table->index(['conversation_id', 'created_at']);
            $table->index('was_sent');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_logs');
    }
};
