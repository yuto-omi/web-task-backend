<?php

namespace App\Modules\User\ValueObjects;

use InvalidArgumentException;

class EmailAddress
{
    private string $value;

    public function __construct(string $value)
    {
        $normalized = strtolower(trim($value));
        if (! filter_var($normalized, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Invalid email address.');
        }

        $this->value = $normalized;
    }

    public function value(): string
    {
        return $this->value;
    }
}
