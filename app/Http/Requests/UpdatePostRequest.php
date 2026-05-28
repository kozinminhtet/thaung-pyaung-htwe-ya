<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check()
            && auth()->user()->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'content' => [
                'nullable',
                'string',
                'min:3',
                'max:10000',
            ],

            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],

            'video_url' => [
                'nullable',
                'url',
            ],

            'category_id' => [
                'nullable',
                'exists:categories,id',
            ],

            'status' => [
                'required',
                'in:draft,published,archived',
            ],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $post = $this->route('post');

            if (
                ! $this->filled('content')
                && ! $this->filled('video_url')
                && ! $this->hasFile('image')
                && ! optional($post)->image_url
            ) {
                $validator->errors()->add('content', 'Post content, image, or video is required.');
            }
        });
    }

    public function messages(): array
    {
        return [
            'content.required_without_all' => 'Post content, image, or video is required.',
            'video_url.required_without_all' => 'Please provide a video URL or add content/image.',
            'image.image' => 'Uploaded file must be an image.',
            'video_url.url' => 'Video URL format is invalid.',
        ];
    }
}
