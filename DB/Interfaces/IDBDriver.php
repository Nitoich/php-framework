<?php

namespace Framework\DB\Interfaces;

interface IDBDriver
{
    public function select(IQuery $query): IDBResult;
    public function create(IQuery $query): int;
    public function delete(IQuery $query): void;
    public function update(IQuery $query): bool;
}