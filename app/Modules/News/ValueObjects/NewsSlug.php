<?php

namespace App\Modules\News\ValueObjects;

use Illuminate\Support\Str;
use InvalidArgumentException;

class NewsSlug
{
    private string $value;

    public function __construct(string $value)
    {
        $normalized = trim($value);
        if ($normalized === '') {
            throw new InvalidArgumentException('Slug cannot be empty.');
        }

        $this->value = $normalized;
    }

    public static function fromTitle(string $title, ?string $slug = null): self
    {
        $value = $slug ?: Str::slug($title);

        return new self($value);
    }

    public function value(): string
    {
        return $this->value;
    }
}
