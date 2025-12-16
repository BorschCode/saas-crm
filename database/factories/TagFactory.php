<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tag>
 */
class TagFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tags = [
            'Laravel', 'PHP', 'Vue.js', 'React', 'TypeScript', 'JavaScript',
            'AI', 'Machine Learning', 'Web Development', 'Mobile Apps',
            'Cloud Computing', 'DevOps', 'API', 'Database', 'Security',
            'Remote Work', 'Startup', 'SaaS', 'Open Source', 'Agile',
            'Leadership', 'Team Building', 'Growth Hacking', 'Content Strategy',
            'User Experience', 'Performance', 'Testing', 'Automation', 'Analytics',
        ];

        $name = fake()->unique()->randomElement($tags);

        return [
            'name' => $name,
            'slug' => str($name)->slug(),
        ];
    }
}
