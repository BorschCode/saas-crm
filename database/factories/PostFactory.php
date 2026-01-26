<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Get all available images from the public/img directory.
     *
     * @return array<string|null>
     */
    protected static function getAvailableImages(): array
    {
        $images = [null];
        $publicPath = public_path('img');

        if (is_dir($publicPath)) {
            foreach (glob($publicPath.'/*.{png,jpg,jpeg,gif,webp}', GLOB_BRACE) as $imagePath) {
                $images[] = '/img/'.basename($imagePath);
            }

            $svgPath = $publicPath.'/svg';
            if (is_dir($svgPath)) {
                foreach (glob($svgPath.'/*.svg') as $svgImage) {
                    $images[] = '/img/svg/'.basename($svgImage);
                }
            }
        }

        return $images;
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $titles = [
            'Building Scalable Applications with Laravel and Vue.js',
            'The Future of AI in Web Development',
            'Mastering TypeScript: A Complete Guide',
            '10 Productivity Hacks for Remote Teams',
            'How to Launch Your SaaS Product in 2025',
            'Understanding Modern Database Design Patterns',
            'The Art of Writing Clean, Maintainable Code',
            'DevOps Best Practices for Small Teams',
            'Creating Engaging User Experiences with Tailwind CSS',
            'From Idea to MVP: A Startup Journey',
            'API Design Principles Every Developer Should Know',
            'The Rise of Serverless Architecture',
            'Building Real-Time Features with WebSockets',
            'Security Best Practices for Web Applications',
            'Optimizing Performance in Single Page Applications',
            'The Complete Guide to Test-Driven Development',
            'Microservices vs Monoliths: Making the Right Choice',
            'Effective Team Communication in Remote Work',
            'Building a Personal Brand as a Developer',
            'The Power of Open Source Contribution',
        ];

        $title = fake()->randomElement($titles);
        $topic = str_replace(
            ['Building ', 'The ', 'How to ', 'Creating ', 'Understanding '],
            '',
            $title
        );

        $images = static::getAvailableImages();

        $contentTemplates = [
            <<<'MD'
## Introduction

{topic} has revolutionized the way we approach modern software development. In this article, we'll explore why this topic matters and how you can apply it in real-world projects.

## The Problem It Solves

Traditional approaches often struggle with scalability, maintainability, and developer experience. {topic} addresses these challenges head-on.

## Core Concepts

Understanding the fundamentals is crucial:

- **Architecture** – how everything fits together
- **Workflow** – the development process
- **Tooling** – essential utilities
- **Testing** – ensuring reliability

## Implementation Guide

Step-by-step guidance to help you integrate {topic} into production-ready applications.

## Wrapping Up

Whether you're building a startup MVP or a large-scale system, {topic} provides a solid foundation.
MD,

            <<<'MD'
## Getting Started with {topic}

When I first encountered {topic}, I underestimated its impact. Over time, it became a core part of my workflow.

## Key Lessons

1. **Start small** and iterate
2. **Learn from the community**
3. **Practice consistently**

## Real-World Usage

Applying {topic} in production environments leads to better code quality and long-term maintainability.

## Final Thoughts

If you're serious about improving your development process, {topic} is worth mastering.
MD,
        ];

        $markdown = str_replace(
            '{topic}',
            $topic,
            fake()->randomElement($contentTemplates)
        );

        return [
            'user_id' => \App\Models\User::factory(),
            'category_id' => \App\Models\Category::factory(),

            'title' => $title,
            'slug' => Str::slug($title).'-'.fake()->unique()->numberBetween(1000, 9999),

            'excerpt' => 'Discover '.lcfirst($topic).' and learn how it can improve your development workflow.',

            // 🔥 КЛЮЧОВЕ: HTML, НЕ Markdown
            'content' => Str::markdown($markdown),

            'featured_image' => fake()->randomElement($images),

            'is_published' => fake()->boolean(70),
            'published_at' => fake()->optional(0.7)->dateTimeBetween('-1 year', 'now'),
        ];
    }

    public function published(): static
    {
        return $this->state(fn () => [
            'is_published' => true,
            'published_at' => fake()->dateTimeBetween('-1 year', 'now'),
        ]);
    }

    public function draft(): static
    {
        return $this->state(fn () => [
            'is_published' => false,
            'published_at' => null,
        ]);
    }
}
