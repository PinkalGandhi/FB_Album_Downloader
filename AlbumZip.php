<?php
    
    //facebook configuration
    require_once 'Config.php';

    if(isset($_GET['state'])) {

        $_SESSION['FBRLH_state'] = $_GET['state'];
    }

    $accessToken = $_SESSION['facebookAccessToken'];

    if(isset($accessToken))
    {
        try{
            $response = $FB->get('me/?fields=id,name,friends,albums{id,count,name,cover_photo,photo_count,photos.limit(300){picture,likes{id}},likes,link},likes',$accessToken);
            $userData = $response->getGraphNode()->asArray();
            $_SESSION['userData'] = $userData;
            $_SESSION['access_Token'] = (string) $accessToken;

            $fb_user = $response->getGraphUser();

            $userid = $fb_user['id'];
            $username = $fb_user['name'];
            
            $str = "http://graph.facebook.com/".$userid."/picture?type=large";
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
    }

    $url = "https://graph.facebook.com/v3.1/me?fields=id,name,albums{id,count,name,cover_photo,photo_count,photos.limit(500){picture,likes{id}},likes,link},likes&access_token=".$accessToken;
    $result = file_get_contents($url);
    $album = json_decode($result);
    //$album->albums->data

    $url1 = "https://graph.facebook.com/v3.1/me?fields=albums%7Bpicture%7D&access_token=".$accessToken;
    $result = file_get_contents($url1);
    $userpicture = json_decode($result);
    //$userpicture->albums->data

    $album_id = $_GET['id'];
    echo $album_id;

    $photos = $FB->get("/{$album_id}/photos?fields=picture", $accessToken)->getGraphEdge()->asArray();

    
    $nm = array();

    foreach($album->albums->data as $albumnm)
    {
        if($albumnm->id==$album_id)
        {
            $nm[0] = $albumnm->name;
        }
    }
    $album_name = implode($nm); //array to string conversion
    //echo $album_name;
    //var_dump($album_name);

    $x = array();
    $index= 0;
    foreach($photos as $aphoto)
    {
        $x[$index] = $aphoto['picture'];
        $index++;
    }

    // echo "<pre>";
    // var_dump($albumimages);
    // echo "</pre>";
   
    //ZIP CODE 
    $zip_folder="";
    $temp_directory = __DIR__.'/AlbumZIP/';
    $zipFileName = $album_name.'.zip';
    
    $path = $temp_directory.$zipFileName;
    
    //ZIP function
    function creating_zip($temp_directory,$zipFileName,$albumimages,$album_name)
    {
        if(!is_dir($temp_directory))
        {
            mkdir($temp_directory,0777);
        }
        if(!is_dir($temp_directory.$album_name))
        {
            mkdir($temp_directory.$album_name,0777);
        }
        foreach($albumimages as $image)
        {   
            $src=$temp_directory.$album_name."/".rand(1,9999).".jpg";
            file_put_contents($src,file_get_contents($image));
        }
    }
     creating_zip($temp_directory,$zipFileName,$x,$album_name);
        $zip = new ZipArchive();
        $zip->open("AlbumZIP/".$zipFileName,ZipArchive::CREATE);
        $files= scandir($temp_directory.$album_name);
          unset($files[0],$files[1]);
        foreach ($files as $file) {
            $zip->addFile("AlbumZIP/".$album_name."/".$file);    
        }
    
        $zip->close();
        echo "Zipping is done and store on the webhost folder";
     
?> 

<html>
    <head>
        <style>
            img[alt="www.000webhost.com"]{display:none;}
        </style>
    </head>
    <body></body>
</html>