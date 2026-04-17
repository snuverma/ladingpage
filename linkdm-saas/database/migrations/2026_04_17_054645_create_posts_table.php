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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->constrained()->onDelete('cascade');
            $table->string('post_id'); // Meta post ID
            $table->string('platform'); // facebook, instagram
            $table->text('message')->nullable();
            $table->string('permalink')->nullable();
            $table->timestamp('posted_at')->nullable();
            $table->boolean('has_automation')->default(false);
            $table->timestamps();

            $table->unique(['post_id', 'platform']);
            $table->index('page_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
