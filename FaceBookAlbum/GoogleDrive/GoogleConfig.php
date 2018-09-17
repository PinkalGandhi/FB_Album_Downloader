<?php

    require_once('Config.php');
    require_once('GoogleAPI/Google_Client.php');
    require_once('GoogleAPI/contrib/Google_DriveService.php');

    $Gclient = new Google_Client();
    $Gclient->setClientId('818891243832-pseb3p2cr5bhu29igoh5budf6d3pp1hs.apps.googleusercontent.com');
    $Gclient->setClientSecret('H_-cAaD503xZxKtdAfUHltNp');
    $Gclient->setApplicationName('rtCampProjectUploadAlbums');
    $Gclient->setRedirectUri('https://facebookalbumdownloader.herokuapp.com/Album.php');
    $Gclient->setScopes(array('https://www.googleapis.com/auth/drive.file'));

    if(isset($_GET['code']))
    {
        $_SESSION['GaccessToken'] = $Gclient->authenticate($_GET['code']);
        //header('Loaction : GDriveUpload.php');
        //exit();
    }
    elseif(!isset($_SESSION['GaccessToken']))
    {
        $Gclient->authenticate();
    }
    
?>
