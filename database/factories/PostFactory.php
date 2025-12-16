<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

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
        $images = [null]; // Include null for posts without images
        $publicPath = public_path('img');

        if (is_dir($publicPath)) {
            // Get images from main img directory
            $mainImages = glob($publicPath.'/*.{png,jpg,jpeg,gif,webp}', GLOB_BRACE);
            foreach ($mainImages as $imagePath) {
                $images[] = '/img/'.basename($imagePath);
            }

            // Get SVG images from svg subdirectory
            $svgPath = $publicPath.'/svg';
            if (is_dir($svgPath)) {
                $svgImages = glob($svgPath.'/*.svg');
                foreach ($svgImages as $svgPath) {
                    $images[] = '/img/svg/'.basename($svgPath);
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
        $images = static::getAvailableImages();

        $contentTemplates = [
            "In today's rapidly evolving tech landscape, understanding {topic} has become more crucial than ever. This comprehensive guide will walk you through everything you need to know to get started.\n\n## Why This Matters\n\nThe importance of {topic} cannot be overstated. Companies across the globe are increasingly relying on these technologies to stay competitive and deliver value to their customers.\n\n## Getting Started\n\nBefore diving deep, it's essential to understand the fundamentals. Here are the key concepts you should be familiar with:\n\n- Core principles and methodologies\n- Industry best practices\n- Common pitfalls to avoid\n- Real-world implementation strategies\n\n## Advanced Techniques\n\nOnce you've mastered the basics, you can explore more advanced approaches that will take your skills to the next level. These techniques have been proven effective in production environments.\n\n## Conclusion\n\nAs we've explored throughout this article, {topic} is a powerful tool in any developer's arsenal. With practice and dedication, you'll be able to leverage these concepts to build better, more efficient solutions.",

            "When I first started working with {topic}, I had no idea how transformative it would be for my career. Let me share what I've learned along the way.\n\n## The Beginning\n\nLike many developers, I was skeptical at first. The learning curve seemed steep, and I wasn't sure if the investment would pay off. But I decided to take the plunge anyway.\n\n## Key Lessons Learned\n\nThroughout my journey, several insights stood out:\n\n1. **Start Small**: Don't try to boil the ocean. Begin with simple projects and gradually increase complexity.\n\n2. **Learn from Others**: The community is incredibly helpful. Don't hesitate to ask questions and share your experiences.\n\n3. **Practice Consistently**: Regular practice is more effective than occasional marathons.\n\n## Practical Applications\n\nHere's how I've applied these concepts in real-world scenarios. The results have been remarkable, leading to improved performance, better code quality, and happier clients.\n\n## Final Thoughts\n\nLooking back, diving into {topic} was one of the best decisions I made. If you're on the fence, I encourage you to give it a try. The skills you'll gain are invaluable.",

            "## Introduction\n\n{topic} has revolutionized the way we approach modern software development. In this article, we'll explore why this technology has gained so much traction and how you can leverage it in your projects.\n\n## The Problem It Solves\n\nTraditional approaches often struggle with scalability, maintainability, and developer experience. {topic} addresses these challenges head-on with innovative solutions.\n\n## Core Concepts\n\nUnderstanding the fundamentals is crucial for success. Let's break down the essential components:\n\n- **Architecture**: How everything fits together\n- **Workflow**: The development process\n- **Tooling**: Essential tools and utilities\n- **Testing**: Ensuring quality and reliability\n\n## Implementation Guide\n\nReady to get your hands dirty? Here's a step-by-step approach to implementing {topic} in your next project. We'll cover setup, configuration, and common patterns that work well in production.\n\n## Best Practices\n\nOver time, the community has established proven patterns and practices. Following these guidelines will help you avoid common mistakes and build more robust solutions.\n\n## Wrapping Up\n\nWhether you're building a startup MVP or a large-scale enterprise application, {topic} provides the tools and patterns you need to succeed. Start experimenting today!",
        ];

        $topic = str_replace(['Building ', 'The ', 'How to ', 'Creating ', 'Understanding '], '', $title);
        $content = str_replace('{topic}', $topic, fake()->randomElement($contentTemplates));

        $excerpt = 'Discover ' . lcfirst($topic) . ' and learn how it can transform your approach to modern development. ' . fake()->sentence();

        return [
            'user_id' => \App\Models\User::factory(),
            'category_id' => \App\Models\Category::factory(),
            'title' => $title,
            'slug' => str($title)->slug().'-'.fake()->unique()->numberBetween(1000, 9999),
            'excerpt' => $excerpt,
            'content' => $content,
            'featured_image' => fake()->randomElement($images),
            'is_published' => fake()->boolean(70),
            'published_at' => fake()->optional(0.7)->dateTimeBetween('-1 year', 'now'),
        ];
    }

    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_published' => true,
            'published_at' => fake()->dateTimeBetween('-1 year', 'now'),
        ]);
    }

    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_published' => false,
            'published_at' => null,
        ]);
    }
}
