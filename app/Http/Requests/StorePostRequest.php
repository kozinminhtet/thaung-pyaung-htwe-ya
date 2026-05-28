<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    /**
     * Authorization
     */
    public function authorize(): bool
    {
        return auth()->check()
            && auth()->user()->role === 'admin';
    }

    /**
     * Validation Rules
     */
    public function rules(): array
    {
        return [

            'content' => [
                'nullable',
                'string',
                'min:3',
                'max:10000',
                'required_without_all:image,video_url',
            ],

            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
                'required_without_all:content,video_url',
            ],

            'video_url' => [
                'nullable',
                'url',
                'required_without_all:content,image',
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

    /**
     * Custom Messages
     */
    public function messages(): array
    {
        return [

            'content.required_without_all' =>
            'Post content, image, or video is required.',

            'image.required_without_all' =>
            'Please upload an image or add content/video.',

            'video_url.required_without_all' =>
            'Please provide a video URL or add content/image.',

            'image.image' =>
            'Uploaded file must be an image.',

            'video_url.url' =>
            'Video URL format is invalid.',

        ];
    }
}