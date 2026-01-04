<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\UserRole;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        // $this->call(AdminSeeder::class);

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $this->call(UserRoleSeeder::class);

        // Create admin user
        $adminRole = UserRole::where('role', 'admin')->first();
        
        User::firstOrCreate(
            ['email' => 'admin@college.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'user_role_id' => $adminRole->id,
                'email_verified_at' => now(),
            ]
        );
    }
}
