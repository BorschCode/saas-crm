<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Client;
use App\Models\Contact;
use App\Models\Deal;
use App\Models\Post;
use App\Models\Project;
use App\Models\Tag;
use App\Models\Task;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::factory()->create([
            'name' => 'Sarah Johnson',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'job_title' => 'CEO & Founder',
        ]);

        $team = Team::create([
            'name' => 'TechVenture Solutions',
            'slug' => 'techventure',
            'description' => 'Innovative software development and digital transformation company',
            'owner_id' => $admin->id,
            'is_active' => true,
        ]);

        $admin->update(['current_team_id' => $team->id]);
        $admin->teams()->attach($team->id, ['role' => 'admin']);

        // Create team members with realistic roles
        $jobTitles = [
            'Senior Full-Stack Developer',
            'Product Manager',
            'UX/UI Designer',
            'DevOps Engineer',
            'Content Strategist',
        ];

        foreach ($jobTitles as $index => $jobTitle) {
            $user = User::factory()->create([
                'job_title' => $jobTitle,
            ]);
            $user->update(['current_team_id' => $team->id]);
            $user->teams()->attach($team->id, ['role' => 'member']);
        }

        $clients = Client::factory(10)->create(['team_id' => $team->id]);

        foreach ($clients as $client) {
            Contact::factory(rand(1, 3))->create([
                'team_id' => $team->id,
                'client_id' => $client->id,
            ]);

            Deal::factory(rand(0, 2))->create([
                'team_id' => $team->id,
                'client_id' => $client->id,
            ]);
        }

        $projects = Project::factory(15)->create([
            'team_id' => $team->id,
            'client_id' => $clients->random()->id,
        ]);

        foreach ($projects as $project) {
            Task::factory(rand(5, 15))->create([
                'team_id' => $team->id,
                'project_id' => $project->id,
            ]);
        }

        // Create blog content with realistic data
        $categoryData = [
            ['name' => 'Technology', 'slug' => 'technology', 'description' => 'Latest trends in software development, AI, and digital innovation'],
            ['name' => 'Business', 'slug' => 'business', 'description' => 'Entrepreneurship, startup insights, and business strategy'],
            ['name' => 'Design', 'slug' => 'design', 'description' => 'UI/UX design, creative trends, and visual storytelling'],
            ['name' => 'Marketing', 'slug' => 'marketing', 'description' => 'Digital marketing strategies, SEO, and content creation'],
            ['name' => 'Productivity', 'slug' => 'productivity', 'description' => 'Tips and tools to boost your efficiency and workflow'],
            ['name' => 'Career', 'slug' => 'career', 'description' => 'Professional development and career advancement advice'],
            ['name' => 'Lifestyle', 'slug' => 'lifestyle', 'description' => 'Work-life balance, wellness, and personal growth'],
            ['name' => 'Innovation', 'slug' => 'innovation', 'description' => 'Cutting-edge ideas and breakthrough technologies'],
        ];

        $categories = collect($categoryData)->map(fn ($cat) => Category::create($cat));

        $tagNames = [
            'Laravel', 'PHP', 'Vue.js', 'React', 'TypeScript', 'JavaScript',
            'AI', 'Machine Learning', 'Web Development', 'Mobile Apps',
            'Cloud Computing', 'DevOps', 'API', 'Database', 'Security',
            'Remote Work', 'Startup', 'SaaS', 'Open Source', 'Agile',
        ];

        $tags = collect($tagNames)->map(fn ($name) => Tag::create([
            'name' => $name,
            'slug' => Str::slug($name),
        ]));

        $allUsers = User::all();

        // Create 50 blog posts with realistic distribution
        foreach (range(1, 50) as $index) {
            $isPublished = $index <= 40; // 40 published, 10 drafts

            $post = Post::factory()->create([
                'user_id' => $allUsers->random()->id,
                'category_id' => $categories->random()->id,
                'is_published' => $isPublished,
                'published_at' => $isPublished ? now()->subDays(rand(1, 365)) : null,
            ]);

            // Attach 2-4 relevant tags per post
            $post->tags()->attach(
                $tags->random(rand(2, 4))->pluck('id')->toArray()
            );
        }
    }
}
