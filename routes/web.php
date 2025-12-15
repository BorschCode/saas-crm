<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::prefix('blog')->name('blog.')->group(function () {
    Route::get('/', [App\Http\Controllers\BlogController::class, 'index'])->name('index');
    Route::get('/category/{slug}', [App\Http\Controllers\BlogController::class, 'category'])->name('category');
    Route::get('/tag/{slug}', [App\Http\Controllers\BlogController::class, 'tag'])->name('tag');
    Route::get('/{slug}', [App\Http\Controllers\BlogController::class, 'show'])->name('show');
});

// File upload routes
Route::get('/posts/upload', [App\Http\Controllers\FileUploadController::class, 'create'])
    ->name('posts.upload');

Route::post('/posts/upload', [App\Http\Controllers\FileUploadController::class, 'store'])
    ->name('posts.upload.store');

Route::get('/files/{file}/download', [App\Http\Controllers\FileUploadController::class, 'download'])
    ->name('files.download');

Route::post('/posts/{post}/retry-parsing', [App\Http\Controllers\FileUploadController::class, 'retry'])
    ->name('posts.retry-parsing');

Route::get('/posts/{post}/export-pdf', [App\Http\Controllers\PostExportController::class, 'exportPdf'])
    ->name('posts.export-pdf');

require __DIR__.'/settings.php';
