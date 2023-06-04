<?php

namespace Framework\DB\Interfaces;

use Framework\DB\Migrations\Migration;

interface IDBDriver
{
    public function select(IQuery $query): IDBResult;
    public function create(IQuery $query): int;
    public function delete(IQuery $query): void;
    public function update(IQuery $query): bool;
    public function create_table(Migration $migration): bool;
    public function alter_table(Migration $migration): bool;
    public function drop_table(string $table_name): bool;
    public function show_tables(): IDBResult;
    public function show_table(string $table_name): IDBResult;
}