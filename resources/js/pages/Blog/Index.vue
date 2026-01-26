<script setup lang="ts">
import WelcomeHeader from '@/components/WelcomeHeader.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

interface Category {
    id: number;
    name: string;
    slug: string;
    description?: string;
    posts_count?: number;
}

interface Tag {
    id: number;
    name: string;
    slug: string;
    posts_count?: number;
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
    category: Category;
    user: User;
    tags: Tag[];
}

interface PaginatedPosts {
    data: Post[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
    links: Array<{
        url: string | null;
        label: string;
        active: boolean;
    }>;
}

const props = defineProps<{
    posts: PaginatedPosts;
    categories: Category[];
    popularTags: Tag[];
    currentCategory?: Category;
    currentTag?: Tag;
    searchQuery?: string;
}>();

const search = ref(props.searchQuery || '');

const handleSearch = () => {
    router.get(
        '/blog',
        { search: search.value || undefined },
        { preserveState: true, preserveScroll: true },
    );
};

const clearSearch = () => {
    search.value = '';
    router.get('/blog', {}, { preserveState: true, preserveScroll: true });
};

const pageTitle = computed(() => {
    if (props.currentCategory) {
        return `${props.currentCategory.name} - Blog`;
    }
    if (props.currentTag) {
        return `${props.currentTag.name} - Blog`;
    }
    return 'Blog';
});

const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
    });
};
</script>

<template>
    <Head :title="pageTitle" />

    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50">
        <WelcomeHeader :can-register="true" />

        <main class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
            <div class="flex flex-col gap-8">
                <!-- Header -->
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">
                        {{ pageTitle }}
                    </h1>
                    <p
                        v-if="currentCategory?.description"
                        class="mt-1 text-gray-600"
                    >
                        {{ currentCategory.description }}
                    </p>
                </div>

                <!-- Search -->
                <div>
                    <form @submit.prevent="handleSearch" class="relative">
                        <input
                            v-model="search"
                            type="text"
                            placeholder="Search posts…"
                            class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 pr-11 pl-11 text-gray-900 placeholder:text-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none"
                            @input="handleSearch"
                        />
                        <svg
                            class="absolute top-1/2 left-4 h-5 w-5 -translate-y-1/2 text-gray-400"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                            />
                        </svg>
                        <button
                            v-if="search"
                            type="button"
                            @click="clearSearch"
                            class="absolute top-1/2 right-4 -translate-y-1/2 text-gray-400 hover:text-gray-600"
                        >
                            ✕
                        </button>
                    </form>
                </div>

                <div class="grid gap-8 lg:grid-cols-12">
                    <!-- POSTS -->
                    <div class="lg:col-span-8">
                        <div
                            v-if="posts.data.length"
                            class="grid gap-6 md:grid-cols-2"
                        >
                            <article
                                v-for="post in posts.data"
                                :key="post.id"
                                class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm transition hover:shadow-md"
                            >
                                <Link
                                    :href="`/blog/${post.slug}`"
                                    class="block aspect-video bg-gray-100"
                                >
                                    <img
                                        v-if="post.featured_image"
                                        :src="post.featured_image"
                                        :alt="post.title"
                                        class="h-full w-full object-cover"
                                    />
                                </Link>

                                <div class="flex flex-col gap-3 p-5">
                                    <div class="flex items-center gap-2 text-sm">
                                        <Link
                                            :href="`/blog/category/${post.category.slug}`"
                                            class="rounded-full bg-blue-100 px-3 py-1 font-medium text-blue-700"
                                        >
                                            {{ post.category.name }}
                                        </Link>
                                        <span class="text-gray-500">
                                            {{ formatDate(post.published_at) }}
                                        </span>
                                    </div>

                                    <!-- ✅ КЛІКАБЕЛЬНИЙ ЗАГОЛОВОК -->
                                    <Link
                                        :href="`/blog/${post.slug}`"
                                        class="group"
                                    >
                                        <h2
                                            class="text-xl font-semibold text-gray-900 transition group-hover:text-blue-600"
                                        >
                                            {{ post.title }}
                                        </h2>
                                    </Link>

                                    <p
                                        v-if="post.excerpt"
                                        class="line-clamp-3 text-gray-600"
                                    >
                                        {{ post.excerpt }}
                                    </p>

                                    <div
                                        class="flex items-center justify-between pt-2"
                                    >
                                        <span class="text-sm text-gray-500">
                                            {{ post.user.name }}
                                        </span>
                                        <Link
                                            :href="`/blog/${post.slug}`"
                                            class="text-sm font-medium text-blue-600 hover:underline"
                                        >
                                            Read →
                                        </Link>
                                    </div>
                                </div>
                            </article>
                        </div>

                        <!-- Pagination -->
                        <div
                            v-if="posts.last_page > 1"
                            class="mt-10 flex flex-wrap justify-center gap-2"
                        >
                            <button
                                v-for="(link, i) in posts.links"
                                :key="i"
                                :disabled="!link.url"
                                @click="
                                    link.url &&
                                    router.get(link.url, {}, {
                                        preserveState: true,
                                        preserveScroll: true,
                                    })
                                "
                                v-html="link.label"
                                class="min-w-[42px] rounded-lg border px-4 py-2 text-sm font-medium transition disabled:opacity-50"
                                :class="
                                    link.active
                                        ? 'border-gray-300 bg-white text-gray-900 shadow'
                                        : 'border-gray-200 bg-gray-100 text-gray-700 hover:bg-gray-200'
                                "
                            />
                        </div>
                    </div>

                    <!-- SIDEBAR -->
                    <aside class="lg:col-span-4">
                        <div class="sticky top-6 flex flex-col gap-6">
                            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                                <h3 class="mb-4 text-lg font-semibold text-gray-900">
                                    Categories
                                </h3>
                                <div class="flex flex-col gap-2">
                                    <Link
                                        v-for="c in categories"
                                        :key="c.id"
                                        :href="`/blog/category/${c.slug}`"
                                        class="flex justify-between rounded-lg px-3 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                    >
                                        <span>{{ c.name }}</span>
                                        <span class="text-gray-400">
                                            {{ c.posts_count }}
                                        </span>
                                    </Link>
                                </div>
                            </div>

                            <div
                                v-if="popularTags.length"
                                class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm"
                            >
                                <h3 class="mb-4 text-lg font-semibold text-gray-900">
                                    Popular Tags
                                </h3>
                                <div class="flex flex-wrap gap-2">
                                    <Link
                                        v-for="t in popularTags"
                                        :key="t.id"
                                        :href="`/blog/tag/${t.slug}`"
                                        class="rounded-full border border-gray-200 px-3 py-1 text-sm text-gray-700 hover:bg-gray-100"
                                    >
                                        {{ t.name }}
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </aside>
                </div>
            </div>
        </main>
    </div>
</template>
