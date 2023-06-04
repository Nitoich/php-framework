<?php

namespace Framework\DB\Migrations;

abstract class Migration
{
    public function __construct(
        protected string $table_name
    )
    {}

    public function getTableName(): string
    {
        return $this->table_name;
    }

    public function getFields(): array
    {
        $result = [];
        $reflection = new \ReflectionClass($this);
        $props = $reflection->getProperties();
        foreach ($props as $prop)
        {
            $attributes = $prop->getAttributes();
            foreach ($attributes as $attribute)
            {
                $result[$prop->getName()][] = new ($attribute->getName())(...array_merge(['name' => $prop->getName()], $attribute->getArguments()));
            }
        }

        return $result;
    }
}