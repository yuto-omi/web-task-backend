<?php

namespace App\Modules\NewsCategory\ValueObjects;

use Illuminate\Support\Str;
use InvalidArgumentException;

class CategorySlug
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

    public static function fromName(string $name, ?string $slug = null): self
    {
        $value = $slug ?: Str::slug($name);

        return new self($value);
    }

    public function value(): string
    {
        return $this->value;
    }
}
