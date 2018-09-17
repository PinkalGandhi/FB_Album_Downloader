<?php

    require_once 'Config.php';

    unset($_SESSION['facebookAccessToken']);

    unset($_SESSION['userData']);
    // unset($_SESSION['$accessToken'])

    header('Location: https://facebookalbumdownloader.herokuapp.com/index.php');

?>
