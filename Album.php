<?php
    
    //facebook configuration
    require_once 'Config.php';
    
    if(isset($_GET['state'])) {

        $_SESSION['FBRLH_state'] = $_GET['state'];
    }

    //accessToken 
    $accessToken = $_SESSION['facebookAccessToken'];

    if(isset($accessToken))
    {
        try{
            //try to get facebook albums,id,name,pictures.
            $response = $FB->get('me/?fields=id,name,friends,albums{id,count,name,cover_photo,photo_count,photos{picture,likes{id}},likes,link},likes',$accessToken);
            $userData = $response->getGraphNode()->asArray();
            $_SESSION['userData'] = $userData;
            $_SESSION['access_Token'] = (string) $accessToken;

            $fb_user = $response->getGraphUser();

            $userid = $fb_user['id'];
            $username = $fb_user['name'];
            
            $str = "http://graph.facebook.com/".$userid."/picture?type=large";

            //var_dump($fb_user);
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
    
    //try to get albums
    $url = "https://graph.facebook.com/v3.1/me?fields=id,name,albums{id,count,name,cover_photo,photo_count,photos{picture,likes{id}},likes,link},likes&access_token=".$accessToken;
    $result = file_get_contents($url);
    $album = json_decode($result);
    //var_dump($album);

    //try to get photos of albums
    $url1 = "https://graph.facebook.com/v3.1/me?fields=albums%7Bpicture%7D&access_token=".$accessToken;
    $result = file_get_contents($url1);
    $userpicture = json_decode($result);
    //var_dump($userpicture);

    // $photos = $FB->get("/{$album_id}/photos?fields=picture", $accessToken)->getGraphEdge()->asArray();
?>

<!doctype html>
<html class="no-js" lang="zxx">

<head>
    <meta charset="utf-8">
    <meta name="author" content="John Doe">
    <meta name="description" content="">
    <meta name="keywords" content="HTML,CSS,XML,JavaScript">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="text/html; charset=iso-8859-2" http-equiv="Content-Type">
    <!-- Title -->
    <title>FaceBook</title>
    <!-- Place favicon.ico in the root directory -->
    <link rel="icon" type="image/png" href="images/fb1.png"/>
    <!-- Plugin-CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <link rel="stylesheet" href="css/icofont.css">
    <link rel="stylesheet" href="css/magnific-popup.css">
    <link rel="stylesheet" href="css/animate.css">
    <!-- Main-Stylesheets -->
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="css/responsive.css">
    <script src="js/vendor/modernizr-2.8.3.min.js"></script>
    <!-- Slide Show Link -->
    <link href="Slider/ninja-slider.css" rel="stylesheet" type="text/css" />
    <script src="Slider/ninja-slider.js" type="text/javascript"></script>
    
    <!-- style sheet -->
    <style>
        *{
            margin:0;
            padding:0;
        }
        img[alt="www.000webhost.com"]{display:none;}
        .txtfb{
            font-size:20px;
            color:white;
        }
        .txtheader{
            font-size:20px;
            color:white;
        }   
        .fbgif1{
            height:50px;
            width:50px;
        }
        .usernm{
            margin-left:0px;
            font-size:22px;
            color:black;
            font-weight:bold;
        }
        .albumlink{
            font-size:22px;
            color:white;
            margin-left:300px;
        }
        .logoutlink{
            font-size:22px;
            color:white;
        }
        .line{
            color:black;
            font-weight:bold;
            font-size:22px;
        }
        .drivelogo{
            height:20px;
            width:20px;
        }
        .ziplogo{
            height:20px;
            width:20px;
        }
        .albmimg{
            height:22px;
            width:22px;
        }
        .zipimg{
            height:30px;
            width:30px;
            
        }
        .imgupload{
            height:25px;
            width:25px;
        }
        .txtusernm{
            color:black;
        }
        
    </style>

    <script>
        //slideshow script
        function openSlideShowWindow(accessToken,aid)
        {
            alert(aid);
            str="albumSlideShow.php?token="+accessToken+"&id="+aid;
            window.open(str, "_blank","height=400px width=980px");

        }
        
        //albumZip script
        function albumZip(accessToken,aid)
        {
            alert(accessToken+aid);
            url="AlbumZip.php?token="+accessToken+"&id="+aid;
            window.open(url, "_blank","height=150px width=500px");
        }
        //Google Drive
        function google_drive(albumid)
        {   
                alert(albumid);
                url="GoogleConfig.php?id="+albumid;
                
        }
    </script>

</head>

<body class="site2" data-spy="scroll" data-target=".mainmenu-area">
    <!-- Mainmenu-Area -->
    <nav class="navbar mainmenu-area" data-spy="affix" data-offset-top="197">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <div id="search-box" class="collapse">
                            <form action="#">
                                <input type="search" class="form-control" placeholder="What do you want to know?">
                            </form>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12">
                    <div class="navbar-header smoth">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#mainmenu">
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span> 
                        </button>
                        
                        <img src="images/gif_like.gif" class="fbgif1" alt="GIF NOT FOUND" />
                        <lable class="txtheader" value="FaceBook Album Downloader" name="fbtxt">FaceBook Album Downloader</lable>
                        
                    </div>

                    <div class="collapse navbar-collapse navbar-right" id="mainmenu">
                        <ul class="nav navbar-nav primary-menu txt">
                            <li><a href="#"> <?php echo $username; ?> </a></li>
                            <li><a href="LogOut.php">Logout</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    <!-- Mainmenu-Area-/ -->

    <!-- User Profile -->
    <section class="section-padding" id="team-area">
        <div class="container">
            <div class="row">
            <?php
                
                //for loop for getting photos ,id,name
                foreach($album->albums->data as $usersdata)
                {
                    foreach($userpicture->albums->data as $userdata1)
                    {
                        if($userdata1->id==$usersdata->id)
                        { 
                            $albumID = $usersdata->id;
                            $albumname =  $usersdata->name;
                            
            ?>  
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <div class="team-box two wow fadeInUp" data-wow-delay="0.2s">
                                    <div class="team-image imagemargin">
                                            <img src="<?php echo $userdata1->picture->data->url; ?>" alt="Image Not Found"/>
                                    </div>
                                    <div class="team-hover text-center">
                                        <h2 class="t_title"><?php echo $albumname; ?></h2>
                                        <h2 class="t_title"> <?php echo "Photos : : ".$usersdata->photo_count ?></h2>
                                        <br/>
                                        <div class="team-social-menu">
                                            <?php echo "<a href='#' onclick=albumZip('$accessToken','$albumID');>"?> <img src="images/filezip1.png"  class="zipimg" name="$albumID" /> </a>
                                            <?php echo "<a href='#' onclick=openSlideShowWindow('$accessToken','$albumID');>"?> <img src="images/ui-image.svg" class="albmimg" name="$albumID" /> </a>
                                            <?php echo "<a href='#' onclick=google_drive('$albumID');>"?> <img src="images/uploadImage.png"  alt="Image Not Found" class="imgupload" /></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                <?php

                        }
                    
                    }
                }
                ?>
            </div>
        </div>
    </section>
    <!-- User Profile / -->

    <!--Vendor-JS-->
    <script src="js/vendor/jquery-1.12.4.min.js"></script>
    <script src="js/vendor/bootstrap.min.js"></script>
    <!--Plugin-JS-->
    <script src="js/owl.carousel.min.js"></script>
    <script src="js/appear.js"></script>
    <script src="js/bars.js"></script>
    <script src="js/waypoints.min.js"></script>
    <script src="js/counterup.min.js"></script>
    <script src="js/easypiechart.min.js"></script>
    <script src="js/mixitup.min.js"></script>
    <script src="js/contact-form.js"></script>
    <script src="js/scrollUp.min.js"></script>
    <script src="js/magnific-popup.min.js"></script>
    <script src="js/wow.min.js"></script>
    <!--Main-active-JS-->
    <script src="js/main.js"></script>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDXZ3vJtdK6aKAEWBovZFe4YKj1SGo9V20&callback=initMap"></script>
    <script src="js/maps.js"></script>
</body>

</html>