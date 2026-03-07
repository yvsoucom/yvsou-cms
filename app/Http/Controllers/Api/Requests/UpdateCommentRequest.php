<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'comment_content' => ['sometimes', 'string'],
            'comment_parent' => ['sometimes', 'integer'],
            'comment_approved' => ['sometimes', 'integer'],
            'post_version' => ['sometimes', 'integer'],
        ];
    }
}
