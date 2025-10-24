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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->constrained('conversations')->onDelete('cascade');
            $table->string('whatsapp_message_id')->unique(); // WhatsApp API message ID
            $table->enum('direction', ['inbound', 'outbound']); // incoming or outgoing
            $table->enum('type', ['text', 'image', 'video', 'document', 'audio', 'voice'])->default('text');
            $table->text('content')->nullable(); // Message text content
            $table->string('media_url')->nullable(); // S3 URL for media files
            $table->string('mime_type')->nullable();
            $table->foreignId('sender_id')->nullable()->constrained('users')->onDelete('set null'); // User who sent (null if customer)
            $table->enum('status', ['sent', 'delivered', 'read', 'failed'])->default('sent');
            $table->boolean('is_ai_generated')->default(false); // If sent by Sofia AI
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->index(['conversation_id', 'created_at']);
            $table->index('sender_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
