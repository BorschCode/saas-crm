<template>
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50">
        <header class="bg-white shadow-sm border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Filament CRM & Blog Platform</h1>
                        <p class="mt-1 text-gray-600">Comprehensive business management with powerful blogging capabilities</p>
                    </div>
                    <div class="flex gap-4">
                        <a
                            v-if="canRegister"
                            href="/register"
                            class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition"
                        >
                            Register
                        </a>
                        <a
                            href="/login"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
                        >
                            Login
                        </a>
                    </div>
                </div>
            </div>
        </header>

        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-extrabold text-gray-900 mb-4">
                    Everything You Need to Run Your Business
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Manage your CRM, create blog posts from files, track time, handle projects, and more - all in one powerful platform.
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
                <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-shadow p-6 border border-gray-100">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Blog Platform</h3>
                    <p class="text-gray-600 mb-4">Browse articles, search posts, filter by categories and tags.</p>
                    <div class="space-y-2">
                        <a href="/blog" class="block text-blue-600 hover:text-blue-800 text-sm font-medium">
                            → View All Posts
                        </a>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-shadow p-6 border border-gray-100">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">PDF/CSV to Blog</h3>
                    <p class="text-gray-600 mb-4">Upload PDF or CSV files and automatically convert them to blog posts with AI-powered text extraction.</p>
                    <div class="space-y-2">
                        <a href="/posts/upload" class="block text-purple-600 hover:text-purple-800 text-sm font-medium">
                            → Upload Files
                        </a>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-shadow p-6 border border-gray-100">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Admin Panel</h3>
                    <p class="text-gray-600 mb-4">Powerful Filament admin interface for managing all aspects of your business.</p>
                    <div class="space-y-2">
                        <a href="/admin" class="block text-green-600 hover:text-green-800 text-sm font-medium">
                            → Open Admin Panel
                        </a>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-shadow p-6 border border-gray-100">
                    <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Dashboard</h3>
                    <p class="text-gray-600 mb-4">Access your personalized dashboard with insights and quick actions.</p>
                    <div class="space-y-2">
                        <a href="/dashboard" class="block text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                            → Go to Dashboard
                        </a>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-shadow p-6 border border-gray-100">
                    <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">User Settings</h3>
                    <p class="text-gray-600 mb-4">Manage your profile, security settings, and preferences.</p>
                    <div class="space-y-2">
                        <a href="/settings" class="block text-gray-600 hover:text-gray-800 text-sm font-medium">
                            → Manage Settings
                        </a>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-shadow p-6 border border-gray-100">
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Authentication</h3>
                    <p class="text-gray-600 mb-4">Secure login, registration, and password management powered by Laravel Fortify.</p>
                    <div class="space-y-2">
                        <a href="/login" class="block text-red-600 hover:text-red-800 text-sm font-medium">
                            → Login
                        </a>
                        <a v-if="canRegister" href="/register" class="block text-red-600 hover:text-red-800 text-sm font-medium">
                            → Register
                        </a>
                        <a href="/forgot-password" class="block text-red-600 hover:text-red-800 text-sm font-medium">
                            → Reset Password
                        </a>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-8 border border-gray-200">
                <h3 class="text-2xl font-bold text-gray-900 mb-6">Platform Capabilities</h3>
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-3">CRM Features</h4>
                        <ul class="space-y-2 text-gray-600">
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                Client & Contact Management
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                Deal Pipeline & Sales Tracking
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                Project & Task Management
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                Time Entry Tracking
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                Team Collaboration
                            </li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-3">Blog & Content</h4>
                        <ul class="space-y-2 text-gray-600">
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-blue-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                PDF/CSV to Blog Post Conversion
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-blue-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                Full-Text Search (Laravel Scout)
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-blue-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                Categories & Tags
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-blue-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                Export to PDF
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-blue-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                Async Queue Processing
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="mt-12 text-center">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Built With Modern Technology</h3>
                <div class="flex flex-wrap justify-center gap-4 text-sm text-gray-600">
                    <span class="px-4 py-2 bg-white rounded-full shadow-sm border border-gray-200">Laravel 12</span>
                    <span class="px-4 py-2 bg-white rounded-full shadow-sm border border-gray-200">Filament 4</span>
                    <span class="px-4 py-2 bg-white rounded-full shadow-sm border border-gray-200">Inertia.js v2</span>
                    <span class="px-4 py-2 bg-white rounded-full shadow-sm border border-gray-200">Vue 3</span>
                    <span class="px-4 py-2 bg-white rounded-full shadow-sm border border-gray-200">MongoDB</span>
                    <span class="px-4 py-2 bg-white rounded-full shadow-sm border border-gray-200">Laravel Scout</span>
                    <span class="px-4 py-2 bg-white rounded-full shadow-sm border border-gray-200">Redis Queues</span>
                    <span class="px-4 py-2 bg-white rounded-full shadow-sm border border-gray-200">Tailwind CSS v4</span>
                </div>
            </div>
        </main>

        <footer class="bg-white border-t border-gray-200 mt-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <p class="text-center text-gray-600">
                    Filament CRM & Blog Platform - Built with Laravel & Filament
                </p>
            </div>
        </footer>
    </div>
</template>

<script setup lang="ts">
defineProps({
    canRegister: Boolean,
});
</script>
