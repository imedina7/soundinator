<?php

require_once __DIR__ . "/lib/session/Auth.php";

header("Content-type: application/json; charset=utf-8");

if (Auth::validate_session($_POST['SESSION_ID'])){

} else {
    die('{ "error": "Failed to validate session" }');
}


$soundfiles = scandir("./sounds/");
$i = 0;
$jsonOutput = "";

foreach ($soundfiles as $s) {
    if (preg_match('/^\./', $s) == 0) {
        $filedata = base64_encode(file_get_contents("./sounds/".$s));
        $jsonOutput .= '{"id":"'.$i.'","name": "'.$s.'","data":"'.$filedata.'"},';
        $i++;
    }
}

$db = DatabaseConnect::getInstance();
$dbOutput = "";
$soundData = $db->getSounds();

foreach ($soundData as $sdata){
    $dbOutput = '{"id":"'.$sdata['sound_id'].'","name":"'.$sdata['sound_name'].'"},';
}

$jsonOutput = trim($jsonOutput,',');
$dbOutput = trim($dbOutput,',');
?>{"soundList":[<?php echo $jsonOutput; ?>],"dbOutput":[<?php echo $dbOutput; ?>]}