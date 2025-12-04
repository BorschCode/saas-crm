<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Inertia\Inertia;
use Inertia\Response;

class BlogController extends Controller
{
    public function index(): Response
    {
        $posts = Post::query()
            ->with(['category', 'user', 'tags'])
            ->where('is_published', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->latest('published_at')
            ->paginate(12);

        $categories = Category::all()->map(function ($category) {
            $category->posts_count = Post::where('category_id', $category->id)->count();

            return $category;
        });

        $popularTags = Tag::all()->map(function ($tag) {
            $tag->posts_count = $tag->posts()->count();

            return $tag;
        })->sortByDesc('posts_count')->take(10)->values();

        return Inertia::render('Blog/Index', [
            'posts' => $posts,
            'categories' => $categories,
            'popularTags' => $popularTags,
        ]);
    }

    public function show(string $slug): Response
    {
        $post = Post::query()
            ->with(['category', 'user', 'tags'])
            ->where('slug', $slug)
            ->where('is_published', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->firstOrFail();

        $relatedPosts = Post::query()
            ->where('category_id', $post->category_id)
            ->where('id', '!=', $post->id)
            ->where('is_published', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->latest('published_at')
            ->limit(3)
            ->get();

        return Inertia::render('Blog/Show', [
            'post' => $post,
            'relatedPosts' => $relatedPosts,
        ]);
    }

    public function category(string $slug): Response
    {
        $category = Category::where('slug', $slug)->firstOrFail();

        $posts = Post::query()
            ->with(['category', 'user', 'tags'])
            ->where('category_id', $category->id)
            ->where('is_published', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->latest('published_at')
            ->paginate(12);

        $categories = Category::all()->map(function ($category) {
            $category->posts_count = Post::where('category_id', $category->id)->count();

            return $category;
        });

        $popularTags = Tag::all()->map(function ($tag) {
            $tag->posts_count = $tag->posts()->count();

            return $tag;
        })->sortByDesc('posts_count')->take(10)->values();

        return Inertia::render('Blog/Index', [
            'posts' => $posts,
            'categories' => $categories,
            'popularTags' => $popularTags,
            'currentCategory' => $category,
        ]);
    }

    public function tag(string $slug): Response
    {
        $tag = Tag::where('slug', $slug)->firstOrFail();

        // For MongoDB, get all tag's posts and manually paginate
        $allPosts = Post::query()
            ->with(['category', 'user', 'tags'])
            ->where('is_published', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->latest('published_at')
            ->get()
            ->filter(fn ($post) => $post->tags->contains('id', $tag->id));

        // Manual pagination
        $perPage = 12;
        $currentPage = request()->get('page', 1);
        $posts = new \Illuminate\Pagination\LengthAwarePaginator(
            $allPosts->forPage($currentPage, $perPage),
            $allPosts->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        $categories = Category::all()->map(function ($category) {
            $category->posts_count = Post::where('category_id', $category->id)->count();

            return $category;
        });

        $popularTags = Tag::all()->map(function ($tag) {
            $tag->posts_count = $tag->posts()->count();

            return $tag;
        })->sortByDesc('posts_count')->take(10)->values();

        return Inertia::render('Blog/Index', [
            'posts' => $posts,
            'categories' => $categories,
            'popularTags' => $popularTags,
            'currentTag' => $tag,
        ]);
    }
}
