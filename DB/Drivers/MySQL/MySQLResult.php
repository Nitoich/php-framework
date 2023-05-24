<?php

namespace Framework\DB\Drivers\MySQL;

use Framework\DB\Interfaces\IQuery;

class MySQLResult implements \Framework\DB\Interfaces\IDBResult
{
    public function __construct(
        protected IQuery $query,
        protected \mysqli_result $mysqli_result,
    ){}

    public function getData(): array
    {
        return ($this->mysqli_result->num_rows > 0) ? $this->mysqli_result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function isSusses(): bool
    {
        // TODO: Implement isSusses() method.
    }

    public function getErrors(): array
    {
        // TODO: Implement getErrors() method.
    }

    public function errorHandler(): void
    {
        // TODO: Implement errorHandler() method.
    }

    public function getQuery(): IQuery
    {
        return $this->query;
    }

    public function getLastInsertId(): ?int
    {
        // TODO: Implement getLastInsertId() method.
    }
}