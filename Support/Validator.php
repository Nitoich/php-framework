<?php

namespace Framework\Support;

use Framework\Config;
use Framework\Support\Validator\Rule;

class Validator
{
    protected bool $isFail = false;
    protected array $errors = [];

    public static function make(array $data, array $rules): static
    {
        return new static($data, $rules);
    }

    public function __construct(array $data, protected array $rules)
    {
        $this->initRules();
        foreach ($this->rules as $field => $rules)
        {
            $rules = explode('|', $rules);
            foreach ($rules as $rule)
            {
                $rule = new (Config::get("validator.rules.$rule"))();
                /** @var Rule $rule */
                if(!$rule($data[$field] ?? null))
                {
                    $this->isFail = true;
                    $this->errors[$field][] = $rule->errorMessage();
                }
            }
        }
    }

    public function errors(): array
    {
        return $this->errors;
    }

    public function isFail(): bool
    {
        return $this->isFail;
    }

    private function initRules(): void
    {
        Config::registerConfigFile(__DIR__ . '/Validator/comparisonRules.php');
    }
}