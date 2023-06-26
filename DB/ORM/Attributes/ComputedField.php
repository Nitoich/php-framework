<?php

namespace Framework\DB\ORM\Attributes;

#[\Attribute]
class ComputedField
{
    public function __construct(protected bool $needed = false)
    {
    }

    /**
     * @param bool $needed
     */
    public function getNeeded(): bool
    {
        return $this->needed;
    }
}