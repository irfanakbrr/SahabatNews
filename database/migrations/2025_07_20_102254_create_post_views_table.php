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
        Schema::create('post_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained()->onDelete('cascade');
            $table->string('ip_address', 45)->nullable(); // IPv6 support
            $table->string('user_agent')->nullable();
            $table->string('session_id')->nullable();
            $table->timestamp('viewed_at');
            $table->timestamps();
            
            // Index untuk performa
            $table->index(['post_id', 'viewed_at']);
            $table->index(['ip_address', 'post_id']);
            $table->index(['session_id', 'post_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_views');
    }
};
