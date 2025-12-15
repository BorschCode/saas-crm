<?php

namespace App\Http\Controllers;

use App\Http\Requests\FileUploadRequest;
use App\Jobs\ExtractFileText;
use App\Models\Post;
use App\Models\SourceFile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileUploadController extends Controller
{
    public function create(): Response
    {
        return Inertia::render('Posts/Upload');
    }

    public function store(FileUploadRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $uploadedFiles = $validated['files'];
        $createdPosts = [];

        foreach ($uploadedFiles as $uploadedFile) {
            // Store file
            $filename = $uploadedFile->getClientOriginalName();
            $extension = $uploadedFile->getClientOriginalExtension();
            $fileType = strtolower($extension);
            $uniqueFileName = Str::uuid().'.'.$extension;
            $filePath = $uploadedFile->storeAs('uploads', $uniqueFileName, 'private');

            // Create SourceFile record
            $sourceFile = SourceFile::create([
                'filename' => $filename,
                'file_path' => $filePath,
                'file_type' => $fileType,
                'file_size' => $uploadedFile->getSize(),
                'mime_type' => $uploadedFile->getMimeType(),
                'uploaded_by' => auth()->id(),
                'uploaded_session' => auth()->check() ? null : session()->getId(),
            ]);

            // Create Post record
            $title = pathinfo($filename, PATHINFO_FILENAME);
            $slug = Str::slug($title).'-'.Str::random(8);

            $post = Post::create([
                'user_id' => auth()->id(),
                'guest_session_id' => auth()->check() ? null : session()->getId(),
                'source_file_id' => $sourceFile->id,
                'title' => $title,
                'slug' => $slug,
                'excerpt' => 'Uploaded from file: '.$filename,
                'content' => '',
                'is_published' => false,
                'parsing_state' => 'pending',
            ]);

            // Dispatch extraction job
            ExtractFileText::dispatch($post);

            $createdPosts[] = $post;
        }

        $message = count($createdPosts) === 1
            ? 'File uploaded successfully. Content extraction is in progress.'
            : count($createdPosts).' files uploaded successfully. Content extraction is in progress.';

        return to_route('blog.index')->with('success', $message);
    }

    public function download(SourceFile $file): StreamedResponse
    {
        // Authorization: check if user owns the file or it's their session
        if ($file->uploaded_by && auth()->id() !== $file->uploaded_by) {
            abort(403, 'Unauthorized access to file');
        }

        if ($file->uploaded_session && session()->getId() !== $file->uploaded_session) {
            abort(403, 'Unauthorized access to file');
        }

        return Storage::disk('private')->download($file->file_path, $file->filename);
    }

    public function retry(Post $post): RedirectResponse
    {
        // Authorization: check if user owns the post
        if ($post->user_id && auth()->id() !== $post->user_id) {
            abort(403, 'Unauthorized action');
        }

        if ($post->guest_session_id && session()->getId() !== $post->guest_session_id) {
            abort(403, 'Unauthorized action');
        }

        if (! $post->canRetryParsing()) {
            return back()->withErrors(['error' => 'Parsing has not failed for this post.']);
        }

        // Reset parsing state and retry
        $post->update([
            'parsing_state' => 'pending',
            'parsing_error' => null,
        ]);

        ExtractFileText::dispatch($post);

        return back()->with('success', 'Retrying content extraction.');
    }
}
