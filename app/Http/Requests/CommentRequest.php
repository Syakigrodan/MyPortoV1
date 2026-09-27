<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:80'],
            'message' => ['required', 'string', 'max:1000'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'name',
            'message' => 'comment',
            'avatar' => 'profile photo',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Your name is required.',
            'message.required' => 'Please write something before posting.',
            'message.max' => 'Your comment is too long (max 1000 characters).',
            'avatar.image' => 'The profile photo must be an image.',
            'avatar.mimes' => 'The profile photo must be a JPG, PNG, or WEBP file.',
            'avatar.max' => 'The profile photo may not be greater than 2 MB.',
        ];
    }
}
