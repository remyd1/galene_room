<?php

include_once __DIR__ . "/../inc/config.php";
include_once __DIR__ . "/../lib/php/actions.php";

$all_rooms=list_rooms();
$json_all_rooms=json_encode($all_rooms);
echo($json_all_rooms);


?>