<?php

namespace Framework\DB\Drivers\MySQL\Interfaces;

interface ISQLField
{
    public function getSQL(): string;
    public function getSQLAdditionTable(): string;
}