<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Module;



class ModulesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
   
        $modules = [
            'Introduction to Programming',
            'Web Development Fundamentals',
            'Database Management Systems',
            'Data Structures and Algorithms',
            'Object-Oriented Programming',
            'Computer Networks',
            'Software Engineering',
            'Mobile Application Development',
            'Artificial Intelligence',
            'Machine Learning Basics',
            'Cybersecurity Fundamentals',
            'Cloud Computing',
            'DevOps Practices',
            'UI/UX Design',
            'Digital Marketing',
        ];

        foreach ($modules as $moduleName) {
            Module::firstOrCreate(
                ['module' => $moduleName],
                ['is_available' => true]
            );
        }

        $this->command->info('Modules seeded successfully!');
    }
}
