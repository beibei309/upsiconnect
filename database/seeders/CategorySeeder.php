<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;


class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    $categories = [
        [
            'name' => 'Academic Tutoring',
            'slug' => 'academic-tutoring',
            'description' => 'Help with studies and assignments',
            'image_path' => 'tutor.png',
            'color' => '#4F46E5',
            'is_active' => 1,
        ],
        [
            'name' => 'Programming & Tech',
            'slug' => 'programming-tech',
            'description' => 'Web development, mobile apps, and technical services',
            'image_path' => 'tech.svg',
            'color' => '#10B981',
            'is_active' => 1,
        ],
        [
            'name' => 'Design & Creative',
            'slug' => 'design-creative',
            'description' => 'Graphic design, video editing, and creative services',
            'image_path' => 'graphic.svg',
            'color' => '#F59E0B',
            'is_active' => 1,
        ],
        [
            'name' => 'Housechores',
            'slug' => 'housechore-services',
            'description' => 'Ironing services, house cleaning',
            'image_path' => 'cleaning.png',
            'color' => '#540863',
            'is_active' => 1,
        ],
        [
            'name' => 'Event Planning',
            'slug' => 'event-planning',
            'description' => 'Event organization and planning services',
            'image_path' => 'event.png',
            'color' => '#4FB7B3',
            'is_active' => 1,
        ],
        [
            'name' => 'Runner & Errands',
            'slug' => 'runner-errands',
            'description' => 'Pickup parcel, help buy personal things',
            'image_path' => 'runner.png',
            'color' => '#EC4899',
            'is_active' => 1,
        ],
    ];

    foreach ($categories as $category) {
        \DB::table('categories')->insert($category);
    }
}

}

