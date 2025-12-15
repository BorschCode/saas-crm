<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FileUploadRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'files' => ['required', 'array', 'max:10'],
            'files.*' => [
                'required',
                'file',
                'mimes:pdf,csv',
                'max:10240', // 10MB
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'files.*.mimes' => 'Only PDF and CSV files are allowed.',
            'files.*.max' => 'File size must not exceed 10MB.',
            'files.required' => 'Please select at least one file to upload.',
            'files.max' => 'You can upload a maximum of 10 files at once.',
        ];
    }
}
