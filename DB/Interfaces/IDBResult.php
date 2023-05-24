<?php

namespace Framework\DB\Interfaces;

interface IDBResult
{
    public function getData(): array;
    public function isSusses(): bool;
    public function getErrors(): array;
    public function errorHandler(): void;
    public function getQuery(): IQuery;
    public function getLastInsertId(): ?int;
}