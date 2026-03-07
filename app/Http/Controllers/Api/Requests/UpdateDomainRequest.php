<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDomainRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'userid' => ['sometimes', 'integer'],
            'm_type' => ['sometimes', 'string', 'max:20'],
            'owner_rights' => ['sometimes', 'string'],
            'own_group_rights' => ['sometimes', 'string'],
            'grant_group_rights' => ['sometimes', 'string'],
            'grant_user_rights' => ['sometimes', 'string'],
            'any_user_rights' => ['sometimes', 'string'],
            'bHide' => ['sometimes', 'boolean'],
            'bTrash' => ['sometimes', 'boolean'],
        ];
    }
}
