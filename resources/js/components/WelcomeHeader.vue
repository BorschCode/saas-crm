<template>
    <header class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex justify-between items-center">
                <div class="flex-1">
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Filament CRM & Blog Platform</h1>
                    <p class="mt-1 text-sm md:text-base text-gray-600 hidden sm:block">Comprehensive business management with powerful blogging capabilities</p>
                </div>

                <div class="flex items-center gap-4 md:gap-6">
                    <!-- Navigation Links - Desktop -->
                    <nav class="hidden lg:flex items-center gap-6">
                        <a
                            href="/blog"
                            class="text-gray-700 hover:text-blue-600 font-medium transition"
                        >
                            Blog
                        </a>
                        <a
                            href="/posts/upload"
                            class="text-gray-700 hover:text-blue-600 font-medium transition"
                        >
                            Upload Posts
                        </a>
                        <a
                            href="/dashboard"
                            class="text-gray-700 hover:text-blue-600 font-medium transition"
                        >
                            Dashboard
                        </a>
                    </nav>

                    <!-- Auth Buttons -->
                    <div class="flex gap-2 md:gap-4">
                        <a
                            v-if="canRegister"
                            href="/register"
                            class="px-3 md:px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm md:text-base text-gray-700 hover:bg-gray-50 transition"
                        >
                            Register
                        </a>
                        <a
                            href="/login"
                            class="px-3 md:px-4 py-2 bg-blue-600 text-white rounded-lg text-sm md:text-base hover:bg-blue-700 transition"
                        >
                            Login
                        </a>
                    </div>

                    <!-- Mobile Menu Button -->
                    <button
                        @click="mobileMenuOpen = !mobileMenuOpen"
                        class="lg:hidden p-2 text-gray-600 hover:text-gray-900"
                        aria-label="Toggle menu"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path v-if="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile Navigation Menu -->
            <nav v-if="mobileMenuOpen" class="lg:hidden mt-4 pt-4 border-t border-gray-200">
                <div class="flex flex-col gap-3">
                    <a
                        href="/blog"
                        class="px-4 py-2 text-gray-700 hover:bg-gray-50 hover:text-blue-600 rounded-lg transition"
                    >
                        Blog
                    </a>
                    <a
                        href="/posts/upload"
                        class="px-4 py-2 text-gray-700 hover:bg-gray-50 hover:text-blue-600 rounded-lg transition"
                    >
                        Upload Posts
                    </a>
                    <a
                        href="/dashboard"
                        class="px-4 py-2 text-gray-700 hover:bg-gray-50 hover:text-blue-600 rounded-lg transition"
                    >
                        Dashboard
                    </a>
                </div>
            </nav>
        </div>
    </header>
</template>

<script setup lang="ts">
import { ref } from 'vue';

defineProps<{
    canRegister: boolean;
}>();

const mobileMenuOpen = ref(false);
</script>
