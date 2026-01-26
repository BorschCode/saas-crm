<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Services\PdfGenerator;
use Illuminate\Contracts\Support\Responsable;
use Symfony\Component\HttpFoundation\Response;

class PostExportController extends Controller
{
    public function exportPdf(Post $post, PdfGenerator $pdfGenerator): Responsable
    {
        // If post is published, anyone can export it
        if ($post->is_published) {
            return $pdfGenerator->downloadFromPost($post);
        }

        // If post is not published, only the owner can export it
        $canAccess = ($post->user_id && auth()->id() === $post->user_id) ||
                     ($post->guest_session_id && session()->getId() === $post->guest_session_id);

        if (! $canAccess) {
            abort(Response::HTTP_FORBIDDEN, 'Unauthorized action');
        }

        return $pdfGenerator->downloadFromPost($post);
    }
}
