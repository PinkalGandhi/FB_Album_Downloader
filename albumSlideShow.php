<?php
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
    //echo $album_id;
    
    //getting album photos for specific album
    $photos = $FB->get("/{$album_id}/photos?fields=picture&limit=500", $accessToken)->getGraphEdge()->asArray();
    //var_dump($photos);

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
</head>
<body style="padding:0px; margin:0px; background-color:#fff;font-family:arial,helvetica,sans-serif,verdana,'Open Sans'">

    <!-- #region Jssor Slider Begin -->
    <!-- Generator: Jssor Slider Maker -->
    <!-- Source: https://www.jssor.com -->
    <script src="js/jssor.slider-27.4.0.min.js" type="text/javascript"></script>
    <script type="text/javascript">
        
        jssor_1_slider_init = function() {

            var jssor_1_SlideshowTransitions = [
              {$Duration:800,x:-0.3,$During:{$Left:[0.3,0.7]},$Easing:{$Left:$Jease$.$InCubic,$Opacity:$Jease$.$Linear},$Opacity:2},
              {$Duration:800,x:0.3,$SlideOut:true,$Easing:{$Left:$Jease$.$InCubic,$Opacity:$Jease$.$Linear},$Opacity:2}
            ];

            var jssor_1_options = {
              $AutoPlay: 1,
              $SlideshowOptions: {
                $Class: $JssorSlideshowRunner$,
                $Transitions: jssor_1_SlideshowTransitions,
                $TransitionsOrder: 1
              },
              $ArrowNavigatorOptions: {
                $Class: $JssorArrowNavigator$
              },
              $ThumbnailNavigatorOptions: {
                $Class: $JssorThumbnailNavigator$,
                $Orientation: 2,
                $NoDrag: true
              }
            };

            var jssor_1_slider = new $JssorSlider$("jssor_1", jssor_1_options);

            /*#region responsive code begin*/

            var MAX_WIDTH = 600;
            //slider function
            function ScaleSlider() {
                var containerElement = jssor_1_slider.$Elmt.parentNode;
                var containerWidth = containerElement.clientWidth;

                if (containerWidth) {

                    var expectedWidth = Math.min(MAX_WIDTH || containerWidth, containerWidth);

                    jssor_1_slider.$ScaleWidth(expectedWidth);
                }
                else {
                    window.setTimeout(ScaleSlider, 2000);
                }
            }

            ScaleSlider();

            $Jssor$.$AddEvent(window, "load", ScaleSlider);
            $Jssor$.$AddEvent(window, "resize", ScaleSlider);
            $Jssor$.$AddEvent(window, "orientationchange", ScaleSlider);
            /*#endregion responsive code end*/
        };
    </script>
    <style>
        img[alt="www.000webhost.com"]{display:none;}
        /*jssor slider loading skin spin css*/
        .jssorl-009-spin img {
            animation-name: jssorl-009-spin;
            animation-duration: 1.6s;
            animation-iteration-count: infinite;
            animation-timing-function: linear;
        }

        @keyframes jssorl-009-spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .jssora061 {display:block;position:absolute;cursor:pointer;}
        .jssora061 .a {fill:none;stroke:#fff;stroke-width:360;stroke-linecap:round;}
        .jssora061:hover {opacity:.8;}
        .jssora061.jssora061dn {opacity:.5;}
        .jssora061.jssora061ds {opacity:.3;pointer-events:none;}
    </style>
    
    <div id="jssor_1" style="position:relative;margin:0 auto;top:30px;left:0px;width:600px;height:350px;overflow:hidden;visibility:hidden;">
        
        <div data-u="slides" style="cursor:default;position:relative;top:0px;left:0px;width:700px;margin-left:20px; height:380px;overflow:hidden;">
            <?php
            
                //for loop to display images
                foreach($photos as $photo)
                {
            ?>
            <div>
                <img data-u="image" src="<?php echo $photo['picture']; ?>" alt="Image Not Found" />
            </div>
            <?php
                }
            ?>
        </div>
    </div>
    <script type="text/javascript">jssor_1_slider_init();</script>
    <!-- #endregion Jssor Slider End -->
</body>
</html>
