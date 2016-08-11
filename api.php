<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

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
            $recordId = array_shift($request);
            if ($recordId != null) {
                $controller->Get($recordId + 0);
                return;
            }
        }
    }

    http_response_code(400);
    echo(json_encode(array('message' => 'invalid request')));
}