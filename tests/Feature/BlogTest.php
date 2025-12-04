<?php

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;

use function Pest\Laravel\get;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->category = Category::factory()->create(['name' => 'Technology', 'slug' => 'technology']);
    $this->tag = Tag::factory()->create(['name' => 'Laravel', 'slug' => 'laravel']);
});

test('blog index page displays published posts', function () {
    $publishedPost = Post::factory()->published()->create([
        'user_id' => $this->user->id,
        'category_id' => $this->category->id,
    ]);
    $draftPost = Post::factory()->draft()->create([
        'user_id' => $this->user->id,
        'category_id' => $this->category->id,
    ]);

    get(route('blog.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Blog/Index')
            ->has('posts.data', 1)
            ->where('posts.data.0.id', $publishedPost->id));
});

test('blog index page does not display draft posts', function () {
    $draftPost = Post::factory()->draft()->create([
        'user_id' => $this->user->id,
        'category_id' => $this->category->id,
    ]);

    get(route('blog.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Blog/Index')
            ->has('posts.data', 0));
});

test('blog show page displays published post', function () {
    $post = Post::factory()->published()->create([
        'user_id' => $this->user->id,
        'category_id' => $this->category->id,
        'slug' => 'test-post',
    ]);

    get(route('blog.show', ['slug' => 'test-post']))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Blog/Show')
            ->where('post.id', $post->id)
            ->where('post.title', $post->title));
});

test('blog show page returns 404 for draft posts', function () {
    $post = Post::factory()->draft()->create([
        'user_id' => $this->user->id,
        'category_id' => $this->category->id,
        'slug' => 'draft-post',
    ]);

    get(route('blog.show', ['slug' => 'draft-post']))
        ->assertNotFound();
});

test('blog show page returns 404 for non-existent posts', function () {
    get(route('blog.show', ['slug' => 'non-existent']))
        ->assertNotFound();
});

test('blog category page filters posts by category', function () {
    $otherCategory = Category::factory()->create(['slug' => 'design']);

    $technologyPost = Post::factory()->published()->create([
        'user_id' => $this->user->id,
        'category_id' => $this->category->id,
    ]);

    $designPost = Post::factory()->published()->create([
        'user_id' => $this->user->id,
        'category_id' => $otherCategory->id,
    ]);

    get(route('blog.category', ['slug' => 'technology']))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Blog/Index')
            ->has('posts.data', 1)
            ->where('posts.data.0.id', $technologyPost->id)
            ->where('currentCategory.id', $this->category->id));
});

test('blog tag page filters posts by tag', function () {
    $otherTag = Tag::factory()->create(['slug' => 'php']);

    $laravelPost = Post::factory()->published()->create([
        'user_id' => $this->user->id,
        'category_id' => $this->category->id,
    ]);
    $laravelPost->tags()->attach($this->tag);

    $phpPost = Post::factory()->published()->create([
        'user_id' => $this->user->id,
        'category_id' => $this->category->id,
    ]);
    $phpPost->tags()->attach($otherTag);

    get(route('blog.tag', ['slug' => 'laravel']))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Blog/Index')
            ->has('posts.data', 1)
            ->where('posts.data.0.id', $laravelPost->id)
            ->where('currentTag.id', $this->tag->id));
});

test('blog show page displays related posts from same category', function () {
    $mainPost = Post::factory()->published()->create([
        'user_id' => $this->user->id,
        'category_id' => $this->category->id,
        'slug' => 'main-post',
    ]);

    $relatedPost1 = Post::factory()->published()->create([
        'user_id' => $this->user->id,
        'category_id' => $this->category->id,
    ]);

    $relatedPost2 = Post::factory()->published()->create([
        'user_id' => $this->user->id,
        'category_id' => $this->category->id,
    ]);

    $otherCategoryPost = Post::factory()->published()->create([
        'user_id' => $this->user->id,
        'category_id' => Category::factory()->create()->id,
    ]);

    get(route('blog.show', ['slug' => 'main-post']))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Blog/Show')
            ->has('relatedPosts', 2));
});

