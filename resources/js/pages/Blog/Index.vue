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
        { preserveState: true, preserveScroll: true }
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

        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div
            class="flex h-full flex-1 flex-col gap-6"
        >
            <!-- Header -->
            <div class="flex flex-col gap-2">
                <h1
                    class="text-3xl font-bold tracking-tight text-sidebar-foreground"
                >
                    {{ pageTitle }}
                </h1>
                <p
                    v-if="currentCategory?.description"
                    class="text-sidebar-foreground/70"
                >
                    {{ currentCategory.description }}
                </p>
            </div>

            <!-- Search Bar -->
            <div class="relative">
                <form @submit.prevent="handleSearch" class="relative">
                    <input
                        v-model="search"
                        type="text"
                        placeholder="Search posts..."
                        class="w-full rounded-xl border border-sidebar-border/70 bg-background px-4 py-3 pl-11 pr-11 text-sidebar-foreground placeholder:text-sidebar-foreground/50 focus:border-sidebar-accent-foreground focus:outline-none focus:ring-2 focus:ring-sidebar-accent-foreground/20 dark:border-sidebar-border"
                        @input="handleSearch"
                    />
                    <svg
                        class="absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-sidebar-foreground/50"
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
                        class="absolute right-4 top-1/2 -translate-y-1/2 text-sidebar-foreground/50 hover:text-sidebar-foreground"
                    >
                        <svg
                            class="h-5 w-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"
                            />
                        </svg>
                    </button>
                </form>
                <p
                    v-if="searchQuery"
                    class="mt-2 text-sm text-sidebar-foreground/70"
                >
                    Showing results for "{{ searchQuery }}"
                </p>
            </div>

            <div class="grid gap-6 lg:grid-cols-12">
                <!-- Main Content -->
                <div class="lg:col-span-8">
                    <!-- Posts Grid -->
                    <div
                        v-if="posts.data.length > 0"
                        class="grid gap-6 md:grid-cols-2"
                    >
                        <article
                            v-for="post in posts.data"
                            :key="post.id"
                            class="group flex flex-col overflow-hidden rounded-xl border border-sidebar-border/70 bg-background transition-all hover:shadow-lg dark:border-sidebar-border"
                        >
                            <Link
                                :href="`/blog/${post.slug}`"
                                class="relative aspect-video overflow-hidden bg-sidebar-accent"
                            >
                                <img
                                    v-if="post.featured_image"
                                    :src="post.featured_image"
                                    :alt="post.title"
                                    class="h-full w-full object-cover transition-transform group-hover:scale-105"
                                />
                                <div
                                    v-else
                                    class="flex h-full w-full items-center justify-center bg-gradient-to-br from-sidebar-accent to-sidebar-accent-foreground/10"
                                >
                                    <span
                                        class="text-4xl font-bold text-sidebar-accent-foreground/20"
                                    >
                                        {{ post.title.charAt(0) }}
                                    </span>
                                </div>
                            </Link>

                            <div class="flex flex-1 flex-col gap-3 p-5">
                                <div class="flex items-center gap-2 text-sm">
                                    <Link
                                        :href="`/blog/category/${post.category.slug}`"
                                        class="rounded-full bg-sidebar-accent px-3 py-1 font-medium text-sidebar-accent-foreground hover:bg-sidebar-accent/80"
                                    >
                                        {{ post.category.name }}
                                    </Link>
                                    <span
                                        class="text-sidebar-foreground/50"
                                    >
                                        {{ formatDate(post.published_at) }}
                                    </span>
                                </div>

                                <Link
                                    :href="`/blog/${post.slug}`"
                                    class="group/title"
                                >
                                    <h2
                                        class="text-xl font-bold text-sidebar-foreground group-hover/title:text-sidebar-accent-foreground"
                                    >
                                        {{ post.title }}
                                    </h2>
                                </Link>

                                <p
                                    v-if="post.excerpt"
                                    class="line-clamp-3 flex-1 text-sidebar-foreground/70"
                                >
                                    {{ post.excerpt }}
                                </p>

                                <div
                                    class="flex items-center justify-between pt-2"
                                >
                                    <div class="flex items-center gap-2">
                                        <div
                                            class="flex h-8 w-8 items-center justify-center rounded-full bg-sidebar-accent text-sm font-medium text-sidebar-accent-foreground"
                                        >
                                            {{ post.user.name.charAt(0) }}
                                        </div>
                                        <span
                                            class="text-sm text-sidebar-foreground/70"
                                        >
                                            {{ post.user.name }}
                                        </span>
                                    </div>

                                    <Link
                                        :href="`/blog/${post.slug}`"
                                        class="text-sm font-medium text-sidebar-accent-foreground hover:underline"
                                    >
                                        Read more →
                                    </Link>
                                </div>
                            </div>
                        </article>
                    </div>

                    <!-- Empty State -->
                    <div
                        v-else
                        class="flex flex-col items-center justify-center rounded-xl border border-sidebar-border/70 bg-background p-12 text-center dark:border-sidebar-border"
                    >
                        <h3
                            class="mb-2 text-lg font-semibold text-sidebar-foreground"
                        >
                            No posts found
                        </h3>
                        <p class="text-sidebar-foreground/70">
                            Check back later for new content!
                        </p>
                    </div>

                    <!-- Pagination -->
                    <div
                        v-if="posts.last_page > 1"
                        class="mt-6 flex justify-center gap-2"
                    >
                        <template
                            v-for="(link, index) in posts.links"
                            :key="index"
                        >
                            <a
                                v-if="link.url"
                                :href="link.url"
                                class="rounded-lg border border-sidebar-border/70 px-4 py-2 text-sm font-medium transition-colors hover:bg-sidebar-accent dark:border-sidebar-border"
                                :class="{
                                    'bg-sidebar-accent text-sidebar-accent-foreground':
                                        link.active,
                                    'text-sidebar-foreground': !link.active,
                                }"
                                v-html="link.label"
                            />
                            <span
                                v-else
                                class="rounded-lg border border-sidebar-border/70 px-4 py-2 text-sm font-medium text-sidebar-foreground/50 dark:border-sidebar-border"
                                v-html="link.label"
                            />
                        </template>
                    </div>
                </div>

                <!-- Sidebar -->
                <aside class="lg:col-span-4">
                    <div class="sticky top-6 flex flex-col gap-6">
                        <!-- Categories -->
                        <div
                            class="rounded-xl border border-sidebar-border/70 bg-background p-5 dark:border-sidebar-border"
                        >
                            <h3
                                class="mb-4 text-lg font-semibold text-sidebar-foreground"
                            >
                                Categories
                            </h3>
                            <div class="flex flex-col gap-2">
                                <Link
                                    v-for="category in categories"
                                    :key="category.id"
                                    :href="`/blog/category/${category.slug}`"
                                    class="flex items-center justify-between rounded-lg px-3 py-2 text-sm transition-colors hover:bg-sidebar-accent"
                                    :class="{
                                        'bg-sidebar-accent font-medium text-sidebar-accent-foreground':
                                            currentCategory?.id === category.id,
                                        'text-sidebar-foreground':
                                            currentCategory?.id !== category.id,
                                    }"
                                >
                                    <span>{{ category.name }}</span>
                                    <span
                                        v-if="category.posts_count"
                                        class="text-xs text-sidebar-foreground/50"
                                    >
                                        {{ category.posts_count }}
                                    </span>
                                </Link>
                            </div>
                        </div>

                        <!-- Popular Tags -->
                        <div
                            v-if="popularTags.length > 0"
                            class="rounded-xl border border-sidebar-border/70 bg-background p-5 dark:border-sidebar-border"
                        >
                            <h3
                                class="mb-4 text-lg font-semibold text-sidebar-foreground"
                            >
                                Popular Tags
                            </h3>
                            <div class="flex flex-wrap gap-2">
                                <Link
                                    v-for="tag in popularTags"
                                    :key="tag.id"
                                    :href="`/blog/tag/${tag.slug}`"
                                    class="rounded-full border border-sidebar-border/70 px-3 py-1 text-sm transition-colors hover:bg-sidebar-accent dark:border-sidebar-border"
                                    :class="{
                                        'bg-sidebar-accent font-medium text-sidebar-accent-foreground':
                                            currentTag?.id === tag.id,
                                        'text-sidebar-foreground':
                                            currentTag?.id !== tag.id,
                                    }"
                                >
                                    {{ tag.name }}
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
