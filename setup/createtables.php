<?php

require("../resources/library/mysql.php");

const QUERIES_DIR = "queries";

chdir(QUERIES_DIR);
$files = array_diff(scandir(".", SCANDIR_SORT_ASCENDING), array(".", ".."));

foreach ($files as $file) {
    if (!($query = file_get_contents($file))) {
        continue;
    }

    $mysql = MySql::OpenConnection();

    $stmt = $mysql->prepare($query);

    if ($mysql->errno) {
        echo($mysql->error);
        echo("\r\n");
        continue;
    }

    $stmt->execute();

    if ($stmt->errno) {
        echo($stmt->error);
        echo("\r\n");
    }

    $stmt->close();
}