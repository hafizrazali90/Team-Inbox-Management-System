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
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Admin, Operation Manager, Manager, Assistant Manager, CX
            $table->string('slug')->unique(); // admin, operation_manager, manager, assistant_manager, cx
            $table->integer('level')->default(0); // 5=Admin, 4=OM, 3=Manager, 2=AM, 1=CX
            $table->text('description')->nullable();
            $table->json('permissions')->nullable(); // JSON array of permission keys
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
