<?php

namespace Framework\DB\Interfaces;
/*
 * TODO: Релизовать полностью функционал запросов со всеми функциями для БД
 */
interface IQueryBuilder
{
    public function __construct(
        string $table,
        string $db_driver_id,
        ?IQuery $query = null
    );

    public function where(string|callable $field, string $sign = null, string $value = null): static;
    public function whereOr(string|callable $field, ?string $sign = null, ?string $value = null): static;
    public function select(array $fields): static;
    public function update(array $fields_values): static;
    public function create(array $fields_values): static;
    public function join(string $table, string $local_field, string $foreign_field): static;
    public function delete(): static;
    public function distinct(string|array $field): static;
    public function groupBy(string|array $field): static;
    public function limit(int $count): static;
    public function offset(int $count): static;
    public function getQuery(): IQuery;
    public function execute(): IDBResult;
    public function get(): array;
}