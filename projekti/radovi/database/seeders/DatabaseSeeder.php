<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\Task;
use App\Models\Application;
use App\Enums\StudyType;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Setup Roles
        $roleAdmin = Role::create(['name' => 'admin']);
        $roleTeacher = Role::create(['name' => 'teacher']);
        $roleStudent = Role::create(['name' => 'student']);

        // ---------------------------------------------------------
        // 2. Create Specific Users for Login (Password: 'password')
        // ---------------------------------------------------------

        // Admin
        $admin = User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'admin@app.com',
            'password' => Hash::make('password'),
        ]);
        $admin->assignRole($roleAdmin);

        // Main Teacher (for you to test creating tasks/accepting students)
        $mainTeacher = User::factory()->create([
            'name' => 'Prof. Main Teacher',
            'email' => 'teacher@app.com',
            'password' => Hash::make('password'),
        ]);
        $mainTeacher->assignRole($roleTeacher);

        // Main Student (for you to test applying)
        $mainStudent = User::factory()->create([
            'name' => 'Student Demo',
            'email' => 'student@app.com',
            'password' => Hash::make('password'),
        ]);
        $mainStudent->assignRole($roleStudent);

        // ---------------------------------------------------------
        // 3. Create Batch Users
        // ---------------------------------------------------------
        
        $teachers = User::factory(5)->create();
        foreach($teachers as $t) $t->assignRole($roleTeacher);

        $students = User::factory(20)->create();
        foreach($students as $s) $s->assignRole($roleStudent);

        // Merge main users into lists for data generation
        $allTeachers = $teachers->push($mainTeacher);
        $allStudents = $students->push($mainStudent);

        // ---------------------------------------------------------
        // 4. Create Tasks (Subjects)
        // ---------------------------------------------------------
        
        // Realistic topics for demo purposes
        $topics = [
            ['en' => 'AI in Healthcare', 'hr' => 'Umjetna inteligencija u zdravstvu'],
            ['en' => 'Laravel Web Development', 'hr' => 'Laravel web razvoj'],
            ['en' => 'Blockchain Security', 'hr' => 'Sigurnost blockchain tehnologije'],
            ['en' => 'Internet of Things (IoT)', 'hr' => 'Internet stvari (IoT)'],
            ['en' => 'Cloud Computing Strategies', 'hr' => 'Strategije računalstva u oblaku'],
            ['en' => 'Mobile App UI/UX', 'hr' => 'UI/UX mobilnih aplikacija'],
            ['en' => 'Big Data Analysis', 'hr' => 'Analiza velikih podataka'],
            ['en' => 'Cybersecurity Protocols', 'hr' => 'Protokoli kibernetičke sigurnosti'],
            ['en' => 'Machine Learning Basics', 'hr' => 'Osnove strojnog učenja'],
            ['en' => 'Database Optimization', 'hr' => 'Optimizacija baza podataka'],
            ['en' => 'Green Energy Tech', 'hr' => 'Tehnologija zelene energije'],
            ['en' => 'Smart City Infrastructure', 'hr' => 'Infrastruktura pametnih gradova'],
            ['en' => 'E-Commerce Trends', 'hr' => 'Trendovi u e-trgovini'],
            ['en' => 'Game Development with Unity', 'hr' => 'Razvoj igara u Unityju'],
            ['en' => 'Neural Networks', 'hr' => 'Neuronske mreže'],
        ];

        foreach ($allTeachers as $teacher) {
            // Each teacher gets 3 random topics from the list
            $teacherTopics = collect($topics)->random(3);

            foreach ($teacherTopics as $topic) {
                Task::create([
                    'user_id' => $teacher->id,
                    'name_en' => $topic['en'],
                    'name_hr' => $topic['hr'],
                    'description_en' => 'This is a detailed description for ' . $topic['en'],
                    'description_hr' => 'Ovo je detaljan opis za ' . $topic['hr'],
                    // Randomly assign a study type
                    'study_type' => fake()->randomElement(StudyType::cases()),
                ]);
            }
        }

        // ---------------------------------------------------------
        // 5. Create Applications (Simulate Students Applying)
        // ---------------------------------------------------------
        
        $allTasks = Task::all();

        foreach ($allStudents as $student) {
            // 70% chance a student has applied to something
            if (rand(1, 100) > 30) {
                
                // Student picks 1 to 5 random tasks
                $tasksToApply = $allTasks->random(rand(1, 5));
                $priority = 1;

                foreach ($tasksToApply as $task) {
                    Application::create([
                        'user_id' => $student->id,
                        'task_id' => $task->id,
                        'priority' => $priority++, // Increments: 1, 2, 3...
                    ]);
                }
            }
        }
    }
}