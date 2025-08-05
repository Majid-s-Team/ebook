<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BookCategory;

class BookCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Science Fiction', 'description' => 'Books about futuristic science and technology.', 'is_active' => true, 'is_popular' => true],
            ['name' => 'Romance', 'description' => 'Love stories and romantic novels.', 'is_active' => true, 'is_popular' => true],
            ['name' => 'Mystery', 'description' => 'Crime, thrillers, and whodunits.', 'is_active' => true, 'is_popular' => false],
            ['name' => 'Fantasy', 'description' => 'Magical worlds and epic tales.', 'is_active' => true, 'is_popular' => true],
            ['name' => 'Biography', 'description' => 'Life stories of notable people.', 'is_active' => true, 'is_popular' => false],
            ['name' => 'Self Help', 'description' => 'Guides for personal development.', 'is_active' => true, 'is_popular' => true],
            ['name' => 'History', 'description' => 'Books on historical events.', 'is_active' => true, 'is_popular' => false],
            ['name' => 'Children', 'description' => 'Books for kids and young readers.', 'is_active' => true, 'is_popular' => true],
            ['name' => 'Health & Fitness', 'description' => 'Healthy living and exercise.', 'is_active' => true, 'is_popular' => false],
            ['name' => 'Horror', 'description' => 'Scary and spine-chilling stories.', 'is_active' => true, 'is_popular' => false],
        ];

        foreach ($categories as $category) {
            BookCategory::create($category);
        }
    }
}