test('post model has correct relationships', function () {
    $post = Post::factory()->create([
        'user_id' => $this->user->id,
        'category_id' => $this->category->id,
    ]);
    $post->tags()->attach($this->tag);

    expect($post->user)->toBeInstanceOf(User::class)
        ->and($post->category)->toBeInstanceOf(Category::class)
        ->and($post->tags)->toHaveCount(1)
        ->and($post->tags->first())->toBeInstanceOf(Tag::class);
});

test('category model counts posts correctly', function () {
    Post::factory()->count(3)->published()->create([
        'user_id' => $this->user->id,
        'category_id' => $this->category->id,
    ]);

    $category = Category::find($this->category->id);

    expect($category->posts()->count())->toBe(3);
});

test('tag model counts posts correctly', function () {
    $posts = Post::factory()->count(2)->published()->create([
        'user_id' => $this->user->id,
        'category_id' => $this->category->id,
    ]);

    foreach ($posts as $post) {
        $post->tags()->attach($this->tag);
    }

    $tag = Tag::find($this->tag->id);

    expect($tag->posts()->count())->toBe(2);
});

test('blog search filters posts by search query', function () {
    $searchablePost = Post::factory()->published()->create([
        'user_id' => $this->user->id,
        'category_id' => $this->category->id,
        'title' => 'Laravel Testing Best Practices',
        'excerpt' => 'Learn how to test Laravel applications effectively',
        'content' => 'This is a comprehensive guide about Laravel testing',
    ]);

    $otherPost = Post::factory()->published()->create([
        'user_id' => $this->user->id,
        'category_id' => $this->category->id,
        'title' => 'Vue.js Components Guide',
        'excerpt' => 'Building components in Vue.js',
        'content' => 'This guide covers Vue.js component development',
    ]);

    // Index the posts for search
    $searchablePost->searchable();
    $otherPost->searchable();

    get(route('blog.index', ['search' => 'Laravel']))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Blog/Index')
            ->where('searchQuery', 'Laravel')
            ->has('posts.data', 1)
            ->where('posts.data.0.id', $searchablePost->id));
});

test('blog search returns empty results for no matches', function () {
    $post = Post::factory()->published()->create([
        'user_id' => $this->user->id,
        'category_id' => $this->category->id,
        'title' => 'Vue.js Guide',
    ]);

    $post->searchable();

    get(route('blog.index', ['search' => 'NonExistentTerm']))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Blog/Index')
            ->where('searchQuery', 'NonExistentTerm')
            ->has('posts.data', 0));
});

test('blog search finds posts by excerpt', function () {
    $post = Post::factory()->published()->create([
        'user_id' => $this->user->id,
        'category_id' => $this->category->id,
        'title' => 'Getting Started',
        'excerpt' => 'MongoDB is a powerful NoSQL database',
        'content' => 'Some general content here',
    ]);

    $post->searchable();

    get(route('blog.index', ['search' => 'MongoDB']))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Blog/Index')
            ->where('searchQuery', 'MongoDB')
            ->has('posts.data', 1)
            ->where('posts.data.0.id', $post->id));
});

test('blog search finds posts by content', function () {
    $post = Post::factory()->published()->create([
        'user_id' => $this->user->id,
        'category_id' => $this->category->id,
        'title' => 'Database Guide',
        'excerpt' => 'A quick introduction',
        'content' => 'This post covers Eloquent ORM in detail with examples',
    ]);

    $post->searchable();

    get(route('blog.index', ['search' => 'Eloquent']))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Blog/Index')
            ->where('searchQuery', 'Eloquent')
            ->has('posts.data', 1)
            ->where('posts.data.0.id', $post->id));
});

