<?php
//require_once "lib/auth.php";

header("Content-type: application/json; charset=utf-8");

// if (validate_session($_GET['SESSION_ID'])){

// } else {

// }
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
$jsonOutput = trim($jsonOutput,',');
?>{"soundList":[<?php echo $jsonOutput; ?>]}