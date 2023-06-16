<?php

namespace Framework\Support\Validator;

abstract class Rule
{
    public function __invoke(?string $value, ...$args): bool
    {
        return true;
    }

    public function errorMessage(): string
    {
        return 'validation error!';
    }
}