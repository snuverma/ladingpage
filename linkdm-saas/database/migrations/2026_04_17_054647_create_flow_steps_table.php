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
        Schema::create('flow_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('flow_id')->constrained()->onDelete('cascade');
            $table->integer('step_order');
            $table->enum('type', ['message', 'button', 'condition', 'delay', 'action']);
            $table->string('name')->nullable();
            $table->text('content')->nullable(); // Message content or condition logic
            $table->json('buttons')->nullable(); // Button configuration for this step
            $table->json('conditions')->nullable(); // Conditional logic
            $table->integer('delay_seconds')->default(0);
            $table->foreignId('next_step_id')->nullable()->constrained('flow_steps')->onDelete('set null');
            $table->timestamps();

            $table->index('flow_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flow_steps');
    }
};
