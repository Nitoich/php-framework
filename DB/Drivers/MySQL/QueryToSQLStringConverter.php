<?php

namespace Framework\DB\Drivers\MySQL;

use Framework\DB\Constants\QueryOperations;
use Framework\DB\Interfaces\IQuery;

class QueryToSQLStringConverter
{
    protected array $operationsTemplates = [
        QueryOperations::CREATE => 'INSERT INTO #TABLE# #FIELDS#',
        QueryOperations::SELECT => 'SELECT #SELECT_FIELDS# FROM #TABLE# #JOINS# #WHERES# #LIMIT# #OFFSET#',
        QueryOperations::UPDATE => 'UPDATE #TABLE# SET #UPDATE_FIELDS# #WHERES#',
        QueryOperations::DELETE => 'DELETE FROM #TABLE# #WHERES#'
    ];

    public function compileSQL(IQuery $query): string
    {
        $limit = $query->getLimit();
        $offset = $query->getOffset();

        $sql = $this->operationsTemplates[$query->getOperation()];
        $sql = str_replace("#SELECT_FIELDS#", $this->getSelectFields($query), $sql);
        $sql = str_replace("#FIELDS#", $this->getFieldsValues($query), $sql);
        $sql = str_replace("#UPDATE_FIELDS#", $this->getUpdateFields($query), $sql);
        $sql = str_replace("#TABLE#", $query->getTable(), $sql);
        $sql = str_replace("#WHERES#", $this->getWheres($query), $sql);
        $sql = str_replace("#JOINS#", '', $sql);
        $sql = str_replace("#LIMIT#", $limit ? "LIMIT $limit" : '', $sql);
        $sql = str_replace("#OFFSET#", $offset ? "OFFSET $offset" : '', $sql);
        return $sql;
    }

    protected function getUpdateFields(IQuery $query): string
    {
        $fields_values = $query->getFieldsValues();
        $fields_values_str = [];
        foreach ($fields_values as $field => $value)
        {
            $fields_values_str[] = "$field = '$value'";
        }
        return implode(', ', $fields_values_str);
    }

    protected function getFieldsValues(IQuery $query): string
    {
        $fields_values = $query->getFieldsValues();
        $fields = array_keys($fields_values);
        $values = array_values($fields_values);
        $values = array_map(function ($value) {
            return "'$value'";
        }, $values);

        $fields = implode(', ', $fields);
        $values = implode(', ', $values);

        return "($fields) VALUES ($values)";
    }

    protected function getWheres(IQuery $query): string
    {
        $conditions = $query->getConditions();
        if(empty($conditions)) { return ''; }
        $conditions_strings = [];
        foreach ($conditions as $field => $data)
        {
            $conditions_strings[] = "($field {$data['sign']} '{$data['value']}')";
        }
        return 'WHERE ' . implode(' AND ', $conditions_strings);
    }

    protected function getSelectFields(IQuery $query): string
    {
        $select_fields = $query->getSelectedFields();
        if(empty($select_fields))
        {
            return '*';
        }

        return implode(',', $select_fields);
    }
}