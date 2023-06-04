<?php

namespace Framework\DB\Migrations;

#[\Attribute]
abstract class BaseField {
    public function __construct(
        protected string $name = ''
    ) {}
}