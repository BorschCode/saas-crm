<script setup lang="ts">
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import { route } from 'ziggy-js';

defineProps<{
    canRegister: boolean;
}>();

const mobileMenuOpen = ref(false);
</script>

<template>
    <header class="border-b border-gray-200 bg-white shadow-sm">
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <!-- Logo / Title -->
                <div class="flex-1">
                    <Link
                        :href="route('home')"
                        class="text-2xl font-bold text-gray-900 transition hover:text-blue-700 md:text-3xl"
                    >
                        Filament CRM & Blog Platform
                    </Link>
                    <p
                        class="mt-1 hidden text-sm text-gray-600 sm:block md:text-base"
                    >
                        Comprehensive business management with powerful blogging
                        capabilities
                    </p>
                </div>

                <!-- Right side -->
                <div class="flex items-center gap-4 md:gap-6">
                    <!-- Desktop navigation -->
                    <nav class="hidden items-center gap-6 lg:flex">
                        <Link
                            :href="route('blog.index')"
                            class="font-medium text-gray-700 transition hover:text-blue-600"
                        >
                            Blog
                        </Link>

                        <Link
                            :href="route('dashboard')"
                            class="font-medium text-gray-700 transition hover:text-blue-600"
                        >
                            Dashboard
                        </Link>
                    </nav>

                    <!-- Auth -->
                    <div class="flex gap-2 md:gap-4">
                        <Link
                            v-if="canRegister"
                            :href="route('register')"
                            class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-700 transition hover:bg-gray-50 md:px-4 md:text-base"
                        >
                            Register
                        </Link>

                        <Link
                            :href="route('login')"
                            class="rounded-lg bg-blue-600 px-3 py-2 text-sm text-white transition hover:bg-blue-700 md:px-4 md:text-base"
                        >
                            Login
                        </Link>
                    </div>

                    <!-- Mobile menu button -->
                    <button
                        class="p-2 text-gray-600 hover:text-gray-900 lg:hidden"
                        @click="mobileMenuOpen = !mobileMenuOpen"
                        aria-label="Toggle menu"
                    >
                        <svg
                            class="h-6 w-6"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                v-if="!mobileMenuOpen"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"
                            />
                            <path
                                v-else
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"
                            />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile nav -->
            <nav
                v-if="mobileMenuOpen"
                class="mt-4 border-t border-gray-200 pt-4 lg:hidden"
            >
                <div class="flex flex-col gap-3">
                    <Link
                        :href="route('blog.index')"
                        class="rounded-lg px-4 py-2 text-gray-700 transition hover:bg-gray-50 hover:text-blue-600"
                    >
                        Blog
                    </Link>

                    <Link
                        :href="route('dashboard')"
                        class="rounded-lg px-4 py-2 text-gray-700 transition hover:bg-gray-50 hover:text-blue-600"
                    >
                        Dashboard
                    </Link>
                </div>
            </nav>
        </div>
    </header>
</template>
