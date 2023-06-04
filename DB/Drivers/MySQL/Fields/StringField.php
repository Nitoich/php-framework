<?php

namespace Framework\DB\Drivers\MySQL\Fields;

use Framework\DB\Drivers\MySQL\Interfaces\ISQLField;

#[\Attribute]
class StringField extends \Framework\DB\Migrations\BaseField implements ISQLField
{
    public function __construct(
        protected string $name = '',
        protected int $size = 20
    ) {
        parent::__construct($name);
    }

    public function getSQL(): string
    {
        return "VARCHAR({$this->size})";
    }

    public function getSQLAdditionTable(): string
    {
        return "";
    }
}