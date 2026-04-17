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
        Schema::create('automations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->constrained()->onDelete('cascade');
            $table->foreignId('post_id')->nullable()->constrained()->onDelete('cascade'); // null = global automation
            $table->string('name');
            $table->enum('trigger_type', ['comment', 'dm', 'follow']);
            $table->enum('reply_type', ['public', 'private', 'both'])->default('both');
            $table->text('public_reply_message')->nullable();
            $table->text('private_dm_message')->nullable();
            $table->json('dm_buttons')->nullable(); // Button configuration
            $table->foreignId('flow_id')->nullable()->constrained()->onDelete('set null');
            $table->boolean('is_active')->default(true);
            $table->integer('delay_seconds_min')->default(10);
            $table->integer('delay_seconds_max')->default(60);
            $table->boolean('require_follow')->default(false); // For follow-gated messaging
            $table->text('follow_required_message')->nullable();
            $table->timestamps();

            $table->index('page_id');
            $table->index('post_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('automations');
    }
};
