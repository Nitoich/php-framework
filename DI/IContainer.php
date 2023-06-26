<?php

namespace Framework\DI;

interface IContainer
{
    public function get(string $abstract): ?Object;
    public function bind(string $abstract, Object $instance): void;
    public function has(string $id): bool;
    public function singleton(string $abstract, ...$args);
}