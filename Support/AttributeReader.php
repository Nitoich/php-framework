<?php

namespace Framework\Support;

class AttributeReader
{
    public static function get(string $class): array
    {
        $reflection = new \ReflectionClass($class);
        $result = [
            'class' => [],
            'methods' => [],
            'properties' => []
        ];

        foreach ($reflection->getAttributes() as $attribute)
        {
            $result['class'][$attribute->getName()] = new ($attribute->getName())(...$attribute->getArguments());
        }

        foreach ($reflection->getProperties() as $property)
        {
            foreach ($property->getAttributes() as $attribute)
            {
               $result['properties'][$property->getName()][$attribute->getName()] = new ($attribute->getName())(...$attribute->getArguments());
            }
        }

        foreach ($reflection->getMethods() as $method)
        {
            foreach ($method->getAttributes() as $attribute)
            {
                $result['methods'][$method->getName()][$attribute->getName()] = new ($attribute->getName())(...$attribute->getArguments());
            }
        }

        return $result;
    }
}