<template>
    <Head title="Upload Files - Blog" />

    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50">
        <WelcomeHeader :can-register="true" />

        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="mx-auto max-w-3xl">
            <div class="rounded-lg bg-white p-8 shadow-md">
                <h1 class="mb-6 text-3xl font-bold text-gray-900">
                    Upload PDF or CSV Files
                </h1>

                <p class="mb-6 text-gray-600">
                    Upload PDF or CSV files to automatically create blog posts.
                    Each file will create a new post with extracted content.
                </p>

                <form
                    @submit.prevent="submitForm"
                    enctype="multipart/form-data"
                >
                    <div class="mb-6">
                        <label
                            for="files"
                            class="mb-2 block text-sm font-medium text-gray-700"
                        >
                            Select Files (PDF or CSV)
                        </label>

                        <input
                            type="file"
                            id="files"
                            ref="fileInput"
                            @change="handleFileChange"
                            multiple
                            accept=".pdf,.csv"
                            class="block w-full text-sm text-gray-500 file:mr-4 file:rounded-full file:border-0 file:bg-blue-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-blue-700 hover:file:bg-blue-100"
                        />

                        <!-- Display general file errors -->
                        <div
                            v-if="errors.files"
                            class="mt-2 text-sm text-red-600"
                        >
                            {{ errors.files }}
                        </div>

                        <!-- Display specific file errors (e.g., validation on the first file) -->
                        <div
                            v-if="errors['files.0']"
                            class="mt-2 text-sm text-red-600"
                        >
                            {{ errors['files.0'] }}
                        </div>
                    </div>

                    <div v-if="selectedFiles.length > 0" class="mb-6">
                        <h3 class="mb-2 text-sm font-medium text-gray-700">
                            Selected Files ({{ selectedFiles.length }})
                        </h3>
                        <ul class="space-y-2">
                            <li
                                v-for="(file, index) in selectedFiles"
                                :key="index"
                                class="flex items-center justify-between rounded-md bg-gray-50 p-3"
                            >
                                <span class="text-sm text-gray-700">{{
                                    file.name
                                }}</span>
                                <span class="text-xs text-gray-500">{{
                                    formatFileSize(file.size)
                                }}</span>
                            </li>
                        </ul>
                    </div>

                    <div class="flex items-center justify-between">
                        <button
                            type="submit"
                            :disabled="
                                form.processing || selectedFiles.length === 0
                            "
                            class="inline-flex items-center rounded-md border border-transparent bg-blue-600 px-6 py-3 text-base font-medium text-white shadow-sm hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:outline-none disabled:cursor-not-allowed disabled:opacity-50"
                        >
                            <span v-if="!form.processing">Upload Files</span>
                            <span v-else>Uploading...</span>
                        </button>

                        <a
                            href="/blog"
                            class="text-blue-600 hover:text-blue-800"
                        >
                            Back to Blog
                        </a>
                    </div>
                </form>

                <div class="mt-8 rounded-md bg-blue-50 p-4">
                    <h3 class="mb-2 text-sm font-medium text-blue-800">
                        How it works:
                    </h3>
                    <ul
                        class="list-inside list-disc space-y-1 text-sm text-blue-700"
                    >
                        <li>Each uploaded file creates a new blog post</li>
                        <li>
                            Text is extracted automatically from PDFs and CSVs
                        </li>
                        <li>
                            Posts are created as drafts for you to review and
                            edit
                        </li>
                        <li>Maximum file size: 10MB per file</li>
                        <li>You can upload up to 10 files at once</li>
                    </ul>
                </div>
            </div>
        </div>
        </main>
    </div>
</template>

<script setup lang="ts">
import WelcomeHeader from '@/components/WelcomeHeader.vue';
import { Head, useForm, type InertiaForm } from '@inertiajs/vue3';
import { ref, type Ref } from 'vue';

interface UploadForm {
    files: File[];
}

const fileInput: Ref<HTMLInputElement | null> = ref(null);
const selectedFiles: Ref<File[]> = ref([]);
const errors: Ref<Record<string, string>> = ref({});

const form: InertiaForm<UploadForm> = useForm<UploadForm>({
    files: [],
});

const handleFileChange = (event: Event) => {
    const target = event.target as HTMLInputElement;
    const files = Array.from(target.files || []);

    selectedFiles.value = files;
    form.files = files;
    errors.value = {};
};

const formatFileSize = (bytes: number): string => {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return Math.round((bytes / Math.pow(k, i)) * 100) / 100 + ' ' + sizes[i];
};

const submitForm = () => {
    // Assuming a global route helper (like Ziggy) is available,
    // which is standard in Laravel/Inertia applications.
    // We use type assertion to access the global helper in TypeScript.
    const uploadRoute = (window as any).route('posts.upload');

    form.post(uploadRoute, {
        onSuccess: () => {
            selectedFiles.value = [];
            if (fileInput.value) {
                fileInput.value.value = ''; // Clear file input element
            }
            errors.value = {}; // Clear previous errors
        },
        onError: (serverErrors) => {
            errors.value = serverErrors;
        },
    });
};
</script>
