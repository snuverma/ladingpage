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
        Schema::create('logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('account_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('automation_id')->nullable()->constrained()->onDelete('set null');
            $table->string('event_type'); // comment_received, dm_sent, keyword_matched, error, etc.
            $table->string('platform')->nullable();
            $table->string('external_id')->nullable(); // Meta comment/message ID
            $table->text('payload')->nullable(); // Full webhook payload
            $table->text('message')->nullable();
            $table->enum('level', ['info', 'warning', 'error', 'debug'])->default('info');
            $table->timestamps();

            $table->index('user_id');
            $table->index('event_type');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logs');
    }
};
