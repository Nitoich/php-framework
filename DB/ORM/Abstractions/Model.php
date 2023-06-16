<?php

namespace Framework\DB\ORM\Abstractions;

abstract class Model implements \JsonSerializable
{
    public function __construct(array $fields)
    {
        foreach ($fields as $field => $value) {
            $this->$field = $value;
        }
    }

    protected function getComputedFields(): array
    {
        $reflection = new \ReflectionClass($this);
        $result = [];
        foreach ($reflection->getMethods() as $method)
        {
            foreach ($method->getAttributes() as $attribute)
            {
                if(strripos($attribute->getName(), 'ComputedField') !== false)
                {
                    foreach ($attribute->getArguments() as $argument)
                    {
                        $result[$method->getName()] = $argument;
                    }
                }
            }
        }
        return $result;
    }

    public function __get(string $name): mixed
    {
        if(in_array($name, $this->getComputedFields()))
        {
            return $this->$name();
        }
        return $this->$name ?? null;
    }

    public function __set(string $name, $value): void
    {
        $this->$name = $value;
    }

    public function toArray(): array
    {
        $reflection = new \ReflectionClass($this);
        $result = [];
        foreach ($reflection->getProperties() as $property)
        {
            if($property->isInitialized($this)) {
                $result[$property->getName()] = $property->getValue($this);
            }
        }

        $computedFields = $this->getComputedFields();
        foreach ($computedFields as $field => $needed)
        {
            if($needed == false) { continue; }
            $result[$field] = $this->$field();
        }

        return $result;
    }

    public function jsonSerialize(): array {
        return $this->toArray();
    }
}