<?php

require_once __DIR__ . "/lib/session/Auth.php";
require_once __DIR__ . "/lib/models/Sound.php";

header("Content-type: application/json; charset=utf-8");
if ( ! isset($_POST['SESSION_ID']))
    die('{ "error": "Failed to validate session" }');

if (! Auth::validate_session($_POST['SESSION_ID']))
    die('{ "error": "Failed to validate session" }');

$output = '{ "error": "Bad request" }';
if (isset($_GET["action"])) {

    $session_id = $_POST['SESSION_ID'];
    
    $redisClient = RedisConnect::getInstance();
    
    $user_id = $redisClient->getSessionUserId($session_id);
    $db = DatabaseConnect::getInstance();

    switch ($_GET["action"]){
        case "fetch_sounds":
            $sounds = $db->getSounds($user_id);
            $outArray = Array();
            foreach ($sounds as $s){
                $sound_data = stream_get_contents($s['sound_data']);
                $currentObj = array("name" => $s['sound_name'],
                                    "id" => $s['sound_id'],
                                    "data" => base64_encode(hex2bin($sound_data)),
                                    "type" => $s['sound_type']);
                array_push($outArray,$currentObj);
            }
            $outObject = array("status" => "success","soundList" => $outArray);
            $output = json_encode($outObject);
            error_log("api sent object: ".$output);
        break;
        case "save_sounds":
            if ( isset($_FILES))
                if ($savedFiles = $db->saveSounds($user_id,$_FILES))
                    $output = '{"status" : "success", "savedFiles": '.json_encode($savedFiles).' }';
        break;
        default: 
            $output = '{ "error": "You must specify a valid action" }';
    }
    
}

echo $output;
?>