<?php

namespace Framework\Support\Validator\Rules;

class EmailRule extends \Framework\Support\Validator\Rule
{
    public function __invoke(?string $value, ...$args): bool
    {
        return strripos($value ?? '', '@') !== false;
    }

    public function errorMessage(): string
    {
        return "Is not email";
    }
}