<?php

namespace Framework\DB\Drivers\MySQL;

use Framework\DB\Drivers\MySQL\Interfaces\ISQLField;
use Framework\DB\Interfaces\IDBResult;
use Framework\DB\Interfaces\IQuery;
use Framework\DB\Migrations\BaseField;
use Framework\DB\Migrations\Migration;

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

    public function create_table(Migration $migration): bool
    {
        $additions = [];
        $sql = "CREATE TABLE IF NOT EXISTS {$migration->getTableName()} (";
        echo "<pre>";
//        var_dump($migration->getFields());
        echo "</pre>";
        foreach($migration->getFields() as $column => $types) {
            $sql_types = [];
            /** @var ISQLField $type */
            foreach ($types as $type)
            {
                if($type->getSQLAdditionTable() != '')
                {
                    $additions[] = $type->getSQLAdditionTable();
                }

                if($type->getSQL() != '')
                {
                    $sql_types[] = $type->getSQL();
                }
            }
            $sql_types = implode(' ', $sql_types);
            $sql .= "$column $sql_types, ";
        }

        $sql .= implode(', ', $additions);

//        $sql = rtrim($sql, ', ');
        $sql .= ")";

//        var_dump($sql);

        $this->mysqli->query($sql);
        return $this->mysqli->query($sql);
    }

    public function alter_table(Migration $migration): bool
    {
        // TODO: Implement alter_table() method.
        return false;
    }

    public function drop_table(string $table_name): bool
    {
        // TODO: Implement drop_table() method.
        return false;
    }

    public function show_tables(): IDBResult
    {
        // TODO: Implement show_tables() method.
    }

    public function show_table(string $table_name): IDBResult
    {
        // TODO: Implement show_table() method.
    }
}