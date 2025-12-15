<?php

namespace App\Jobs;

use App\Models\Post;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class PopulateBlogContent implements ShouldQueue
{
    use Queueable;

    public $tries = 3;

    public $backoff = [30, 120, 600];

    public $timeout = 120;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Post $post
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            if (! $this->post->original_content) {
                throw new \RuntimeException('Post has no original content to populate from');
            }

            // Clean and format the extracted text
            $cleanedContent = $this->cleanContent($this->post->original_content);

            // Update post content and mark as completed
            $this->post->update([
                'content' => $cleanedContent,
                'parsing_state' => 'completed',
                'parsing_completed_at' => now(),
                'parsing_error' => null,
            ]);

            Log::info("Successfully populated content for post {$this->post->id}");
        } catch (\Exception $e) {
            $this->handleFailure($e);
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        $this->handleFailure($exception);
    }

    protected function handleFailure(\Throwable $exception): void
    {
        $this->post->update([
            'parsing_state' => 'failed',
            'parsing_error' => $exception->getMessage(),
        ]);

        Log::error("Failed to populate content for post {$this->post->id}", [
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString(),
        ]);
    }

    protected function cleanContent(string $content): string
    {
        // Remove excessive whitespace
        $content = preg_replace('/[ \t]+/', ' ', $content);

        // Normalize line breaks
        $content = preg_replace('/\r\n/', "\n", $content);
        $content = preg_replace('/\r/', "\n", $content);

        // Remove more than 2 consecutive line breaks
        $content = preg_replace('/\n{3,}/', "\n\n", $content);

        return trim($content);
    }
}
