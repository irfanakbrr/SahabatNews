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
            // Pastikan kolom ditambahkan setelah kolom 'password' atau sesuai kebutuhan
            $table->foreignId('role_id')->after('password')->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Hati-hati saat drop foreign key di MySQL
            // Periksa nama constraint jika otomatis dibuat Laravel atau definisikan nama secara eksplisit
            // $table->dropForeign(['role_id']); // Cara umum
            $table->dropConstrainedForeignId('role_id'); // Cara Laravel > 8.x
            // $table->dropColumn('role_id'); // Tidak perlu jika menggunakan dropConstrainedForeignId
        });
    }
}; 