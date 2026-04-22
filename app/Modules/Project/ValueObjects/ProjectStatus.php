<?php

namespace App\Modules\Project\ValueObjects;

use InvalidArgumentException;

class ProjectStatus
{
    private const ALLOWED = ['not_started', 'in_progress', 'completed'];

    private string $value;

    public function __construct(string $value)
    {
        if (! in_array($value, self::ALLOWED, true)) {
            throw new InvalidArgumentException("Invalid project status: {$value}");
        }

        $this->value = $value;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function isCompleted(): bool
    {
        return $this->value === 'completed';
    }

    public function isInProgress(): bool
    {
        return $this->value === 'in_progress';
    }
}
