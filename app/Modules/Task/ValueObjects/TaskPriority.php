<?php

namespace App\Modules\Task\ValueObjects;

use InvalidArgumentException;

class TaskPriority
{
    private const ALLOWED = ['low', 'medium', 'high'];

    private string $value;

    public function __construct(string $value)
    {
        if (! in_array($value, self::ALLOWED, true)) {
            throw new InvalidArgumentException("Invalid task priority: {$value}");
        }

        $this->value = $value;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function isHigh(): bool
    {
        return $this->value === 'high';
    }
}
