<?php

require_once('resources/library/mysql.php');

abstract class BaseController
{
    public function Get(int $id)
    {
        self::GetAll(array(static::GetKey() => $id));
    }

    public function GetAll(array $criteria)
    {
        $query = 'SELECT * FROM ' . static::GetTableName();
        $params = [];

        if (!empty($criteria)) {
            $where_clause = ' WHERE ';
            $types = '';
            foreach ($criteria as $column => $value) {
                $where_clause .= "`$column` = ? AND ";
                $types .= 's';
                $params[] = &$criteria[$column];
            }
            array_unshift($params, $types);
            $query .= rtrim($where_clause, 'AND ');
        }

        try {
            $matches = MySql::Select($query, $params);
        } catch (Exception $e) {
            http_response_code(400);
            echo(json_encode(array("message" => $e->getMessage())));
            return;
        }

        $result = [];
        $entityName = static::GetTableName();
        foreach ($matches as $match) {
            $result[] = new $entityName($match);
        }

        http_response_code(200);
        echo(json_encode($result, JSON_UNESCAPED_UNICODE));
    }

    public abstract function GetTableName();

    public abstract function GetKey();
}