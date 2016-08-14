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
        $table_name = static::GetTableName();

        $table_columns = [];
        try {
            $column_info = MySql::Select("SHOW COLUMNS FROM `$table_name`");
            foreach($column_info as $ci) {
                $table_columns[] = $ci["Field"];
            }
            unset($column_info);
        } catch (Exception $ignored) {
        }

        $query = "SELECT * FROM `$table_name`";
        $params = [];

        if (!empty($criteria)) {
            $where_clause = null;
            $types = '';
            foreach ($criteria as $column => $value) {
                if (array_search(strtolower($column), array_map('strtolower', $table_columns))) {
                    if ($where_clause == null) $where_clause = ' WHERE ';
                    $where_clause .= "`$column` = ? AND ";
                    $types .= 's';
                    $params[] = &$criteria[$column];
                }
            }
            if ($where_clause != null) {
                array_unshift($params, $types);
                $query .= rtrim($where_clause, 'AND ');
            }
        }

        $matches = MySql::Select($query, $params);

        $result = [];
        $entityName = static::GetTableName();
        foreach ($matches as $match) {
            $result[] = new $entityName($match);
        }

        http_response_code(200);
        echo(json_encode($result, JSON_UNESCAPED_UNICODE));
    }

    public function Create(array $attributes)
    {
        $table_name = static::GetTableName();
        $columns = "`" . implode("`,`", array_keys($attributes)) . "`";
        $types = implode("", array_fill(0, count($attributes), "s"));
        $placeholders = implode("", array_fill(0, count($attributes), "?"));

        $query = "INSERT INTO `$table_name` ($columns) VALUES ($placeholders)";

        $params = [$types];
        foreach ($attributes as $key => $value) {
            $params[] = &$attributes[$key];
        }

        $id = MySql::Insert($query, $params);

        http_response_code(200);
        echo(json_encode(array('Id' => $id)));
    }

    public function Delete(int $id)
    {
        $table_name = static::GetTableName();
        MySql::Delete($table_name, $id);
        http_response_code(200);
        echo(json_encode(array("message" => "success")));
    }

    public abstract function GetTableName();

    public abstract function GetKey();
}