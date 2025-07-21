<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UpdateUsersWithUsernamePhoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Update existing users with username from email
        $users = User::whereNull('username')->get();
        
        foreach ($users as $user) {
            $baseUsername = strtolower(explode('@', $user->email)[0]);
            $username = $baseUsername;
            $counter = 1;
            
            // Check if username already exists and make it unique
            while (User::where('username', $username)->where('id', '!=', $user->id)->exists()) {
                $username = $baseUsername . '_' . $counter;
                $counter++;
            }
            
            $user->update(['username' => $username]);
        }
        
        // Update sample users with specific usernames and phone numbers
        $sampleUsers = [
            'admin@sahabatnews.com' => [
                'username' => 'admin_sahabatnews',
                'phone' => '+6281234567890'
            ],
            'editor@sahabatnews.com' => [
                'username' => 'editor_sahabatnews',
                'phone' => '+6281234567891'
            ],
            'user@sahabatnews.com' => [
                'username' => 'user_sahabatnews',
                'phone' => '+6281234567892'
            ]
        ];
        
        foreach ($sampleUsers as $email => $data) {
            $user = User::where('email', $email)->first();
            if ($user) {
                $user->update($data);
            }
        }
        
        // Insert additional sample users
        $additionalUsers = [
            [
                'name' => 'John Doe',
                'username' => 'johndoe',
                'email' => 'john@example.com',
                'phone' => '+6281234567893',
                'password' => bcrypt('password'),
                'role_id' => DB::table('roles')->where('name', 'user')->value('id'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Jane Smith',
                'username' => 'janesmith',
                'email' => 'jane@example.com',
                'phone' => '+6281234567894',
                'password' => bcrypt('password'),
                'role_id' => DB::table('roles')->where('name', 'user')->value('id'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Bob Wilson',
                'username' => 'bobwilson',
                'email' => 'bob@example.com',
                'phone' => '+6281234567895',
                'password' => bcrypt('password'),
                'role_id' => DB::table('roles')->where('name', 'user')->value('id'),
                'email_verified_at' => now(),
            ]
        ];
        
        foreach ($additionalUsers as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }
        
        $this->command->info('Users updated with username and phone numbers successfully!');
        
        // Show verification
        $totalUsers = User::count();
        $usersWithUsername = User::whereNotNull('username')->count();
        $usersWithPhone = User::whereNotNull('phone')->count();
        
        $this->command->info("Total users: {$totalUsers}");
        $this->command->info("Users with username: {$usersWithUsername}");
        $this->command->info("Users with phone: {$usersWithPhone}");
    }
}
