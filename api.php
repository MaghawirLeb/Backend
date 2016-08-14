<?php

require("resources/library/classes.php");

$method = $_SERVER["REQUEST_METHOD"];
$path = trim($_SERVER["PATH_INFO"], "/");

header("Content-Type: application/json");
if ($method === "GET") header("Access-Control-Allow-Origin: *");

if (empty($path)) goto invalid;

try {
    api_call($method, $path);
    return;
} catch (Exception $e) {
    $message = $e->getMessage();
    goto invalid;
}

function api_call($method, $path)
{
    $request = explode('/', $path);

    $entity_name = strtolower(array_shift($request));
    $target = strtolower(array_shift($request));

    $controller = ucfirst($entity_name) . 'Controller';
    $controller_class = "controllers/${controller}.php";

    if (file_exists($controller_class)) {
        require($controller_class);
        $controller = new $controller();
        if ($method === "GET") {
            if ($target == null) {
                $criteria = [];
                foreach ($_GET as $key => $value) {
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

        if ($method === "POST") {
            $controller->Create($_POST);
            return;
        }

        if ($method === "DELETE") {
            foreach (getallheaders() as $header => $value) {
                if ($header === "Id") {
                    $controller->Delete($value);
                    return;
                }
            }
        }
    }
}

invalid:
http_response_code(400);
echo(json_encode(array('message' => isset($message) ? $message : 'Invalid Request')));