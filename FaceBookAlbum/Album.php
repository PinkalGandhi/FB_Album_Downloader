<?php

    require_once 'Config.php';
    
    if(isset($_GET['state'])) {

        $_SESSION['FBRLH_state'] = $_GET['state'];
    }

    $accessToken = $_SESSION['facebookAccessToken'];

    if(isset($accessToken))
    {
        try{
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

    $url = "https://graph.facebook.com/v3.1/me?fields=id,name,albums{id,count,name,cover_photo,photo_count,photos{picture,likes{id}},likes,link},likes&access_token=".$accessToken;
    $result = file_get_contents($url);
    $album = json_decode($result);
    //var_dump($album);


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

    <style>
        *{
            margin:0;
            padding:0;
        }
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
        
    </style>

    <script>
        var id;
        var imageurl;
        var imagecount=0;
        function openSlideShowWindow(aid)
        {
            document.getElementById("box1").style.display="block";
            document.getElementById("loaddiv").style.display="block";
           
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
             if (this.readyState == 4 && this.status == 200) {
                document.getElementById("loaddiv").style.display="none";
                document.getElementById("imageslideshow").style.display="block";
                
                imageurl=JSON.parse(this.responseText);
                imagecount=imageurl.length-1;
                onloadimage();
                }
            };
            xhttp.open("GET", "albumSlideShow.php?id=" + aid, true);
            xhttp.send();
        }
        function onloadimage()
        {
            
            document.getElementById("slideimage").src=imageurl[0];
            setInterval(next, 3000);    
        }
        var i=0;
        function next()
        {
            if(i < imagecount)
            {
                i++;
                document.getElementById("slideimage").src=imageurl[i];
            }
            else{
                i=0;
                document.getElementById("slideimage").src=imageurl[i];
            }
        }
        function prev()
        {
            if(i>0)
            {
            
                i--;
                document.getElementById("slideimage").src=imageurl[i];
        
            }
            else{
                i=imagecount;
                document.getElementById("slideimage").src=imageurl[i];
        
            }
        }
        function closediv()
        {
            document.getElementById("box1").style.display="none";
            document.getElementById("loaddiv").style.display="none";
        }

    </script>
<style>
    #box1{
        background:rgba(0,0,0,0.9);
        height:100%;
        width:100%;
        position:fixed;
        z-index:99999;
        display:none;
    }
    #loaddiv
    {
        display:none;
    }
    #imageslideshow
    {
        position:absolute;
        margin-left:160px;
        margin-top:85px;
        display:none;
    }
    .Loadingimg{
        height:150px;
        width:150px;
        position:absolute;
        margin-left:620px;
        margin-top:245px;
        display:block;
    }
    .crossimg{
        height:30px;
        width:30px;
    }
    .prevbtn{
        height:60px;
        width:60px;
    }
</style>
</head>

<body class="site2" data-spy="scroll" data-target=".mainmenu-area">
    <form method="POST" name="album" id="album">
     <!-- Album Slide Show -->
     <center>
    <div id="box1">
        <p id="loaddiv"><img src="images/FacebookGIF.gif" class="Loadingimg" alt="Not Found"/> </p>
       
        <div id="imageslideshow">
            <img src="" id="slideimage" style="height:500px; width:1000px;"/>
        </div>
        <div id="closebtn" style="margin-left:74%;"><button onclick="closediv()" style="margin-top:90px;position:absolute;color:white;background:white;border:1px solid;border-color:white;"><img src="images/Cross.png" class="crossimg" /></button></div>
        <div id="prev" style="margin-right:87%;"><button onclick="prev()" style="margin-top:300px;position:absolute;color:white;background:white;border:none;border-color:white;"><img src="images/prev.png" alt="Inmage Not Found" class="prevbtn"/></button></div>
        <div id="prev" style="margin-left:73.4%;"><button onclick="next()" style="margin-top:300px;position:absolute;color:white;background:white;border:none;border-color:white;"><img src="images/next.png" alt="Inmage Not Found" class="prevbtn"/></button></div>
   </div>
   </center>
   <!-- Ending Slide Show -->
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
                            <!-- <li><a id="selected_album" name="selected_album" style="cursor:pointer;"> ZIP ALBUM </a></li> -->
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
                <div class="col-xs-12 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
                    <div class="text-center">
                        <h1 class="title"><?php echo $username."'s  Albums"; ?></h1>
                    </div>
                </div>
            </div>

            <div class="row">
            <?php
                foreach($album->albums->data as $usersdata)
                {
                    foreach($userpicture->albums->data as $userdata1)
                    {
                        if($userdata1->id==$usersdata->id)
                        { 
                            $albumID = $usersdata->id;
                            $albumname =  $usersdata->name;
                            //$a = 123;
            ?>  
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <div class="team-box two wow fadeInUp" data-wow-delay="0.2s">
                                    <div class="team-image imagemargin">
                                            <img src="<?php echo $userdata1->picture->data->url; ?>" alt="Image Not Found" style="height:100% width:100%"/>
                                    </div>
                                    <div class="team-hover text-center">
                                        <h2 class="t_title"><?php echo $albumname; ?></h2>
                                        <h2 class="t_title"> <?php echo "Photos : : ".$usersdata->photo_count ?></h2>
                                        <br/>
                                        <div class="team-social-menu">
                                            <a href='AlbumZip.php?id=<?php echo $albumID; ?>'> <img src="images/filezip1.png" title="Album ZIP"  class="zipimg" name="$albumID" /> </a>
                                            <?php echo "<a href='#' onclick=openSlideShowWindow('$albumID');>"?> <img src="images/ui-image.svg" title="Album Preview" class="albmimg" name="$albumID" /> </a>
                                            <a href='GDriveUpload.php?id=<?php echo $albumID; ?>'><img src="images/uploadImage.png" alt="Image Not Found" title="Google Drive" class="imgupload" /></a>
                                            <!-- <a><input type="checkbox" name="album[]" value="<?php echo $usersdata->id; ?>" class="selectalbum calbumidval" /> </a> -->
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

    </form>
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

    <script type="text/javascript">

        var checkboxes = document.getElementsByClassName("calbumidval");

        $('#selected_album').click(function(){
            var form = document.getElementById("album")
            form.action = "Selected_album.php";
            var count = 0;

            for(i=0;i<checkboxes.length;i++)
            {
                if(checkboxes[i].checked==true)
                {
                    count++;
                }
            }
            if(count==0)
            {
                alert('Please,Select the Album');
            }
            else
            {
                form.submit();
            }
        });

    </script>
    
</body>

</html>
