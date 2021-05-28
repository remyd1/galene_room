<?php

include_once("../inc/config.php");
include_once("../lib/php/actions.php");

$all_rooms=list_rooms();
$json_all_rooms=json_encode($all_rooms);
echo($json_all_rooms);


?>