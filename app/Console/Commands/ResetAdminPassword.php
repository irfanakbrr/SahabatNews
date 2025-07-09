<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Role;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class ResetAdminPassword extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:reset-password {email?} {--password=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset password admin atau buat admin baru jika belum ada';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email') ?? $this->ask('Email admin', 'admin@sahabatnews.test');
        $password = $this->option('password') ?? $this->secret('Password baru');

        if (empty($password)) {
            $password = 'admin123';
            $this->info('Menggunakan password default: admin123');
        }

        // Cari role admin
        $adminRole = Role::where('name', 'admin')->first();
        if (!$adminRole) {
            $this->error('Role admin tidak ditemukan. Jalankan: php artisan db:seed --class=RoleSeeder');
            return Command::FAILURE;
        }

        // Cari user dengan email tersebut
        $user = User::where('email', $email)->first();

        if ($user) {
            // Update password user yang sudah ada
            $user->update([
                'password' => Hash::make($password),
                'role_id' => $adminRole->id, // Pastikan dia admin
            ]);

            $this->info("✅ Password berhasil direset untuk admin: {$user->name} ({$email})");
        } else {
            // Buat user admin baru
            $name = $this->ask('Nama admin', 'Admin SahabatNews');
            
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
                'role_id' => $adminRole->id,
                'email_verified_at' => now(),
            ]);

            $this->info("✅ Admin baru berhasil dibuat: {$user->name} ({$email})");
        }

        $this->line('');
        $this->line('📧 Email: ' . $email);
        $this->line('🔑 Password: ' . $password);
        $this->line('🌐 URL Admin: ' . url('/admin'));
        
        return Command::SUCCESS;
    }
}
