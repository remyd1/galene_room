<?php

include_once __DIR__ . "/../inc/config.php";
include_once __DIR__ . "/../lib/php/actions.php";

/*
  filename must be post as $_GET var.
*/

if(isset($_GET) && !empty($_GET)) {
    if(isset($_GET["filename"]) && !empty($_GET["filename"])) {
        $content = read_room($_GET["filename"]);
        echo($content);
    } elseif(isset($_GET["groupname"]) && !empty($_GET["groupname"])) {
        // if there is a md5 string concatenated with groupname, it will not work...
        $content = read_room($_GET["groupname"].".json");
        echo($content);
    }
}

?>