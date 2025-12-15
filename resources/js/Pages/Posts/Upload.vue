<template>
    <div class="min-h-screen bg-gray-100 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto">
            <div class="bg-white shadow-md rounded-lg p-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-6">Upload PDF or CSV Files</h1>

                <p class="text-gray-600 mb-6">
                    Upload PDF or CSV files to automatically create blog posts. Each file will create a new post with extracted content.
                </p>

                <form @submit.prevent="submitForm" enctype="multipart/form-data">
                    <div class="mb-6">
                        <label for="files" class="block text-sm font-medium text-gray-700 mb-2">
                            Select Files (PDF or CSV)
                        </label>

                        <input
                            type="file"
                            id="files"
                            ref="fileInput"
                            @change="handleFileChange"
                            multiple
                            accept=".pdf,.csv"
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                        />

                        <div v-if="errors.files" class="mt-2 text-sm text-red-600">
                            {{ errors.files }}
                        </div>

                        <div v-if="errors['files.0']" class="mt-2 text-sm text-red-600">
                            {{ errors['files.0'] }}
                        </div>
                    </div>

                    <div v-if="selectedFiles.length > 0" class="mb-6">
                        <h3 class="text-sm font-medium text-gray-700 mb-2">Selected Files ({{ selectedFiles.length }})</h3>
                        <ul class="space-y-2">
                            <li
                                v-for="(file, index) in selectedFiles"
                                :key="index"
                                class="flex items-center justify-between p-3 bg-gray-50 rounded-md"
                            >
                                <span class="text-sm text-gray-700">{{ file.name }}</span>
                                <span class="text-xs text-gray-500">{{ formatFileSize(file.size) }}</span>
                            </li>
                        </ul>
                    </div>

                    <div class="flex items-center justify-between">
                        <button
                            type="submit"
                            :disabled="processing || selectedFiles.length === 0"
                            class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            <span v-if="!processing">Upload Files</span>
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

                <div class="mt-8 p-4 bg-blue-50 rounded-md">
                    <h3 class="text-sm font-medium text-blue-800 mb-2">How it works:</h3>
                    <ul class="text-sm text-blue-700 space-y-1 list-disc list-inside">
                        <li>Each uploaded file creates a new blog post</li>
                        <li>Text is extracted automatically from PDFs and CSVs</li>
                        <li>Posts are created as drafts for you to review and edit</li>
                        <li>Maximum file size: 10MB per file</li>
                        <li>You can upload up to 10 files at once</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';

const fileInput = ref(null);
const selectedFiles = ref([]);
const errors = ref({});

const form = useForm({
    files: [],
});

const handleFileChange = (event) => {
    const files = Array.from(event.target.files);
    selectedFiles.value = files;
    form.files = files;
    errors.value = {};
};

const formatFileSize = (bytes) => {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
};

const submitForm = () => {
    form.post('/posts/upload', {
        onSuccess: () => {
            selectedFiles.value = [];
            if (fileInput.value) {
                fileInput.value.value = '';
            }
        },
        onError: (serverErrors) => {
            errors.value = serverErrors;
        },
    });
};

const processing = ref(false);
</script>
