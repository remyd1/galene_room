<?php

include_once __DIR__ . "/../inc/config.php";
include_once __DIR__ . "/../lib/php/actions.php";

/* 
  This code will need an extra `groupname` key in order to create the right filename; 
  Otherwise, it will create a random md5 string.
*/
$room = json_decode(file_get_contents('php://input'), true);
if($room){ //valid json
    //var_dump($room);
    $ret = create_from_api($room);
    echo($ret);
}

?>