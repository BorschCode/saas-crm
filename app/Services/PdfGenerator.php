<?php

namespace App\Services;

use App\Models\Post;
use Spatie\LaravelPdf\Facades\Pdf;

class PdfGenerator
{
    public function generateFromPost(Post $post): string
    {
        $pdf = Pdf::view('pdf.post-export', [
            'post' => $post,
        ])
            ->format('a4')
            ->name("{$post->slug}.pdf");

        return $pdf->base64();
    }

    public function downloadFromPost(Post $post): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        return Pdf::view('pdf.post-export', [
            'post' => $post,
        ])
            ->format('a4')
            ->name("{$post->slug}.pdf")
            ->download();
    }
}
