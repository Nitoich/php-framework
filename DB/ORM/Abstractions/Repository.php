<?php

namespace Framework\DB\ORM\Abstractions;

use Framework\Config;
use Framework\DB\ORM\Attributes\UsageModel;
use Framework\DB\QueryBuilder;

abstract class Repository implements \Framework\DB\ORM\Interfaces\IRepository
{
    protected ?string $table;
    protected ?string $model;

    public function __construct(protected string $db_driver_id)
    {
        $this->model = $this->getModelClass();
        $this->table = $this->getTableName();

        if(is_null($this->model)) { throw new \Exception("Model uncorrect! \n"); }
        if(is_null($this->table)) { throw new \Exception("Table uncorrect! \n"); }
    }

    protected function getTableName(): ?string
    {
        $model = $this->getModelClass();
        $model_reflection = new \ReflectionClass($model);
        $attributes = $model_reflection->getAttributes();
        foreach ($attributes as $attribute)
        {
            if(strripos($attribute->getName(), 'UseTable') !== false)
            {
                foreach ($attribute->getArguments() as $argument)
                {
                    return $argument;
                }
            }
        }
        return null;
    }

    protected function getModelClass(): ?string
    {
        $self_reflection = new \ReflectionClass($this);
        $attributes = $self_reflection->getAttributes();
        foreach ($attributes as $attribute)
        {
            if(strripos($attribute->getName(), 'UsageModel') !== false)
            {
                foreach ($attribute->getArguments() as $argument)
                {
                    return $argument;
                }
            }
        }
        return null;
    }
    public function find(int $id): ?Model
    {
        $queryBuilder = new QueryBuilder($this->table, $this->db_driver_id);
        $result = $queryBuilder->where('id', $id)->get();
        if(count($result) == 0) {
            return null;
        }
        return new ($this->model)($result[0]);
    }

    public function findByQueryBuilder(QueryBuilder $builder): array
    {
        $result = $builder->get();
        return array_map(function ($item) {
            return new ($this->model)($item);
        }, $result);
    }

    public function findAll(): array
    {
        $queryBuilder = new QueryBuilder($this->table, $this->db_driver_id);
        $result = $queryBuilder->get();
        return array_map(function ($item) {
            return new ($this->model)($item);
        }, $result);
    }

    public function create(Model $model): int
    {
        $queryBuilder = new QueryBuilder($this->table, $this->db_driver_id);
        return $queryBuilder->create($model->toArray())->execute();
    }

    public function update(Model $model): bool
    {
        $queryBuilder = new QueryBuilder($this->table, $this->db_driver_id);
        return $queryBuilder->where('id', $model->id)->update($model->toArray())->execute();
    }

    public function save(Model $model): ?Model
    {
        if(is_null($model->id))
        {
            $id = $this->create($model);
            return $this->find($id);
        } else {
            $this->update($model);
            return $this->find($model->id);
        }
    }

    public function delete(Model $model): void
    {
        $queryBuilder = new QueryBuilder($this->table, $this->db_driver_id);
        $queryBuilder->where('id', $model->id)->delete()->execute();
    }
}