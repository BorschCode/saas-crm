<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = [
            ['name' => 'Technology', 'description' => 'Latest trends in software development, AI, and digital innovation'],
            ['name' => 'Business', 'description' => 'Entrepreneurship, startup insights, and business strategy'],
            ['name' => 'Design', 'description' => 'UI/UX design, creative trends, and visual storytelling'],
            ['name' => 'Marketing', 'description' => 'Digital marketing strategies, SEO, and content creation'],
            ['name' => 'Productivity', 'description' => 'Tips and tools to boost your efficiency and workflow'],
            ['name' => 'Career', 'description' => 'Professional development and career advancement advice'],
            ['name' => 'Lifestyle', 'description' => 'Work-life balance, wellness, and personal growth'],
            ['name' => 'Innovation', 'description' => 'Cutting-edge ideas and breakthrough technologies'],
        ];

        $category = fake()->unique()->randomElement($categories);

        return [
            'name' => $category['name'],
            'slug' => str($category['name'])->slug(),
            'description' => $category['description'],
        ];
    }
}
