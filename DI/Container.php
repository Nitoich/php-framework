<?php

namespace Framework\DI;

use Framework\DB\QueryBuilder;

class Container implements IContainer
{
    protected array $binds = [];
    public function __construct() {}

    public function get(string $abstract): ?object
    {
        $abstract = str_replace('?', '', $abstract);
        return key_exists($abstract, $this->binds) ? $this->binds[$abstract] : $this->prepareObject($abstract);
    }

    public function getMethodParameters(string $class, string $method): array
    {
        $reflection = new \ReflectionClass($class);
        return $reflection->getMethod($method)->getParameters();
    }

    public function getClosureParameters(\Closure $handler): array
    {
        $reflection = new \ReflectionFunction($handler);
        return $reflection->getParameters();
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

    public function build(string $abstract): Object
    {
        return $this->prepareObject($abstract);
    }

    public function bind(string $abstract, ?object $instance): void
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

    public function executeMethod(Object $class, string $method, ?array $primitives = null): mixed
    {
        $args = [];
        $params = $this->getMethodParameters($class::class, $method);
        foreach ($params as $param)
        {
            if(is_array($primitives) && isset($primitives[$param->getName()]))
            {
                $args[$param->getName()] = $primitives[$param->getName()];
            } else {
                $args[$param->getName()] = $this->get($param->getType());
            }
        }

        return $class->$method(...$args);
    }

    public function executeClosure(\Closure $handler, ?array $primitives = null): mixed
    {
        $args = [];
        $params = $this->getClosureParameters($handler);
        foreach ($params as $param)
        {
            if(is_array($primitives) && isset($primitives[$param->getName()]))
            {
                $args[$param->getName()] = $primitives[$param->getName()];
            } else {
                $args[$param->getName()] = $this->get($param->getType());
            }
        }
        return $handler(...$args);
    }
}