test('blog search returns multiple matching posts', function () {
    $post1 = Post::factory()->published()->create([
        'user_id' => $this->user->id,
        'category_id' => $this->category->id,
        'title' => 'PHP Best Practices',
        'excerpt' => 'Learn PHP coding standards',
        'content' => 'PHP development tips',
    ]);

    $post2 = Post::factory()->published()->create([
        'user_id' => $this->user->id,
        'category_id' => $this->category->id,
        'title' => 'Advanced PHP Techniques',
        'excerpt' => 'Master PHP programming',
        'content' => 'Deep dive into PHP',
    ]);

    $post3 = Post::factory()->published()->create([
        'user_id' => $this->user->id,
        'category_id' => $this->category->id,
        'title' => 'JavaScript Basics',
        'excerpt' => 'Introduction to JS',
        'content' => 'Learn JavaScript fundamentals',
    ]);

    $post1->searchable();
    $post2->searchable();
    $post3->searchable();

    get(route('blog.index', ['search' => 'PHP']))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Blog/Index')
            ->where('searchQuery', 'PHP')
            ->has('posts.data', 2));
});

test('blog search only returns published posts', function () {
    $publishedPost = Post::factory()->published()->create([
        'user_id' => $this->user->id,
        'category_id' => $this->category->id,
        'title' => 'Published Laravel Post',
        'excerpt' => 'This is published',
        'content' => 'Laravel content here',
    ]);

    $draftPost = Post::factory()->draft()->create([
        'user_id' => $this->user->id,
        'category_id' => $this->category->id,
        'title' => 'Draft Laravel Post',
        'excerpt' => 'This is a draft',
        'content' => 'Laravel content in draft',
    ]);

    $publishedPost->searchable();
    $draftPost->searchable();

    get(route('blog.index', ['search' => 'Laravel']))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Blog/Index')
            ->where('searchQuery', 'Laravel')
            ->has('posts.data', 1)
            ->where('posts.data.0.id', $publishedPost->id));
});

test('blog search is case insensitive', function () {
    $post = Post::factory()->published()->create([
        'user_id' => $this->user->id,
        'category_id' => $this->category->id,
        'title' => 'TypeScript Tutorial',
        'excerpt' => 'Learn TypeScript basics',
        'content' => 'TypeScript is a typed superset of JavaScript',
    ]);

    $post->searchable();

    // Search with lowercase
    get(route('blog.index', ['search' => 'typescript']))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Blog/Index')
            ->where('searchQuery', 'typescript')
            ->has('posts.data', 1)
            ->where('posts.data.0.id', $post->id));

    // Search with uppercase
    get(route('blog.index', ['search' => 'TYPESCRIPT']))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Blog/Index')
            ->where('searchQuery', 'TYPESCRIPT')
            ->has('posts.data', 1)
            ->where('posts.data.0.id', $post->id));
});

test('blog search with empty string returns all posts', function () {
    Post::factory()->count(3)->published()->create([
        'user_id' => $this->user->id,
        'category_id' => $this->category->id,
    ]);

    get(route('blog.index', ['search' => '']))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Blog/Index')
            ->where('searchQuery', null)
            ->has('posts.data', 3));
});

test('blog search maintains pagination', function () {
    // Create 15 posts all matching search term
    $posts = Post::factory()->count(15)->published()->create([
        'user_id' => $this->user->id,
        'category_id' => $this->category->id,
        'title' => 'Testing Laravel Applications',
    ]);

    foreach ($posts as $post) {
        $post->searchable();
    }

    // First page should have 12 posts (default per page)
    get(route('blog.index', ['search' => 'Testing']))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Blog/Index')
            ->where('searchQuery', 'Testing')
            ->has('posts.data', 12)
            ->where('posts.current_page', 1)
            ->where('posts.last_page', 2));

    // Second page should have 3 posts
    get(route('blog.index', ['search' => 'Testing', 'page' => 2]))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Blog/Index')
            ->where('searchQuery', 'Testing')
            ->has('posts.data', 3)
            ->where('posts.current_page', 2));
});
