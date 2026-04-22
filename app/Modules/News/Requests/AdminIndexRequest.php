<?php

namespace App\Modules\News\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminIndexRequest extends FormRequest
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
            'search' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', 'string', 'in:draft,published,archived'],
            'is_featured' => ['nullable', 'boolean'],
            'category_id' => ['nullable', 'integer', 'exists:news_categories,id'],
            'sort' => ['nullable', 'string', 'in:created_at,-created_at,published_at,-published_at,title,-title'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }
}
