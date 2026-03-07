<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'postid' => ['required', 'integer'],
            'comment_content' => ['required', 'string'],
            'userid' => ['nullable', 'integer'],
            'comment_parent' => ['nullable', 'integer'],
            'comment_approved' => ['nullable', 'integer'],
        ];
    }
}
