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
        Schema::table('users', function (Blueprint $table) {
            // Add username column after name
            $table->string('username')->nullable()->unique()->after('name');
            
            // Add phone column after email
            $table->string('phone', 20)->nullable()->after('email');
            
            // Add indexes for better performance
            $table->index('username');
            $table->index('phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop indexes first
            $table->dropIndex(['username']);
            $table->dropIndex(['phone']);
            
            // Drop columns
            $table->dropColumn(['username', 'phone']);
        });
    }
};
