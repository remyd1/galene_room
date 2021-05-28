<?php

/* This script will allow creation of Galène room. */
include_once('inc/config.php');
//see https://medium.com/@hCaptcha/using-hcaptcha-with-php-fc31884aa9ea
include('inc/hcaptcha.php');
include('lib/php/actions.php');


$html = '<!doctype html>';
$html .= '<html>';
$html .= '<head>';
$html .= '<meta charset="utf-8">';
//loading bootstrap
$html .= '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">';
$html .= '<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js" integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>';
$html .= '<script src="'.HCAPTCHA_JSURL.'" async defer></script>';
$html .= '</head>';
$html .= '<body>';
$html .= '<div class="container">';
$html .= '<p class="lead">Galène Room Creation</p>';
$html .= '<p class="text-warning">Please note that this Galène room will be automatically destroyed after 30 days.</p>';


if(!isset($_POST) || empty($_POST)) {
    $html .= '<form action="create.php" method="post">';
    $html .= ' <div class="row g-3">';
    $html .= '  <div class="input-group col-8">';
    $html .= '   <label for="InputEmail1" class="form-label">Email address</label>';
    $html .= '   <input type="email" name="email" class="form-control" id="InputEmail1" aria-describedby="emailHelp" required>';
    $html .= '   <div id="emailHelp" class="form-text">We\'ll never share your email with anyone else.</div>';
    $html .= '  </div>';
    $html .= '  <label for="basic-url" class="form-label">Please choose the room name for your URL (a random suffix value will be added to this name) : </label>';
    $html .= '  <div class="input-group col-12">';
    $html .= '   <span class="input-group-text" id="basic-addon3">'.GALENE_HOMEPAGE.'/group/'.GROUP_SUBDIR.'</span>';
    $html .= '   <input type="text" value="test" name="groupname" id="groupname" class="form-control" id="basic-url" aria-describedby="basic-addon3" required>';
    $html .= '   <label class="form-text" for="groupname"> your groupname</label><br>';
    $html .= '  </div>';
    $html .= '  <div class="form-check>';
    $html .= '   <div class="col-10">';
    $html .= '    Should the room be visible on the Galène homepage :';
    $html .= '    <input class="form-check-input" type="radio" value="no" name="public" id="notpublic" checked="checked">';
    $html .= '    <label class="form-check-label" for="notpublic">No</label>';
    $html .= '    <input class="form-check-input" type="radio" value="yes" name="public" id="public_ok">';
    $html .= '    <label class="form-check-label" for="public_ok">Yes</label><br />';
    $html .= '   </div>';
    $html .= '  </div><br />';
    $html .= '  <div class="form-check col-12">';
    $html .= '   Please read our <a href="tos.txt">terms of service</a> <br /><input type="checkbox" class="form-check-input" name="TOS_ok" value="TOS" id="TOS" required>&nbsp;';
    $html .= '   <label class="form-check-label" for="TOS_ok">'.$ORG_TEXT.'</label><br>';
    $html .= '  </div><hr />';
    $html .= '  <div class="h-captcha" data-sitekey="'.HCAPTCHA_DATA_SITEKEY.'"></div>';
    $html .= '  <div class="col-12">';
    $html .= '   <button type="submit" value="Submit" class="btn btn-primary">Submit</button>';
    $html .= '  </div>';
    $html .= ' </form>';
    $html .= '</div>';
} else {
    if($HCAPTCHA) {
        /* checking the hcaptcha */
        $data = array(
            'secret' => HCAPTCHA_SECRET_KEY,
            'response' => $_POST['h-captcha-response']
        );
        
        $verify = curl_init();
        curl_setopt($verify, CURLOPT_URL, "https://hcaptcha.com/siteverify");
        curl_setopt($verify, CURLOPT_POST, true);
        curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($verify);
        //var_dump($response);
        $responseData = json_decode($response);
        if($responseData->success) {
            // your success code goes here
            $html .= create_from_form($_POST);
        } else {
            // return error to user; they did not pass
            $html .= "<p>Your hcaptcha is not valid ! Please try again.</p>";
        }
    } else {
        $html .= create_from_form($_POST);
    }

}

$html .= '</div>';
$html .= '</body>';
$html .= '</html>';


echo $html;
?>
