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
        Schema::create('keywords', function (Blueprint $table) {
            $table->id();
            $table->foreignId('automation_id')->constrained()->onDelete('cascade');
            $table->string('keyword');
            $table->boolean('match_exact')->default(false);
            $table->boolean('case_sensitive')->default(false);
            $table->timestamps();

            $table->index('automation_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keywords');
    }
};
