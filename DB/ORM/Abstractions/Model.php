<?php

namespace Framework\DB\ORM\Abstractions;

use Framework\DB\ORM\Attributes\ComputedField;
use Framework\DB\ORM\Attributes\UseTable;
use Framework\Support\AttributeReader;

abstract class Model implements \JsonSerializable
{
    public function __construct(array $fields = [])
    {
        foreach ($fields as $field => $value) {
            $this->$field = $value;
        }
    }

    protected function getComputedFields(): array
    {
        $result = [];
        foreach (AttributeReader::get($this::class)['methods'] as $method => $attributes)
        {
            foreach ($attributes as $attribute)
            {
                if($attribute instanceof ComputedField)
                {
                    $result[$method] = $attribute->getNeeded();
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

    public static function getTableName(): string
    {
        $attributes = AttributeReader::get(static::class);
        return $attributes['class'][UseTable::class]->getTableName();
    }
}