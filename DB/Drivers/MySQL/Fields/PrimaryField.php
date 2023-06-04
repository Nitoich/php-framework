<?php

namespace Framework\DB\Drivers\MySQL\Fields;

use Framework\DB\Drivers\MySQL\Interfaces\ISQLField;

#[\Attribute]
class PrimaryField extends \Framework\DB\Migrations\BaseField implements ISQLField
{
    public function getSQL(): string
    {
        return "";
    }

    public function getSQLAdditionTable(): string
    {
        return  "PRIMARY KEY($this->name)";
    }
}