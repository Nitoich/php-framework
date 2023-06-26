<?php

namespace Framework\DB;

use Framework\DB\Constants\QueryConditionLogicType;
use Framework\DB\Constants\QueryOperations;
use Framework\DB\Constants\QuerySigns;
use Framework\DB\Constants\WhereTypes;
use Framework\DB\Interfaces\IDBResult;
use Framework\DB\Interfaces\IQuery;
use JetBrains\PhpStorm\Pure;

class QueryBuilder implements Interfaces\IQueryBuilder
{

    public function __construct(
        protected string $table,
        protected string $db_driver_id,
        protected ?IQuery $query = null)
    {
        if(empty($this->query))
        {
            $this->query = new Query($this->table, []);
        }
    }

    public function where(callable|string $field, string $sign = null, string $value = null): static
    {
        if($value == null) {
            $value = $sign;
            $sign = QuerySigns::EQUAL;
        }
        $this->query->setCondition($field, $sign, $value);
        return $this;
    }

    public function whereOr(callable|string $field, ?string $sign = null, ?string $value = null): static
    {
        $this->query->setCondition($field, $sign, $value, WhereTypes::DEFAULT, QueryConditionLogicType::OR);
        return $this;
    }

    public function first(): ?array
    {
        return $this->get()[0] ?? null;
    }

    public function select(array $fields): static
    {
        $this->query->setSelectedFields($fields);
        return $this;
    }

    public function update(array $fields_values): static
    {
        $this->query->setOperation(QueryOperations::UPDATE)->setFieldsValues($fields_values);
        return $this;
    }

    public function create(array $fields_values): static
    {
        $this->query->setOperation(QueryOperations::CREATE)->setFieldsValues($fields_values);
        return $this;
    }

    public function delete(): static
    {
        $this->query->setOperation(QueryOperations::DELETE);
        return $this;
    }

    public function getQuery(): IQuery
    {
        return $this->query;
    }

    public function execute(): mixed
    {
        $driver = DBDriverProvider::getDriver($this->db_driver_id);
        return $driver->{$this->query->getOperation()}($this->query);
    }

    public function join(string $table, string $local_field, string $foreign_field): static
    {
        // TODO: реализовать join'ы
        return $this;
    }

    public function get(): array
    {
        return $this->execute()->getData();
    }

    public function distinct(array|string $field): static
    {
        if(is_string($field)) { $this->query->setDistinct([$field]); }
        else { $this->query->setDistinct($field); }
        return $this;
    }

    public function groupBy(array|string $field): static
    {
        if(is_string($field)) { $this->query->setGroupBy([$field]); }
        else { $this->query->setGroupBy($field); }
        return $this;
    }

    public function limit(int $count): static
    {
        $this->query->setLimit($count);
        return $this;
    }

    public function offset(int $count): static
    {
        $this->query->setOffset($count);
        return $this;
    }
}