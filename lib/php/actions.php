<?php

include_once('../../inc/config.php');

function create_from_form($POST) {
    //$html .= var_dump($POST);
    $groupname = $POST['groupname'];
    $mail = $POST['email'];
    if(!preg_match('/@/', $mail)) {
        $html_return = "Your mail is not valid ! Please try again !";
    } else {
        $md5str = md5(uniqid(mt_rand(), true));
        $filebasename = $md5str;
        $blacklistChars = '"%\'*;<>?^`{|}~/\\#=&';
        $pattern = preg_quote($blacklistChars, '/');
        if(!preg_match('/[' . $pattern . ']/', $groupname)) {
            $filebasename = $groupname."_".$md5str;
        }
        $filename = $filebasename.".json";
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
    }

    return($html_return);

}

function create_from_api($room) {
    $md5str = md5(uniqid(mt_rand(), true));
    $filebasename = $md5str;
    $blacklistChars = '"%\'*;<>?^`{|}~/\\#=&';
    $pattern = preg_quote($blacklistChars, '/');
    if(isset($room['groupname'])) {
        if(!preg_match('/[' . $pattern . ']/', $groupname)) {
            $filebasename = $room['groupname']."_".$md5str;
        }
    }
    $filename = $filebasename.".json";
    $roomname = GROUP_SUBDIR.$filebasename;
    
    // adding timestamp in comment.
    $format = '%Y%m%d_%H%M%S';
    $strf = strftime($format);
    if(isset($room['comment'])) {
        $room['comment'] = $room['comment'].", created at ".$strf;
    } else {
        $room['comment'] = "Room created at ".$strf;
    }

    //encode back to a valid JSON for Galène. PHP empty arrays are not associative arrays...
    if(is_null($room['op']) || empty($room['op'][0])) {unset($room['op'][0]);}
    if(is_null($room['presenter']) || empty($room['presenter'][0])) {unset($room['presenter'][0]);}
    if(is_null($room['other']) || empty($room['other'][0])) {unset($room['other'][0]);}
    
    $filecontent = json_encode($room);

    $handle = fopen(FILE_SUBDIR.$filename, 'x');
    if($handle != FALSE) {
        fwrite($handle, $filecontent);
        fclose(FILE_SUBDIR.$filename);
        $html_return = "Ok\n".GALENE_HOMEPAGE."/group/".$roomname;
    } else {
        $html_return = "Whoops ...! Your room cannot be created. An existing room with the same name already exists... ! Please try again !";
    }
    return($html_return);
}

function list_rooms() {
    $all_rooms = array();
    //echo("groupdir: ".FILE_SUBDIR);
    if ($handle = opendir(FILE_SUBDIR)) {
        while (false !== ($entry = readdir($handle))) {
            if ($entry != "." && $entry != "..") {
                array_push($all_rooms, $entry);
            }
        }
        closedir($handle);
    }
    return($all_rooms);
}

function read_room($filename) {
    $content = [];
    // this code should not be able to read files elsewhere
    $blacklistChars = '"%\'*;<>?^`{|}~/\\#=&';
    $pattern = preg_quote($blacklistChars, '/');
    if(!preg_match('/[' . $pattern . ']/', $filename)) {
        if(file_exists(FILE_SUBDIR.$filename)) {
            $content = file_get_contents(FILE_SUBDIR.$filename);
        }
        return($content);
    }
}

function delete_room($filename) {
    $retcode = "";
    // this code should not be able to delete files elsewhere
    $blacklistChars = '"%\'*;<>?^`{|}~/\\#=&';
    $pattern = preg_quote($blacklistChars, '/');
    if(!preg_match('/[' . $pattern . ']/', $filename)) {
        if(file_exists(FILE_SUBDIR.$filename)) {
            $retcode = unlink(FILE_SUBDIR.$filename);
        }
    return($retcode);
    }
}

?>