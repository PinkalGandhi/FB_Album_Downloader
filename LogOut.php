<?php

    require_once 'Config.php';
    
    //unset the accesstoken
    unset($_SESSION['facebookAccessToken']);

    //unset users data
    unset($_SESSION['userData']);


    header('Location: https://pinkalgandhi16.000webhostapp.com/index.php');

?>