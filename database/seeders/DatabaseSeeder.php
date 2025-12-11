<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\StudentService;
use App\Models\Review;
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
            ['name' => 'Academic Tutoring','slug' => 'academic-tutoring', 'description' => 'Help with studies and assignments','image_path' => 'tutor.png','color' => '#4F46E5','is_active' => true],
            ['name' => 'Programming & Tech','slug' => 'programming-tech', 'description' => 'Web development, mobile apps, and technical services','image_path' => 'tech.svg','color' => '#10B981','is_active' => true],
            ['name' => 'Design & Creative','slug' => 'design-creative', 'description' => 'Graphic design, video editing, and creative services','image_path' => 'graphic.svg','color' => '#F59E0B','is_active' => true],
            ['name' => 'Housechores','slug' => 'housechores', 'description' => 'Ironing services, house cleaning, laundry helper','image_path' => 'cleaning.png','color' => '#540863','is_active' => true],
            ['name' => 'Event Planning','slug' => 'event-planning', 'description' => 'Event organization and planning services','image_path' => 'event.png','color' => '#4FB7B3','is_active' => true],
            ['name' => 'Runner & Errands','slug' => 'runner-errands', 'description' => 'Pickup parcel, help buy personal things','image_path' => 'runner.png','color' => '#EC4899','is_active' => true],
        ];

        foreach ($categories as $categoryData) {
            Category::create([
                'name' => $categoryData['name'],
                'slug' => Str::slug($categoryData['name']),
                'description' => $categoryData['description'],
                'image_path' => $categoryData['image_path'],
                'color' => $categoryData['color'],
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
                    ['title' => 'Mathematics Tutoring','image_path' => 'service_tutor.jpg', 'description' => 'Expert help in calculus, algebra, and statistics', 'price' => 25.00, 'category' => 'Academic Tutoring'],
                    ['title' => 'Physics Problem Solving','image_path' => 'service_tutor.jpg', 'description' => 'Assistance with physics assignments and concepts', 'price' => 30.00, 'category' => 'Academic Tutoring'],
                ]
            ],
            [
                'name' => 'Siti Nurhaliza',
                'email' => 'siti@siswa.upsi.edu.my',
                'student_id' => 'CD21002',
                'services' => [
                    ['title' => 'Web Development','image_path' => 'service_tutor.jpg','description' => 'Full-stack web development using Laravel and React', 'price' => 50.00, 'category' => 'Programming & Tech'],
                    ['title' => 'Mobile App Development','image_path' => 'service_tutor.jpg', 'description' => 'Android and iOS app development', 'price' => 80.00, 'category' => 'Programming & Tech'],
                ]
            ],
            [
                'name' => 'Lim Wei Ming',
                'email' => 'lim@siswa.upsi.edu.my',
                'student_id' => 'CD21003',
                'services' => [
                    ['title' => 'Graphic Design', 'image_path' => 'service_tutor.jpg','description' => 'Logo design, posters, and branding materials', 'price' => 35.00, 'category' => 'Design & Creative'],
                    ['title' => 'Video Editing', 'image_path' => 'service_tutor.jpg','description' => 'Professional video editing for events and promotions', 'price' => 40.00, 'category' => 'Design & Creative'],
                ]
            ],
            [
                'name' => 'Priya Devi',
                'email' => 'priya@siswa.upsi.edu.my',
                'student_id' => 'CD21004',
                'services' => [
                    ['title' => 'English Tutoring','image_path' => 'service_tutor.jpg', 'description' => 'Improve your English speaking and writing skills', 'price' => 20.00, 'category' => 'Academic Tutoring'],
                    ['title' => 'Translation Services','image_path' => 'service_tutor.jpg', 'description' => 'English-Malay-Tamil translation services', 'price' => 15.00, 'category' => 'Academic Tutoring'],
                ]
            ],
            [
                'name' => 'Raj Kumar',
                'email' => 'raj@siswa.upsi.edu.my',
                'student_id' => 'CD21005',
                'services' => [
                    ['title' => 'Event Photography','image_path' => 'service_tutor.jpg', 'description' => 'Professional photography for events and occasions', 'price' => 60.00, 'category' => 'Event Planning'],
                    ['title' => 'Portrait Photography','image_path' => 'service_tutor.jpg', 'description' => 'Individual and group portrait sessions', 'price' => 45.00, 'category' => 'Event Planning'],
                ]
            ],
            [
                'name' => 'Fatimah Zahra',
                'email' => 'fatimah@siswa.upsi.edu.my',
                'student_id' => 'CD21006',
                'services' => [
                    ['title' => 'Event Planning','image_path' => 'service_tutor.jpg', 'description' => 'Complete event planning and coordination services', 'price' => 100.00, 'category' => 'Event Planning'],
                    ['title' => 'Wedding Planning','image_path' => 'service_tutor.jpg', 'description' => 'Specialized wedding planning and coordination', 'price' => 200.00, 'category' => 'Event Planning'],
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
                    'image_path' => $serviceData['image_path'],
                    'description' => $serviceData['description'],
                    'suggested_price' => $serviceData['price'],
                    'is_active' => true,
                ]);
            }
        }
               $allServices = \App\Models\StudentService::all();
               $requester = $communityUser; // Guna user community sedia ada

               foreach ($allServices->random(3) as $service) {
                \App\Models\ServiceRequest::create([
                    'student_service_id' => $service->id,      // Ikut Model (bukan service_id)
                    'requester_id'       => $requester->id,    // Ikut Model (bukan user_id)
                    'provider_id'        => $service->user_id, // Ambil owner servis tu
                    'status'             => 'pending',
                    'message'            => 'Hi, I need help with this!',
                    'offered_price'      => $service->suggested_price,
                    'created_at'         => now(),
               ]);
            }
 
        $ahmad = User::where('email', 'ahmad@siswa.upsi.edu.my')->first();
        $siti = User::where('email', 'siti@siswa.upsi.edu.my')->first();
        $requester = $communityUser; // User Community sudah didefinisi di atas

        $completedRequest = \App\Models\ServiceRequest::first(); 


	if ($ahmad && $siti && $requester && $completedRequest) {

    // 1. Ahmad dapat rating RENDAH (Reviewer: Community User)
    // Ahmad akan muncul di dashboard dengan Average Rating 1.0 (1+1)/2
    Review::create([
        'reviewer_id' => $requester->id,
        'reviewee_id' => $ahmad->id,
        'service_request_id' => $completedRequest->id,
        'conversation_id' => null, // Biarkan null jika conversation_id nullable di DB
        'rating' => 1, 
        'comment' => 'Service was extremely poor and slow. Need improvement.',
    ]);

    // 2. Ahmad dapat rating RENDAH lagi (untuk pastikan Average rendah 1.0)
    Review::create([
        'reviewer_id' => $requester->id,
        'reviewee_id' => $ahmad->id,
        'service_request_id' => $completedRequest->id,
        'conversation_id' => null,
        'rating' => 1, 
        'comment' => 'Second time using, still disappointing.',
    ]);

    // 3. Siti dapat rating TINGGI (Reviewer: Community User)
    // Siti akan muncul di dashboard dengan rating 5.0
    Review::create([
        'reviewer_id' => $requester->id,
        'reviewee_id' => $siti->id,
        'service_request_id' => $completedRequest->id,
        'conversation_id' => null,
        'rating' => 5,
        'comment' => 'Excellent service, highly recommended!',
    ]);
}

        //admin 
        $this->call(AdminSeeder::class);

    }
}
