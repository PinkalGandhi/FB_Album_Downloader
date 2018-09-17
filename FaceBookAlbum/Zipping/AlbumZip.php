<?php

    spl_autoload_register();
    require_once 'Config.php';
    require_once 'zip.php';
    //increase maximum execution time
    ini_set('max_execution_time', 900);
    if(isset($_GET['state'])) {

        $_SESSION['FBRLH_state'] = $_GET['state'];
    }

    $accessToken = $_SESSION['facebookAccessToken'];

    $url = "https://graph.facebook.com/v3.1/me?fields=id,name,albums{id,count,name,cover_photo,photo_count,photos{picture,likes{id}},likes,link},likes&access_token=".$accessToken;
    $result = file_get_contents($url);
    $album = json_decode($result);

    //getting id of a album
    $album_id = $_GET['id'];
    //echo $album_id;
    
    //ZIP CODE 
    $temp_directory = __DIR__.'/Download/';

    if(!is_dir($temp_directory))
    {
        mkdir($temp_directory,0777);
    }
    
    foreach($album->albums->data as $userdata)
    {
        try
        {
            if($userdata->id == $album_id)
            {
                $userAlbumId = $album_id;
                //echo $userAlbumId;
                $response = $FB->get('/'.$userAlbumId.'/?fields=name',$_SESSION['facebookAccessToken']);
                $album = $response->getGraphNode()->asArray();
                $albumname = $album['name'];
                //echo $albumname;

                //fetch images album wise
                $url = "https://graph.facebook.com/v3.1/".$userAlbumId."/photos?fields=name,images%2Calbum&access_token=".$accessToken;
                $result = file_get_contents($url);
                $pic = json_decode($result);
                $existphotokey = (array)$pic;
                $a=(array)$pic->paging;
                if(array_key_exists("next",$a))
                {
                    $url=$a["next"];
                }
                $path = $temp_directory.$albumname.'/';
                if(!is_dir($path))
                {
                    mkdir($path,0777);
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
                            $photoId=$mydata->id;
                            $file=$path.$photoId.'.jpg';
                            file_put_contents($file,file_get_contents($url1));
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
    
    //making zip

    $zip_name='FaceBookAlbum.zip';
    $zip_directory='/';

    $zip = new zip($zip_name,$zip_directory);
    $dir = 'Download';
    $zip->add_directory($dir);
    $zip->save();

    $zip_path=$zip->get_zip_path();

    //Download ZIP File
    header("Pragma:public");
    header("Expires:0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: public");
    header("Content-Description: File Transfer");
    header("Content-Type: application/zip");
    header("Content-Disposition: attachment; filename=\"" . $zip_name . "\"");
    header("Content-Transfer-Encoding: binary");
    header("Content-Length:" .filesize($zip_path));

    //read ZIP file
    readfile($zip_path);

    //recursively remove all directory
    $zip->removeRecursive($dir.'/');

?>  
