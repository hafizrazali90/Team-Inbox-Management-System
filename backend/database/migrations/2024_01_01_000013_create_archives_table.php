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
        Schema::create('archives', function (Blueprint $table) {
            $table->id();
            $table->string('archive_type'); // conversation, message, activity_log
            $table->unsignedBigInteger('original_id'); // Original record ID
            $table->json('data'); // Full record data in JSON
            $table->timestamp('archived_at');
            $table->timestamps();

            $table->index(['archive_type', 'original_id']);
            $table->index('archived_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('archives');
    }
};
