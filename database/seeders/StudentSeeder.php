<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // Get student role ID
         $studentRoleId = DB::table('user_roles')->where('role', 'student')->value('id');

         $students = [
             ['name' => 'Emma Johnson', 'email' => 'emma.johnson@student.college.edu'],
             ['name' => 'Liam Williams', 'email' => 'liam.williams@student.college.edu'],
             ['name' => 'Olivia Brown', 'email' => 'olivia.brown@student.college.edu'],
             ['name' => 'Noah Davis', 'email' => 'noah.davis@student.college.edu'],
             ['name' => 'Ava Martinez', 'email' => 'ava.martinez@student.college.edu'],
             ['name' => 'Ethan Rodriguez', 'email' => 'ethan.rodriguez@student.college.edu'],
             ['name' => 'Sophia Garcia', 'email' => 'sophia.garcia@student.college.edu'],
             ['name' => 'Mason Wilson', 'email' => 'mason.wilson@student.college.edu'],
             ['name' => 'Isabella Anderson', 'email' => 'isabella.anderson@student.college.edu'],
             ['name' => 'Lucas Taylor', 'email' => 'lucas.taylor@student.college.edu'],
         ];
 
         foreach ($students as $student) {
             // Check if student already exists
             $exists = DB::table('users')->where('email', $student['email'])->exists();
             
             if (!$exists) {
                 DB::table('users')->insert([
                     'name' => $student['name'],
                     'email' => $student['email'],
                     'password' => Hash::make('password'),
                     'user_role_id' => $studentRoleId,
                     'created_at' => now(),
                     'updated_at' => now(),
                 ]);
             }
            }
             
    }
}
