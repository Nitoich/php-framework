<?php

namespace Framework\DB\Drivers\MySQL;

use Framework\DB\Interfaces\IDBResult;
use Framework\DB\Interfaces\IQuery;

class MySQLDriver implements \Framework\DB\Interfaces\IDBDriver
{

    protected ?\mysqli $mysqli = null;
    protected QueryToSQLStringConverter $converter;

    public function __construct(
        string $database,
        string $username,
        string $password,
        string $host = 'localhost',
        string $port = '3306'
    )
    {
        $this->createConnection($host, $username, $password, $database, $port);
        $this->converter = new QueryToSQLStringConverter();
    }

    public function select(IQuery $query): IDBResult
    {
        $sql = $this->converter->compileSQL($query);
        $result = $this->mysqli->query($sql);
        return new MySQLResult($query, $result);
    }

    public function create(IQuery $query): int
    {
        $sql = $this->converter->compileSQL($query);
        $result = $this->mysqli->query($sql);
        if(!$result)
        {
            throw new \Exception("Dont create record! \n SQL: $sql \n");
        }
        return $this->mysqli->insert_id;
    }

    public function delete(IQuery $query): void
    {
        $sql = $this->converter->compileSQL($query);
        $this->mysqli->query($sql);
    }

    public function update(IQuery $query): bool
    {
        $sql = $this->converter->compileSQL($query);
        return $this->mysqli->query($sql);
    }

    public function createConnection(...$options): void
    {
        if(!$this->mysqli)
        {
            $this->mysqli = new \mysqli(
                $options[0],
                $options[1],
                $options[2],
                $options[3],
                $options[4]);
        }
    }
}