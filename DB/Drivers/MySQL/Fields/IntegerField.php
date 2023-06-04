<?php

namespace Framework\DB\Drivers\MySQL\Fields;

use Framework\DB\Drivers\MySQL\Interfaces\ISQLField;
use Framework\DB\Migrations\BaseField;

#[\Attribute]
class IntegerField extends BaseField implements ISQLField
{
    public function __construct(
        protected string $name = '',
        protected int $size = 20
    ) {
        parent::__construct($name);
    }

    public function getSQL(): string
    {
        return "INT({$this->size})";
    }

    public function getSQLAdditionTable(): string
    {
        return  "";
    }
}