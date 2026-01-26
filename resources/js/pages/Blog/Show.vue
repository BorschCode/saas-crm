<script setup lang="ts">
import { exportPdf } from '@/actions/App/Http/Controllers/PostExportController';
import WelcomeHeader from '@/components/WelcomeHeader.vue';
import { Head, Link } from '@inertiajs/vue3';
import { Download } from 'lucide-vue-next';

interface Category {
    id: number;
    name: string;
    slug: string;
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

const formatDate = (dateString: string) =>
    new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
    });
</script>

<template>
    <Head :title="post.title" />

    <div
        class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50"
    >
        <WelcomeHeader :can-register="true" />

        <main class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
            <article
                class="mx-auto w-full max-w-4xl rounded-2xl bg-white/90 p-6 shadow-lg backdrop-blur md:p-10"
            >
                <!-- Meta -->
                <div
                    class="mb-8 flex flex-wrap items-center justify-between gap-4 text-sm"
                >
                    <div class="flex flex-wrap items-center gap-3">
                        <Link
                            v-if="post.category"
                            :href="`/blog/category/${post.category.slug}`"
                            class="rounded-full bg-blue-100 px-4 py-1.5 font-semibold text-blue-800 hover:bg-blue-200"
                        >
                            {{ post.category.name }}
                        </Link>

                        <span class="text-gray-600">
                            {{ formatDate(post.published_at) }}
                        </span>

                        <template v-if="post.user">
                            <span class="text-gray-400">•</span>
                            <span class="font-medium text-gray-700">
                                {{ post.user.name }}
                            </span>
                        </template>
                    </div>

                    <a
                        :href="exportPdf.url(post.id)"
                        download
                        class="inline-flex items-center gap-2 rounded-full bg-blue-100 px-4 py-1.5 font-semibold text-blue-800 hover:bg-blue-200"
                    >
                        <Download class="h-4 w-4" />
                        PDF
                    </a>
                </div>

                <!-- Title -->
                <h1
                    class="mb-4 text-4xl leading-tight font-extrabold tracking-tight text-gray-900 md:text-5xl"
                >
                    {{ post.title }}
                </h1>

                <!-- Excerpt -->
                <p
                    v-if="post.excerpt"
                    class="mb-8 text-xl leading-relaxed font-medium text-gray-700"
                >
                    {{ post.excerpt }}
                </p>

                <!-- Tags -->
                <div v-if="post.tags.length" class="mb-8 flex flex-wrap gap-2">
                    <Link
                        v-for="tag in post.tags"
                        :key="tag.id"
                        :href="`/blog/tag/${tag.slug}`"
                        class="rounded-full border border-gray-300 px-3 py-1 text-sm font-medium text-gray-700 hover:bg-gray-100"
                    >
                        #{{ tag.name }}
                    </Link>
                </div>

                <!-- Image -->
                <div
                    v-if="post.featured_image"
                    class="mb-10 overflow-hidden rounded-xl"
                >
                    <img
                        :src="post.featured_image"
                        :alt="post.title"
                        class="w-full object-cover"
                    />
                </div>

                <!-- Content -->
                <div
                    class="prose prose-lg prose-headings:text-gray-950 prose-a:text-blue-700 max-w-none text-gray-900"
                    v-html="post.content"
                />

                <!-- Author -->
                <div
                    v-if="post.user"
                    class="mt-14 flex items-center gap-4 rounded-xl border border-gray-200 bg-gray-50 p-6"
                >
                    <div
                        class="flex h-14 w-14 items-center justify-center rounded-full bg-blue-100 text-xl font-bold text-blue-800"
                    >
                        {{ post.user.name.charAt(0) }}
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Written by</p>
                        <p class="text-lg font-semibold text-gray-900">
                            {{ post.user.name }}
                        </p>
                    </div>
                </div>
            </article>

            <!-- Related -->
            <section v-if="relatedPosts.length" class="mx-auto mt-16 max-w-4xl">
                <h2 class="mb-6 text-2xl font-bold text-gray-900">
                    Related Posts
                </h2>

                <div class="grid gap-6 md:grid-cols-3">
                    <Link
                        v-for="p in relatedPosts"
                        :key="p.id"
                        :href="`/blog/${p.slug}`"
                        class="group overflow-hidden rounded-xl border bg-white transition hover:shadow-lg"
                    >
                        <div class="aspect-video bg-gray-100">
                            <img
                                v-if="p.featured_image"
                                :src="p.featured_image"
                                :alt="p.title"
                                class="h-full w-full object-cover transition-transform group-hover:scale-105"
                            />
                        </div>

                        <div class="p-4">
                            <p class="text-xs text-gray-500">
                                {{ formatDate(p.published_at) }}
                            </p>
                            <h3
                                class="mt-1 line-clamp-2 font-semibold text-gray-900 group-hover:text-blue-700"
                            >
                                {{ p.title }}
                            </h3>
                            <p
                                v-if="p.excerpt"
                                class="mt-1 line-clamp-2 text-sm text-gray-700"
                            >
                                {{ p.excerpt }}
                            </p>
                        </div>
                    </Link>
                </div>
            </section>

            <!-- Back -->
            <div class="mx-auto mt-12 max-w-4xl">
                <Link
                    href="/blog"
                    class="text-sm font-semibold text-blue-700 hover:underline"
                >
                    ← Back to Blog
                </Link>
            </div>
        </main>
    </div>
</template>
