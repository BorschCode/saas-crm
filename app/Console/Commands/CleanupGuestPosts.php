<?php

namespace App\Console\Commands;

use App\Models\Post;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CleanupGuestPosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'posts:cleanup-guest {--hours=24 : Delete guest posts older than this many hours}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete guest posts older than the specified time period';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $hours = $this->option('hours');
        $cutoffTime = now()->subHours($hours);

        $this->info("Finding guest posts older than {$hours} hours (before {$cutoffTime})...");

        // Find old guest posts
        $guestPosts = Post::query()
            ->whereNotNull('guest_session_id')
            ->where('created_at', '<', $cutoffTime)
            ->get();

        if ($guestPosts->isEmpty()) {
            $this->info('No guest posts to clean up.');

            return Command::SUCCESS;
        }

        $this->info("Found {$guestPosts->count()} guest posts to delete.");

        $deletedCount = 0;
        $filesDeletedCount = 0;

        foreach ($guestPosts as $post) {
            // Delete associated source file from storage
            if ($post->sourceFile) {
                try {
                    if (Storage::disk('private')->exists($post->sourceFile->file_path)) {
                        Storage::disk('private')->delete($post->sourceFile->file_path);
                        $filesDeletedCount++;
                    }

                    $post->sourceFile->delete();
                } catch (\Exception $e) {
                    $this->error("Failed to delete file for post {$post->id}: {$e->getMessage()}");
                }
            }

            // Delete the post
            $post->delete();
            $deletedCount++;
        }

        $this->info("Deleted {$deletedCount} guest posts and {$filesDeletedCount} associated files.");

        return Command::SUCCESS;
    }
}
