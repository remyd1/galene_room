<?php

include_once('inc/config.php');

function create_from_form($POST) {
    //$html .= var_dump($POST);
    $groupname = $POST['groupname'];
    $mail = $POST['email'];
    $md5str = md5($mail);
    $filebasename = $groupname."_".$md5str;
    $filename = $groupname."_".$md5str.".json";
    $roomname = GROUP_SUBDIR.$filebasename;
    
    $public = $POST['public'];
    if($public == "yes"){
        $public=true;
    } else { $public = false; }
    
    $format = '%Y%m%d_%H%M%S';
    $strf = strftime($format);

    $content = ["op" => [new stdclass()], "presenter" =>  [new stdclass()], "public" => $public, "max-clients" => $MAX_CLIENTS, "comment" => "Created by ".$mail." at ".$strf, "contact" => $CONTACT];
    $filecontent = json_encode($content);

    $handle = fopen(FILE_SUBDIR.$filename, 'x');
    if($handle != FALSE) {
        fwrite($handle, $filecontent);
        fclose(FILE_SUBDIR.$filename);
        $html_return = "Ok, your room has been successfully created ! Please note your new room URL : <a href='".GALENE_HOMEPAGE."/group/".$roomname."'>".GALENE_HOMEPAGE."/group/".$roomname."</a>";
    } else {
        $html_return = "Whoops ...! Your room cannot be created. An existing room with the same name already exists... ! Please try again !";
    }
    return($html_return);

}

function create_from_api($POST) {

}


?>
