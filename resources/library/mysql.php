<?php

class MySql
{
    const MYSQL_SERVER = "127.0.0.1";
    const MYSQL_USER = "web";
    const MYSQL_PASS = "web";
    const MYSQL_DB = "demo";

    public static function Select(string $query, $param_array = null, $mysqli = null) : array
    {
        $rows = array();
        $close = false;

        if ($mysqli == null) {
            $mysqli = self::OpenConnection();
            $close = true;
        }

        $stmt = $mysqli->prepare($query);
        if (!empty($param_array)) {
            call_user_func_array(array($stmt, "bind_param"), $param_array);
        }
        $stmt->execute();

        $res = $stmt->get_result();

        if ($res) {
            while ($row = $res->fetch_array(MYSQLI_ASSOC)) {
                $rows[] = $row;
            }
            $res->free_result();
        }

        $stmt->close();
        if ($close) {
            $mysqli->close();
        }

        return $rows;
    }

    public static function OpenConnection() : mysqli
    {
        return new mysqli(self::MYSQL_SERVER, self::MYSQL_USER, self::MYSQL_PASS, self::MYSQL_DB);
    }
}