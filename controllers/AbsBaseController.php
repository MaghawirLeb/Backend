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

        $matches = MySql::Select($query, $params);

        $accounts = [];
        foreach ($matches as $match) {
            $accounts[] = new Account($match);
        }
        echo(json_encode($accounts, JSON_UNESCAPED_UNICODE));
    }

    public abstract function GetTableName();

    public abstract function GetKey();
}