<?php

require_once __DIR__ . "/lib/clients/DatabaseConnect.php";

$db = DatabaseConnect::getInstance();
$s = $db->getSound($_GET['sound_id']);

header("Content-type: ".$s['sound_type']."; charset=utf-8");
echo hex2bin($s['sound_data']);

?>