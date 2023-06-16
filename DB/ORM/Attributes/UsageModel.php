<?php

namespace Framework\DB\ORM\Attributes;

#[\Attribute]
class UsageModel
{
    public function __construct(
        protected string $model
    )
    {}
}