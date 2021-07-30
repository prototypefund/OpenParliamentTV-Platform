<?php

session_start();

error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
//error_reporting(0);

header('Content-Type: application/json');


include_once(__DIR__ . '/../../modules/utilities/auth.php');
$auth = auth($_SESSION["userdata"]["id"], "apiV1", $_REQUEST["action"]);

if ($auth["meta"]["requestStatus"] != "success") {

    $return = $auth;

} else {

    require_once(__DIR__ . "/api.php");

    $return = apiV1($_REQUEST);

}

echo json_encode($return, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);



?>