<?php

    //Include autoloader
    require_once 'Facebook/autoload.php';

    //Include Required Libraries
    use Facebook\Facebook;
    use Facebook\Exceptions\FacebookResponseException;
    use Facebook\Exception\FacebookSDKException;
 
    //session start
    if(!session_id())
    {
        session_start();
    }

    //$redirectURL = "https://localhost/OST/FaceBookAlbumDownloader/FBCallBack.php"; 
    $FBpermissions = array('email','user_photos','user_likes'); //permissions

    //configuration & set Up FaceBook SDK
    $FB = new \Facebook\Facebook([
        'app_id' => '142201069920240', //FaceBook APP ID
        'app_secret' => 'fd74be7dd1eb42f5901676f7bf6b1c2a', // FaceBook App Secret 
        'default_graph_version' => 'v3.1', //FaceBook Graph Version
    ]);

    //echo $default_graph_version;

    //get redirect Login Helper
    $helper = $FB->getRedirectLoginHelper();

?>