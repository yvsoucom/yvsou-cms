<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDomainRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'domainid' => ['required', 'string', 'max:255'],
            'userid' => ['required', 'integer'],
            'm_type' => ['nullable', 'string', 'max:20'],
            'owner_rights' => ['nullable', 'string'],
            'own_group_rights' => ['nullable', 'string'],
            'grant_group_rights' => ['nullable', 'string'],
            'grant_user_rights' => ['nullable', 'string'],
            'any_user_rights' => ['nullable', 'string'],
            'bHide' => ['nullable', 'boolean'],
            'bTrash' => ['nullable', 'boolean'],
        ];
    }
}
