<?php

namespace Framework\DB\Drivers\MySQL\Fields;

use Framework\DB\Drivers\MySQL\Interfaces\ISQLField;

#[\Attribute]
class AutoIncrementField extends \Framework\DB\Migrations\BaseField implements ISQLField {
    public function getSQL(): string
    {
        return "AUTO_INCREMENT";
    }

    public function getSQLAdditionTable(): string
    {
        return "";
    }
}