<?php

require_once __DIR__ . "/lib/clients/DatabaseConnect.php";

$db = DatabaseConnect::getInstance();
$s = $db->getSound(2);

header("Content-type: ".$s['sound_type']."; charset=utf-8");
echo base64_decode($s['data_base64']);

?>