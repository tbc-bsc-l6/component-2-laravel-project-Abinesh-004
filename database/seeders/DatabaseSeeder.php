<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;


class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Seed roles first
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

        // Optional: Create some sample data for testing
        $teacherRole = UserRole::where('role', 'teacher')->first();
        $studentRole = UserRole::where('role', 'student')->first();

        // Sample teacher
        User::firstOrCreate(
            ['email' => 'teacher@college.com'],
            [
                'name' => 'John Teacher',
                'password' => Hash::make('password'),
                'user_role_id' => $teacherRole->id,
                'email_verified_at' => now(),
            ]
        );

        // Sample student
        User::firstOrCreate(
            ['email' => 'student@college.com'],
            [
                'name' => 'Jane Student',
                'password' => Hash::make('password'),
                'user_role_id' => $studentRole->id,
                'email_verified_at' => now(),
            ]
        );

        // Seed realistic teachers and students
        $this->call([
            ModulesSeeder::class,
            TeacherSeeder::class,
            StudentSeeder::class,
        ]);
    }
}

