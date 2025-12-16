<script setup lang="ts">
import WelcomeHeader from '@/components/WelcomeHeader.vue';
import { Head, Link } from '@inertiajs/vue3';
import { Download } from 'lucide-vue-next';
import { exportPdf } from '@/actions/App/Http/Controllers/PostExportController';

interface Category {
    id: number;
    name: string;
    slug: string;
    description?: string;
}

interface Tag {
    id: number;
    name: string;
    slug: string;
}

interface User {
    id: number;
    name: string;
    avatar?: string;
}

interface Post {
    id: number;
    title: string;
    slug: string;
    excerpt?: string;
    content: string;
    featured_image?: string;
    published_at: string;
    category: Category | null;
    user: User | null;
    tags: Tag[];
}

defineProps<{
    post: Post;
    relatedPosts: Post[];
}>();

const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
    });
};
</script>

<template>
    <Head :title="post.title" />

    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50">
        <WelcomeHeader :can-register="true" />

        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div
            class="flex h-full flex-1 flex-col gap-8"
        >
            <!-- Article Header -->
            <article class="mx-auto w-full max-w-4xl bg-white rounded-xl shadow-md p-6 md:p-8">
                <div class="mb-6 flex flex-col gap-4">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <div class="flex flex-wrap items-center gap-3 text-sm">
                            <Link
                                v-if="post.category"
                                :href="`/blog/category/${post.category.slug}`"
                                class="rounded-full bg-blue-100 px-4 py-1.5 font-medium text-blue-700 hover:bg-blue-200 transition-colors"
                            >
                                {{ post.category.name }}
                            </Link>
                            <span class="text-gray-500">
                                {{ formatDate(post.published_at) }}
                            </span>
                            <template v-if="post.user">
                                <span class="text-gray-400">•</span>
                                <span class="text-gray-600">
                                    By {{ post.user.name }}
                                </span>
                            </template>
                        </div>
                        <a
                            :href="exportPdf.url(post.id)"
                            download
                            class="inline-flex items-center gap-2 rounded-full bg-blue-100 px-4 py-1.5 text-sm font-medium text-blue-700 transition-colors hover:bg-blue-200"
                        >
                            <Download class="h-4 w-4" />
                            Download PDF
                        </a>
                    </div>

                    <h1
                        class="text-4xl font-bold tracking-tight text-gray-900 md:text-5xl"
                    >
                        {{ post.title }}
                    </h1>

                    <p
                        v-if="post.excerpt"
                        class="text-xl text-gray-600"
                    >
                        {{ post.excerpt }}
                    </p>

                    <div v-if="post.tags.length > 0" class="flex flex-wrap gap-2">
                        <Link
                            v-for="tag in post.tags"
                            :key="tag.id"
                            :href="`/blog/tag/${tag.slug}`"
                            class="rounded-full border border-gray-300 px-3 py-1 text-sm text-gray-700 transition-colors hover:bg-gray-100"
                        >
                            # {{ tag.name }}
                        </Link>
                    </div>
                </div>

                <!-- Featured Image -->
                <div
                    v-if="post.featured_image"
                    class="mb-8 aspect-video overflow-hidden rounded-xl"
                >
                    <img
                        :src="post.featured_image"
                        :alt="post.title"
                        class="h-full w-full object-cover"
                    />
                </div>

                <!-- Content -->
                <div
                    class="prose prose-lg prose-slate max-w-none prose-headings:text-gray-900 prose-p:text-gray-700 prose-a:text-blue-600 prose-strong:text-gray-900 prose-code:text-gray-900"
                    v-html="post.content"
                />

                <!-- Author Info -->
                <div
                    v-if="post.user"
                    class="mt-12 flex items-center gap-4 rounded-xl border border-gray-200 bg-gray-50 p-6"
                >
                    <div
                        class="flex h-16 w-16 flex-shrink-0 items-center justify-center rounded-full bg-blue-100 text-2xl font-bold text-blue-700"
                    >
                        {{ post.user.name.charAt(0) }}
                    </div>
                    <div class="flex flex-col">
                        <span class="text-sm text-gray-500">
                            Written by
                        </span>
                        <span
                            class="text-lg font-semibold text-gray-900"
                        >
                            {{ post.user.name }}
                        </span>
                    </div>
                </div>
            </article>

            <!-- Related Posts -->
            <div
                v-if="relatedPosts.length > 0"
                class="mx-auto w-full max-w-4xl"
            >
                <h2
                    class="mb-6 text-2xl font-bold text-gray-900"
                >
                    Related Posts
                </h2>

                <div class="grid gap-6 md:grid-cols-3">
                    <Link
                        v-for="relatedPost in relatedPosts"
                        :key="relatedPost.id"
                        :href="`/blog/${relatedPost.slug}`"
                        class="group flex flex-col overflow-hidden rounded-xl border border-gray-200 bg-white transition-all hover:shadow-lg"
                    >
                        <div
                            class="relative aspect-video overflow-hidden bg-gray-100"
                        >
                            <img
                                v-if="relatedPost.featured_image"
                                :src="relatedPost.featured_image"
                                :alt="relatedPost.title"
                                class="h-full w-full object-cover transition-transform group-hover:scale-105"
                            />
                            <div
                                v-else
                                class="flex h-full w-full items-center justify-center bg-gradient-to-br from-blue-100 to-purple-100"
                            >
                                <span
                                    class="text-3xl font-bold text-gray-300"
                                >
                                    {{ relatedPost.title.charAt(0) }}
                                </span>
                            </div>
                        </div>

                        <div class="flex flex-col gap-2 p-4">
                            <span class="text-xs text-gray-500">
                                {{ formatDate(relatedPost.published_at) }}
                            </span>
                            <h3
                                class="line-clamp-2 font-semibold text-gray-900 group-hover:text-blue-600"
                            >
                                {{ relatedPost.title }}
                            </h3>
                            <p
                                v-if="relatedPost.excerpt"
                                class="line-clamp-2 text-sm text-gray-600"
                            >
                                {{ relatedPost.excerpt }}
                            </p>
                        </div>
                    </Link>
                </div>
            </div>

            <!-- Back to Blog Link -->
            <div class="mx-auto w-full max-w-4xl">
                <Link
                    href="/blog"
                    class="inline-flex items-center gap-2 text-sm font-medium text-blue-600 hover:underline"
                >
                    ← Back to Blog
                </Link>
            </div>
        </div>
        </main>
    </div>
</template>
