<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
         // Get teacher role ID
         $teacherRoleId = DB::table('user_roles')->where('role', 'teacher')->value('id');

         $teachers = [
             ['name' => 'Dr. Sarah Mitchell', 'email' => 'sarah.mitchell@college.edu'],
             ['name' => 'Prof. James Anderson', 'email' => 'james.anderson@college.edu'],
             ['name' => 'Dr. Emily Chen', 'email' => 'emily.chen@college.edu'],
             ['name' => 'Prof. Michael Brown', 'email' => 'michael.brown@college.edu'],
             ['name' => 'Dr. Jennifer Davis', 'email' => 'jennifer.davis@college.edu'],
             ['name' => 'Prof. Robert Wilson', 'email' => 'robert.wilson@college.edu'],
             ['name' => 'Dr. Lisa Martinez', 'email' => 'lisa.martinez@college.edu'],
             ['name' => 'Prof. David Thompson', 'email' => 'david.thompson@college.edu'],
             ['name' => 'Dr. Amanda Garcia', 'email' => 'amanda.garcia@college.edu'],
             ['name' => 'Prof. Christopher Lee', 'email' => 'christopher.lee@college.edu'],
         ];
 
         foreach ($teachers as $teacher) {
             // Check if teacher already exists
             $exists = DB::table('users')->where('email', $teacher['email'])->exists();
             
             if (!$exists) {
                 DB::table('users')->insert([
                     'name' => $teacher['name'],
                     'email' => $teacher['email'],
                     'password' => Hash::make('password'),
                     'user_role_id' => $teacherRoleId,
                     'created_at' => now(),
                     'updated_at' => now(),
                 ]);
             }
         }
     }
}
