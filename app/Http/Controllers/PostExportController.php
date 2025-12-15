<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Services\PdfGenerator;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PostExportController extends Controller
{
    public function exportPdf(Post $post, PdfGenerator $pdfGenerator): StreamedResponse
    {
        // Authorization: check if user can view this post
        if ($post->user_id && auth()->id() !== $post->user_id) {
            abort(403, 'Unauthorized action');
        }

        if ($post->guest_session_id && session()->getId() !== $post->guest_session_id) {
            abort(403, 'Unauthorized action');
        }

        // If post is not published and not owned by current user/session, deny access
        if (! $post->is_published) {
            $canAccess = ($post->user_id && auth()->id() === $post->user_id) ||
                         ($post->guest_session_id && session()->getId() === $post->guest_session_id);

            if (! $canAccess) {
                abort(404);
            }
        }

        return $pdfGenerator->downloadFromPost($post);
    }
}
