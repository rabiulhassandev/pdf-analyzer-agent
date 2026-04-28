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
        Schema::create('prompts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->longText('content');
            $table->enum('type', ['entity_extraction', 'risk_assessment', 'summary', 'custom'])->default('custom');
            $table->enum('status', ['draft', 'active', 'archived'])->default('draft');
            $table->string('model', 50)->default('gpt-4o');
            $table->decimal('accuracy_score', 5, 2)->nullable();
            $table->integer('usage_count')->default(0);
            $table->json('parameters')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prompts');
    }
};
