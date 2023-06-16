<?php

namespace Framework\DB\ORM\Interfaces;

use Framework\DB\ORM\Abstractions\Model;
use Framework\DB\QueryBuilder;

interface IRepository
{
    public function find(int $id): ?Model;
    public function findAll(): array;
    public function create(Model $model): int;
    public function update(Model $model): bool;
    public function delete(Model $model): void;
    public function save(Model $model): ?Model;
    public function findByQueryBuilder(QueryBuilder $builder): array;
}