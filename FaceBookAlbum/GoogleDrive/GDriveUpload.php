<?php

    spl_autoload_register();
    require_once 'Config.php';
    //increase maximum execution time
    ini_set('max_execution_time', 900);
    if(isset($_GET['state'])) {

        $_SESSION['FBRLH_state'] = $_GET['state'];
    }

    $accessToken = $_SESSION['facebookAccessToken'];
    //echo $accessToken;

    if(isset($_GET['id']))
    {
        $_SESSION['album_id'] = $_GET['id'];
    }
    $userAlbumId = $_SESSION['album_id'];
    //echo $userAlbumId;

    require_once 'GoogleConfig/vendor/autoload.php';

    $url_array = explode('?', 'http://'.$_SERVER ['HTTP_HOST'].$_SERVER['REQUEST_URI']);

    $client = new Google_Client(); //create class for google clent
    
	$client->setClientId('818891243832-g8prnv3qfskd4fvgndf4512t06fjru45.apps.googleusercontent.com');//client id
	$client->setClientSecret('tlFxfsf1nAR8ysx8Gk_3h5F2');//clent secret
	$client->setRedirectUri($url_array[0]);
	$client->addScope(Google_Service_Drive::DRIVE);
	$client->setScopes(array('https://www.googleapis.com/auth/drive'));
	if(isset($_GET['code']))
	{
		$client->authenticate($_GET['code']);
		$access_token = $client->getAccessToken();
		$client->setAccessToken($access_token);
	}
	else
	{
		$auth_url = $client->createAuthUrl();
		header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
	}
		
	$service = new Google_Service_Drive($client);
    $finfo = finfo_open(FILEINFO_MIME_TYPE);

    // $dir = 'FACEBOOK_ALBUM_DOWNLOADER';
    // $fileMetadata = new Google_Service_Drive_DriveFile(array(
    //     'name' => $dir,
    //     'mimeType' => 'application/vnd.google-apps.folder'));
    //     $file = $service->files->create($fileMetadata, array(
    //         'fields' => 'id'));
        
    //printf("Folder ID: %s\n", $file->id);
    
    $url = "https://graph.facebook.com/v3.1/me?fields=id,name,albums{id,count,name,cover_photo,photo_count,photos{picture,likes{id}},likes,link},likes&access_token=".$accessToken;
    $result = file_get_contents($url);
    $album = json_decode($result);
    
    foreach($album->albums->data as $userdata)
    {
        if($userdata->id == $userAlbumId)
        {
            $albumId = $userAlbumId;
            $response = $FB->get('/'.$userAlbumId.'/?fields=name',$_SESSION['facebookAccessToken']);
            $album = $response->getGraphNode()->asArray();
            $albumname = $album['name'];
            //echo $albumname;

            //google drive folder
            $fileMetadata = new Google_Service_Drive_DriveFile(array(
                'name' => $albumname,
                'mimeType' => 'application/vnd.google-apps.folder'));
                $file = $service->files->create($fileMetadata, array(
                'fields' => 'id'));
                 $folder_id = $file->id;

            //fetch images album wise
            $url = "https://graph.facebook.com/v3.1/".$albumId."/photos?fields=name,images%2Calbum&access_token=".$accessToken;
            $result = file_get_contents($url);
            $pic = json_decode($result);
            $existphotokey = (array)$pic;
            $a=(array)$pic->paging;
            if(array_key_exists("next",$a))
            {
                $url=$a["next"];
            }
            //paging
                do{
                    if(array_key_exists("next",$a))
                    {
                        $url=$a["next"];
                    }
                    else{
                        $url="none";
                    }
                    foreach($pic->data as $mydata)
                    {
                        $url1=$mydata->images[1]->source;
                        // $photoId=$mydata->id;
                        // $file=$path.$photoId.'.jpg';
                        // file_put_contents($file,file_get_contents($url1));
                        $imgurl = rand(1,999999).rand(1,999999).'.jpg';

                            //moving albums to the drive

                            $fileMetadata = new Google_Service_Drive_DriveFile(array(
                                'name' => $imgurl,
                                'parents' => array($folder_id)
                                ));
                                $content = file_get_contents($url1);
                                $file = $service->files->create($fileMetadata, array(
                                'data' => $content,
                                'mimeType' => 'image/jpeg',
                                'uploadType' => 'multipart',
                                'fields' => 'id'));
                    }
                    if($url!="none")
                    {
                        $result=file_get_contents($url);
                    }
                    $pic=json_decode($result);
                    $existphotokey=(array)$pic;
                    $a=(array)$pic->paging;
                }while($url!="none");
        }
    }
    finfo_close($finfo);
    header('Location : Album.php');
    exit();
    
    // $album_id = $_GET['id'];
    // echo $album_id;
    // //picture,images,name,created_time&limit=100
    // $photos = $FB->get("/{$album_id}/photos?fields=picture,images,name&limit=600", $accessToken)->getGraphEdge()->asArray();
    // $images=array();

    // foreach($photos as $photo)
    // {
    //     $images[] = $photo['images'][0]['source'];
    // }

    // echo json_encode($images);

?>
