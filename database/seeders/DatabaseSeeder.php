<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\StudentService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create categories
        $categories = [
            ['name' => 'Academic Tutoring', 'description' => 'Help with studies and assignments', 'is_active' => true],
            ['name' => 'Programming & Tech', 'description' => 'Web development, mobile apps, and technical services', 'is_active' => true],
            ['name' => 'Design & Creative', 'description' => 'Graphic design, video editing, and creative services', 'is_active' => true],
            ['name' => 'Language Services', 'description' => 'Translation, proofreading, and language tutoring', 'is_active' => true],
            ['name' => 'Event Planning', 'description' => 'Event organization and planning services', 'is_active' => true],
            ['name' => 'Photography', 'description' => 'Photo and video services', 'is_active' => true],
        ];

        foreach ($categories as $categoryData) {
            Category::create([
                'name' => $categoryData['name'],
                'slug' => Str::slug($categoryData['name']),
                'description' => $categoryData['description'],
                'is_active' => $categoryData['is_active'],
            ]);
        }

        // Create community user
        $communityUser = User::create([
            'name' => 'Community User',
            'email' => 'community@example.com',
            'password' => Hash::make('password'),
            'role' => 'community',
            'phone' => '0123456789',
            'verification_status' => 'approved',
            'public_verified_at' => now(),
        ]);

        // Create student users with services
        $students = [
            [
                'name' => 'Ahmad Rahman',
                'email' => 'ahmad@siswa.upsi.edu.my',
                'student_id' => 'CD21001',
                'services' => [
                    ['title' => 'Mathematics Tutoring', 'description' => 'Expert help in calculus, algebra, and statistics', 'price' => 25.00, 'category' => 'Academic Tutoring'],
                    ['title' => 'Physics Problem Solving', 'description' => 'Assistance with physics assignments and concepts', 'price' => 30.00, 'category' => 'Academic Tutoring'],
                ]
            ],
            [
                'name' => 'Siti Nurhaliza',
                'email' => 'siti@siswa.upsi.edu.my',
                'student_id' => 'CD21002',
                'services' => [
                    ['title' => 'Web Development', 'description' => 'Full-stack web development using Laravel and React', 'price' => 50.00, 'category' => 'Programming & Tech'],
                    ['title' => 'Mobile App Development', 'description' => 'Android and iOS app development', 'price' => 80.00, 'category' => 'Programming & Tech'],
                ]
            ],
            [
                'name' => 'Lim Wei Ming',
                'email' => 'lim@siswa.upsi.edu.my',
                'student_id' => 'CD21003',
                'services' => [
                    ['title' => 'Graphic Design', 'description' => 'Logo design, posters, and branding materials', 'price' => 35.00, 'category' => 'Design & Creative'],
                    ['title' => 'Video Editing', 'description' => 'Professional video editing for events and promotions', 'price' => 40.00, 'category' => 'Design & Creative'],
                ]
            ],
            [
                'name' => 'Priya Devi',
                'email' => 'priya@siswa.upsi.edu.my',
                'student_id' => 'CD21004',
                'services' => [
                    ['title' => 'English Tutoring', 'description' => 'Improve your English speaking and writing skills', 'price' => 20.00, 'category' => 'Language Services'],
                    ['title' => 'Translation Services', 'description' => 'English-Malay-Tamil translation services', 'price' => 15.00, 'category' => 'Language Services'],
                ]
            ],
            [
                'name' => 'Raj Kumar',
                'email' => 'raj@siswa.upsi.edu.my',
                'student_id' => 'CD21005',
                'services' => [
                    ['title' => 'Event Photography', 'description' => 'Professional photography for events and occasions', 'price' => 60.00, 'category' => 'Photography'],
                    ['title' => 'Portrait Photography', 'description' => 'Individual and group portrait sessions', 'price' => 45.00, 'category' => 'Photography'],
                ]
            ],
            [
                'name' => 'Fatimah Zahra',
                'email' => 'fatimah@siswa.upsi.edu.my',
                'student_id' => 'CD21006',
                'services' => [
                    ['title' => 'Event Planning', 'description' => 'Complete event planning and coordination services', 'price' => 100.00, 'category' => 'Event Planning'],
                    ['title' => 'Wedding Planning', 'description' => 'Specialized wedding planning and coordination', 'price' => 200.00, 'category' => 'Event Planning'],
                ]
            ],
        ];

        foreach ($students as $studentData) {
            $student = User::create([
                'name' => $studentData['name'],
                'email' => $studentData['email'],
                'password' => Hash::make('password'),
                'role' => 'student',
                'phone' => '0123456789',
                'student_id' => $studentData['student_id'],
                'staff_email' => $studentData['email'],
                'verification_status' => 'approved',
                'staff_verified_at' => now(),
                'is_available' => rand(0, 1) == 1, // Random availability
            ]);

            foreach ($studentData['services'] as $serviceData) {
                $category = Category::where('name', $serviceData['category'])->first();
                
                StudentService::create([
                    'user_id' => $student->id,
                    'category_id' => $category->id,
                    'title' => $serviceData['title'],
                    'description' => $serviceData['description'],
                    'suggested_price' => $serviceData['price'],
                    'is_active' => true,
                ]);
            }
        }
    }
}
