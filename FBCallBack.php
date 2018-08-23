<?php

    //facebook configuration
    require_once 'Config.php';
    
    if(isset($_GET['state'])) {
        $_SESSION['FBRLH_state'] = $_GET['state'];
    }

    try{
        //try to get accessToken
        $accessToken = $helper->getAccessToken();
    }
    catch(Facebook\Exceptions\FacebookResponseException $e)
    {
        //when graph returned an error
        echo "Graph returned an Error : : ".$e->getMessage();
        exit();
    }
    catch(Facebook\Exception\FacebookSDKException $e)
    {
        //when validation fails or other local issues
        echo "FaceBook SDK returned an Error : : ".$e->getMessage();
        exit();
    }

    if(!$accessToken)
    {
        header('Location: https://pinkalgandhi16.000webhostapp.com/index.php');
        exit();
    }

    if(!isset($accessToken))
    {
        if($helper->getError())
        {
            header('HTTP/1.0 401 Unauthorized');
            echo "Error : : ".$helper->getError()."<br/>";
            echo "Error Code : : ".$helper->getErrorCode()."<br/>";
            echo "Error Reason : :".$helper->getErrorReason()."<br/>";
            echo "Error Description : :".$helper->getErrorDescription()."<br/>";
        }
        else{
            header('HTTP/1.0 400 Bad Request');
            echo "BAD REQUEST";
        }
        exit();
    }

    //Logged In
    // echo "Access Token : : ";
    // var_dump($accessToken->getValue());

    // The OAuth 2.0 client handler helps us manage access tokens
    $oAuth2Client = $FB->getOAuth2Client();

    $tokenMetaData = $oAuth2Client->debugToken($accessToken);

    $tokenMetaData->validateAppId('142201069920240');

    $tokenMetaData->validateExpiration();

    //long lived accesstoken
    if(!$accessToken->isLongLived())
    {
        try{
            $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
        }
        catch(FacebookSDKException $e)
        {
            echo "Error when getting Long-Lived Access token : : ".$e->getMessage()."<br/>";
            exit();
        }

        //var_dum($accessToken->getValue());
    }

    $_SESSION['facebookAccessToken'] = (string) $accessToken;

    header('Location: https://pinkalgandhi16.000webhostapp.com/Album.php');

?>