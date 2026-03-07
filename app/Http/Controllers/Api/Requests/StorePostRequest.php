<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'post_title' => ['required', 'string', 'max:255'],
            'post_content' => ['required', 'string'],
            'post_author' => ['nullable', 'integer'],
            'revised_author' => ['nullable', 'integer'],
            'post_status' => ['nullable', 'integer'],
            'rights' => ['nullable', 'string'],
            'comment_rights' => ['nullable', 'string'],
            'canzip' => ['nullable', 'boolean'],
        ];
    }
}
