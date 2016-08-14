<?php

error_reporting(0);

class MySql
{
    const MYSQL_SERVER = "127.0.0.1";
    const MYSQL_USER = "web";
    const MYSQL_PASS = "web";
    const MYSQL_DB = "demo";

    public static function Select(string $query, $param_array = null, &$mysqli = null) : array
    {
        $rows = array();
        $close = false;

        if ($mysqli == null) {
            $mysqli = self::OpenConnection();
            $close = true;
        }

        try {

            $stmt = $mysqli->prepare($query);
            if (!$stmt) {
                throw new Exception($mysqli->error);
            }

            if (!empty($param_array)) {
                call_user_func_array(array($stmt, "bind_param"), $param_array);
            }

            if ($stmt->execute()) {
                $res = $stmt->get_result();

                if ($res) {
                    while ($row = $res->fetch_array(MYSQLI_ASSOC)) {
                        $rows[] = $row;
                    }
                    $res->free_result();
                }

                $stmt->close();
            }

            return $rows;

        } finally {
            if ($close) {
                $mysqli->close();
            }
        }
    }

    public static function Insert(string $query, $param_array = null, &$mysqli = null) : int
    {
        $close = false;

        if ($mysqli == null) {
            $mysqli = self::OpenConnection();
            $close = true;
        }

        try {
            $stmt = $mysqli->prepare($query);
            if (!$stmt) {
                throw new Exception($mysqli->error);
            }

            if (!empty($param_array)) {
                call_user_func_array(array($stmt, "bind_param"), $param_array);
            }

            if ($stmt->execute()) {
                $response = $stmt->insert_id;
                $stmt->close();
            } else {
                throw new Exception($stmt->error);
            }

            return $response;
        } finally {
            if ($close) {
                $mysqli->close();
            }
        }
    }

    public static function Delete(string $table_name, int $id, &$mysqli = null)
    {
        $close = false;

        if ($mysqli == null) {
            $mysqli = self::OpenConnection();
            $close = true;
        }

        try {
            $result = $mysqli->query("SHOW KEYS FROM `$table_name` WHERE Key_name = 'PRIMARY'");
            if ($result && $result->num_rows === 1) {
                $row = $result->fetch_array(MYSQLI_ASSOC);
                $pk = $row["Column_name"];
                $result->free();
            }

            if (isset($pk)) {
                $mysqli->query("DELETE FROM `$table_name` WHERE `$pk`='$id'");
            } else {
                throw new Exception("Unable to find the primary key for the selected table");
            }
        } finally {
            if ($close) {
                $mysqli->close();
            }
        }
    }

    public static function OpenConnection() : mysqli
    {
        $mysqli = new mysqli(self::MYSQL_SERVER, self::MYSQL_USER, self::MYSQL_PASS, self::MYSQL_DB);
        $mysqli->set_charset("utf8");
        return $mysqli;
    }
}