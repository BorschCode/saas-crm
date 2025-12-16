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
            ->name("{$post->slug}.pdf")
            ->withBrowsershot(function ($browsershot) {
                $browsershot->noSandbox()
                    ->setOption('args', ['--disable-web-security']);
            });

        return $pdf->base64();
    }

    public function downloadFromPost(Post $post): \Illuminate\Contracts\Support\Responsable
    {
        return Pdf::view('pdf.post-export', [
            'post' => $post,
        ])
            ->format('a4')
            ->name("{$post->slug}.pdf")
            ->withBrowsershot(function ($browsershot) {
                $browsershot->noSandbox()
                    ->setOption('args', ['--disable-web-security']);
            })
            ->download();
    }
}
