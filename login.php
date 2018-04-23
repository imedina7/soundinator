<?php

require_once __DIR__ . "/lib/session/Auth.php";

header("Content-type: application/json; charset=utf-8");

if (isset($_POST['username']) && isset($_POST['password'])) {

    if ($session_id = Auth::validate_user($_POST['username'],$_POST['password'])) {
        $outObj = Array ("session_id" => $session_id);
        $jsonOutput = json_encode($outObj);
        error_log($jsonOutput);
        echo $jsonOutput;
    } else
        echo '{ "error": "Invalid username and/or password" }';
}