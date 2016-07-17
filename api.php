<?php

require("resources/library/classes.php");

$method = $_SERVER["REQUEST_METHOD"];
$path = trim($_SERVER["PATH_INFO"], "/");

switch ($method) {
    case "GET":
        Get($path, $_GET);
        break;
}

function Get($path, $get)
{
    if (empty($path)) return;

    $request = explode('/', $path);

    $entity_name = strtolower(array_shift($request));
    $target = strtolower(array_shift($request));

    $controller = ucfirst($entity_name) . 'Controller';
    $controller_class = "controllers/${controller}.php";

    if (file_exists($controller_class)) {
        require($controller_class);
        $controller = new $controller();

        if ($target == null) {
            $criteria = [];
            foreach ($get as $key => $value) {
                $criteria[$key] = $value;
            }
            $controller->GetAll($criteria);
            return;
        }

        if ($target === 'id') {
            $accountId = array_shift($request);
            if ($accountId != null) {
                $controller->Get($accountId + 0);
                return;
            }
        }
    }

    echo(json_encode(array('message' => 'invalid request')));
}