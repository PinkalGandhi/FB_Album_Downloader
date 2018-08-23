<?php
    
//Login Page & config facebook    
   require_once 'Config.php';
   if(isset($_GET['state'])) {
    $_SESSION['FBRLH_state'] = $_GET['state'];
    }
    //facebook call back url
   $loginURL = $helper->getLoginUrl('https://pinkalgandhi16.000webhostapp.com/FBCallBack.php',$FBpermissions);
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
        .btnlogin{
            margin-top:50px;
            color:black;
            font-weight:bold;
            font-size:35px;
        }
        .txtheader{
            font-size:20px;
            color:white;
        }   
        .btnlogin{
            font-size:23px;
            background-color:skyblue;
            height:40px;
            width:300px;
            color:black;
            border-radius:4px;
            border:unset;
        }     
        .btnlogin:hover{
            background-color:white;
        }
        .bacgroundimg{
            background-image :url('images/fb01.jpg');
            width: 100%;
            height: 100%;
            position:absolute;
        }
        .fbgif1{
            height:50px;
            width:50px;
        }
    </style>
</head>

<body class="site2" data-spy="scroll" data-target=".mainmenu-area">
    <!-- Mainmenu-Area -->
    <nav class="navbar mainmenu-area" data-spy="affix" data-offset-top="197">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="navbar-header smoth"> 
                        <img src="images/gif_like.gif" class="fbgif1" alt="GIF NOT FOUND" />
                        <lable class="txtheader" value="FaceBook Album Downloader" name="fbtxt">FaceBook Album Downloader</lable>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!--Header-Area-->
    <header class="bacgroundimg overlay">
        <div class="vcenter">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2 text-center">
                        <div class="header-text">
                            <div class="wow fadeInUp upper latter-space txtfb" data-wow-delay="0.2s"> "Bring The World Closer Together" </div>
                            <!-- <div class="wow fadeInUp" data-wow-delay="0.7s">
                                <a href="<?php echo htmlspecialchars($loginURL) ?>" class="bttn bttn-lg bttn-primary btnlogin">Log In With FaceBook</a>
                            </div> -->

                            <div>
                                <a href="<?php echo htmlspecialchars($loginURL) ?>">
                                    <input type="button" value="Log In With FaceBook" class="btnlogin"/>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!--Header-Area-/-->

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