<?php

namespace Framework\DI;

class Container implements IContainer
{
    private array $binds = [];

    public function get(string $abstract): object
    {
        return $this->binds[$abstract] ?? $this->prepareObject($abstract);
    }

    private function prepareObject(string $abstract): Object
    {
        $reflectionClass = new \ReflectionClass($abstract);
        $constructor = $reflectionClass->getConstructor();
        if(!$constructor || (!empty($constructor) && !$constructor->getAttributes()))
        {
            return new $abstract();
        }

        $attributes = [];
        $constructorAttrs = $constructor->getParameters();
        foreach ($constructorAttrs as $attr)
        {
            $attributes[$attr->getName()] = $this->get($attr->getType());
        }
        return new $abstract(...$attributes);
    }

    public function bind(string $abstract, object $instance): void
    {
        $this->binds[$abstract] = $instance;
    }

    public function has(string $id): bool
    {
        return !empty($this->binds[$id]);
    }

    public function singleton(string $abstract, ...$args): void
    {
        $this->bind($abstract, new $abstract(...$args));
    }
}