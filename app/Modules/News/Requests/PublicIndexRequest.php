<?php

namespace App\Modules\News\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PublicIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'category' => ['nullable', 'string', 'max:255'],
            'q' => ['nullable', 'string', 'max:255'],
            'featured' => ['nullable', 'boolean'],
            'sort' => ['nullable', 'string', 'in:published_at,-published_at,created_at,-created_at,title,-title'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }
}
