<?php

namespace Framework\DB;

use Framework\DB\Constants\QueryConditionLogicType;
use Framework\DB\Constants\WhereTypes;

class Query implements Interfaces\IQuery
{
    public function __construct(
        protected string $table,
        protected array $conditions,
        protected string $operation = \Framework\DB\Constants\QueryOperations::SELECT,
        protected array $fieldsValues = [],
        protected array $selectFields = [],
        protected array $joins = [],
        protected ?int $limit = null,
        protected ?int $offset = null,
        protected array $groupBy = [],
        protected array $distinct = []
    ) {}

    public function getConditions(): array
    {
        return $this->conditions;
    }

    public function getTable(): string
    {
        return $this->table;
    }

    public function getLimit(): ?int
    {
        return $this->limit;
    }

    public function getOffset(): ?int
    {
        return $this->offset;
    }

    public function getSelectedFields(): array
    {
        return $this->selectFields;
    }

    public function getFieldsValues(): array
    {
        return $this->fieldsValues;
    }

    public function setCondition(
        string $field,
        string $sign,
        string $value,
        string $type = WhereTypes::DEFAULT,
        string $logic = QueryConditionLogicType::AND
    ): static
    {
        $this->conditions[$field] = [
            'sign' => $sign,
            'value' => $value,
            'logic' => $logic,
            'type' => $type
        ];
        return $this;
    }

    public function setTable(string $table): static
    {
        $this->table = $table;
        return $this;
    }

    public function setLimit(?int $limit): static
    {
        $this->limit = $limit;
        return $this;
    }

    public function setOffset(?int $offset): static
    {
        $this->offset = $offset;
        return $this;
    }

    public function setSelectedFields(array $selectedFields): static
    {
        $this->selectFields = $selectedFields;
        return $this;
    }

    public function setFieldsValues(array $fieldsValues): static
    {
        $this->fieldsValues = $fieldsValues;
        return $this;
    }

    public function getOperation(): string
    {
        return $this->operation;
    }

    public function setOperation(string $operation): static
    {
        $this->operation = $operation;
        return $this;
    }

    public function setGroupBy(array $fields): static
    {
        $this->groupBy = $fields;
        return $this;
    }

    public function getGroupBy(): array
    {
        return $this->groupBy;
    }

    public function setDistinct(array $fields): static
    {
        $this->distinct = $fields;
        return $this;
    }

    public function getDistinct(): array
    {
        return $this->distinct;
    }
}