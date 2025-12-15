<?php

namespace App\Jobs;

use App\Models\Post;
use App\Services\FileTextExtractor;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ExtractFileText implements ShouldQueue
{
    use Queueable;

    public $tries = 3;

    public $backoff = [60, 300, 900];

    public $timeout = 300;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Post $post
    ) {}

    /**
     * Execute the job.
     */
    public function handle(FileTextExtractor $extractor): void
    {
        // Update state to processing
        $this->post->update(['parsing_state' => 'processing']);

        try {
            $sourceFile = $this->post->sourceFile;

            if (! $sourceFile) {
                throw new \RuntimeException('Post has no source file');
            }

            $filePath = Storage::disk('private')->path($sourceFile->file_path);

            if (! file_exists($filePath)) {
                throw new \RuntimeException('Source file not found');
            }

            // Extract text based on file type
            $extractedText = match ($sourceFile->file_type) {
                'pdf' => $extractor->extractPdf($filePath),
                'csv' => $extractor->extractCsv($filePath),
                default => throw new \RuntimeException("Unsupported file type: {$sourceFile->file_type}"),
            };

            // Store extracted text
            $this->post->update([
                'original_content' => $extractedText,
                'parsing_state' => 'processing',
            ]);

            // Dispatch next job to populate content
            PopulateBlogContent::dispatch($this->post);

            Log::info("Successfully extracted text from file for post {$this->post->id}");
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

        Log::error("Failed to extract text from file for post {$this->post->id}", [
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString(),
        ]);
    }
}
