<?php
    require_once 'Config.php';
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
        $images[] = $photo['images'][0]['source'];
    }
    echo json_encode($images);
?>
