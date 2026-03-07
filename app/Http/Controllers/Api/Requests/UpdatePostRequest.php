<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'post_title' => ['sometimes', 'string', 'max:255'],
            'post_content' => ['sometimes', 'string'],
            'post_author' => ['sometimes', 'integer'],
            'revised_author' => ['sometimes', 'integer'],
            'post_status' => ['sometimes', 'integer'],
            'rights' => ['sometimes', 'string'],
            'comment_rights' => ['sometimes', 'string'],
            'canzip' => ['sometimes', 'boolean'],
        ];
    }
}
