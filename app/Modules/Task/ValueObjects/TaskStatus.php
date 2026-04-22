<?php

namespace App\Modules\Task\ValueObjects;

use InvalidArgumentException;

class TaskStatus
{
    private const ALLOWED = ['pending', 'in_progress', 'in_review', 'done'];

    private string $value;

    public function __construct(string $value)
    {
        if (! in_array($value, self::ALLOWED, true)) {
            throw new InvalidArgumentException("Invalid task status: {$value}");
        }

        $this->value = $value;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function isDone(): bool
    {
        return $this->value === 'done';
    }
}
