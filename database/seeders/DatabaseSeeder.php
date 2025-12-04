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

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'job_title' => 'System Administrator',
        ]);

        $team = Team::create([
            'name' => 'Acme Corporation',
            'slug' => 'acme-corp',
            'description' => 'Main company team',
            'owner_id' => $admin->id,
            'is_active' => true,
        ]);

        $admin->update(['current_team_id' => $team->id]);
        $admin->teams()->attach($team->id, ['role' => 'admin']);

        $users = User::factory(5)->create();
        foreach ($users as $user) {
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

        // Create blog content
        $categories = Category::factory(5)->create();
        $tags = Tag::factory(15)->create();

        $allUsers = User::all();

        foreach (range(1, 30) as $index) {
            $post = Post::factory()->create([
                'user_id' => $allUsers->random()->id,
                'category_id' => $categories->random()->id,
                'is_published' => true,
                'published_at' => now()->subDays(rand(1, 365)),
            ]);

            $post->tags()->attach(
                $tags->random(rand(2, 5))->pluck('id')->toArray()
            );
        }
    }
}
