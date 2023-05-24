<?php

namespace Framework\DB\Interfaces;

use Framework\DB\Constants\QueryConditionLogicType;
use Framework\DB\Constants\QueryOperations;
use Framework\DB\Constants\WhereTypes;

interface IQuery
{
    public function __construct(
        string $table,
        array $conditions,
        string $operation = QueryOperations::SELECT,
        array $fieldsValues = [],
        array $selectFields = [],
        array $joins = [],
        ?int $limit = null,
        ?int $offset = null,
        array $groupBy = [],
        array $distinct = []
    );
    public function getOperation(): string;
    public function setOperation(string $operation): static;
    public function getConditions(): array;
    public function setCondition(
        string $field,
        string $sign,
        string $value,
        string $type = WhereTypes::DEFAULT,
        string $logic = QueryConditionLogicType::AND,
    ): static;
    public function getTable(): string;
    public function setTable(string $table): static;
    public function getLimit(): ?int;
    public function setLimit(?int $limit): static;
    public function getOffset(): ?int;
    public function setOffset(?int $offset): static;
    public function getSelectedFields(): array;
    public function setSelectedFields(array $selectedFields): static;
    public function getFieldsValues(): array;
    public function setFieldsValues(array $fieldsValues): static;
    public function setGroupBy(array $fields): static;
    public function getGroupBy(): array;
    public function setDistinct(array $fields): static;
    public function getDistinct(): array;
}