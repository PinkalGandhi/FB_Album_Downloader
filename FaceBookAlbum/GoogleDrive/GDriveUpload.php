<?php
  
    require_once 'GoogleConfig.php';

    if(isset($_GET['state'])) {

        $_SESSION['FBRLH_state'] = $_GET['state'];
    }

    $accessToken = $_SESSION['facebookAccessToken'];

    if(!isset($accessToken))
    {
        header('Location : index.php');
    }
    
    $album_id = $_GET['id'];
    //echo $album_id;
    //picture,images,name,created_time&limit=100
    $photos = $FB->get("/{$album_id}/photos?fields=picture,images,name&limit=600", $accessToken)->getGraphEdge()->asArray();
    $images=array();

    foreach($photos as $photo)
    {
        array_push($images,$photo['images'][0]['source']);
    }
    //echo json_encode($images);

    $service = new Google_Service_Drive($client);
	$finfo = finfo_open(FILEINFO_MIME_TYPE);
		
    //if check directory name exist or not on google drive if not exist the create directory
    $file = new Google_Service_Drive_DriveFile();
    if(in_array("FaceBook Album Downloder",$foldername))
    {
        $folderId =$foldername_and_id['FaceBook Album Downloder'] ;
    
    }
    else
    {
        $fileMetadata = new Google_Service_Drive_DriveFile(array(
			'name' => 'FaceBookAlbums',
			'mimeType' => 'application/vnd.google-apps.folder'));
			
			$file = $service->files->create($fileMetadata, array(
			'fields' => 'id'));
			$folderId = $file->id;
		
    }

    if(isset($album_id))
    {
        foreach($album_id as $albumID)
        {
            $id = $albumID;
            $getAlbumname = $fb->get('/'.$id.'?fields=name,photos.limit(100){images,name,created_time}',$_SESSION['facebookAccessToken'])->getGraphEdge()->asArray();
            $folder_name = $getAlbumname['name'];

            //creating google directory on google drive

            $fileMetadata = new Google_Service_Drive_DriveFile(array(
				'name' => $folder_name,
				'parents' => array($folderId),
				'mimeType' => 'application/vnd.google-apps.folder'));
				
				$file = $service->files->create($fileMetadata, array(
				'fields' => 'id'));
                $folderid = $file->id;
                
            //fetching images from the album

            $url1="https://graph.facebook.com/v3.1/".$id."/photos?fields=images%2Calbum&access_token=".$fbAccessToken;
            $result = file_get_contents($url1);
            $pic=json_decode($result);
            $existphotokey=(array)$pic;
            $a=(array)$pic->paging;

            if(array_key_exists("next",$a))
            {
                $url1=$a["next"];
            }
            do
            {
                if(array_key_exists("next",$a))
                {
                    $url1=$a["next"];
                }
                else{
                    $url1 = "none";
                }

                foreach($pic->data as $mydata)
                {
                    $url = $mydata->images[0]->source;
                    $img = rand(1,9999999).rand(1,99999999).'.jpg';
                    $fileMetadata = new Google_Service_Drive_DriveFile(array(
                        'name' => $img,
                        'parents' => array($folderid)
                        ));
                        $content = file_get_contents($url);
                        $file = $service->files->create($fileMetadata, array(
                        'data' => $content,
                        'mimeType' => 'image/jpeg',
                        'uploadType' => 'multipart',
                        'fields' => 'id'));	
                }
                if($url1!="none")
                {
                    $result = file_get_contents($url1);
                }
                $pic = json_decode($result);
                $existphotokey = (array)$pic;
                $a = (array)$pic->paging;
            }while($url1!="none");
        }
    }
    finfo_close($finfo);
    header('Location : Album.php');
    exit();

?>
