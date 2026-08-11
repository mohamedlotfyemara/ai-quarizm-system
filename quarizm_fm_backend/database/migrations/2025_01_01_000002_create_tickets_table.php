<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // TCK-1001
            $table->string('title');
            $table->text('description')->nullable();
            $table->foreignId('customer_id')->constrained('users')->cascadeOnDelete();
            $table->string('priority')->default('medium'); // low|medium|high|critical
            $table->string('status')->default('received');
            // received|assigned|accepted|in_progress|closed
            $table->string('assigned_team')->nullable();
            $table->foreignId('technician_id')->nullable()->constrained('users')->nullOnDelete();
            $table->boolean('customer_confirmed')->default(false);
            $table->json('attachments')->nullable(); // مسارات الصور المرفقة مع البلاغ
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
