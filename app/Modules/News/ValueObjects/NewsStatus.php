<?php

namespace App\Modules\News\ValueObjects;

use InvalidArgumentException;

class NewsStatus
{
    private const ALLOWED = ['draft', 'published', 'archived'];

    private string $value;

    public function __construct(string $value)
    {
        if (! in_array($value, self::ALLOWED, true)) {
            throw new InvalidArgumentException('Invalid news status.');
        }

        $this->value = $value;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function requiresPublishedAt(): bool
    {
        return $this->value === 'published';
    }
}
