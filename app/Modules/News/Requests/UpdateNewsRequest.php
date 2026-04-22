<?php

namespace App\Modules\News\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateNewsRequest extends FormRequest
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
        $newsId = (int) $this->route('id');

        return [
            'category_id' => ['sometimes', 'required', 'integer', 'exists:news_categories,id'],
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('news', 'slug')->ignore($newsId),
            ],
            'content' => ['sometimes', 'required', 'string'],
            'thumbnail_url' => ['nullable', 'string', 'max:2048'],
            'published_at' => ['nullable', 'date', 'required_if:status,published'],
            'status' => ['sometimes', 'required', 'string', 'in:draft,published,archived'],
            'is_featured' => ['nullable', 'boolean'],
        ];
    }
}
