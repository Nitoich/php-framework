<?php

namespace Framework\DB\ORM\Attributes;
#[\Attribute]
class UseTable
{
    public function __construct(
        protected string $table_name
    ) {}
}