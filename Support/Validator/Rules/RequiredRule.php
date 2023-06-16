<?php

namespace Framework\Support\Validator\Rules;

class RequiredRule extends \Framework\Support\Validator\Rule
{
    public function __invoke(?string $value, ...$args): bool
    {
        return !is_null($value);
    }

    public function errorMessage(): string
    {
        return 'Required field!';
    }
}