<?php
require 'constant.php';
$apdata = appdetail();
$cat = catList();
?>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>I am Punjabi</title>
        <meta name="description" content="">
        <meta name="author" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <link rel="stylesheet" href="css/base.css" />
        <link rel="stylesheet" href="css/skeleton.css" />
        <link rel="stylesheet" href="css/layout.css" />
        <link rel="stylesheet" href="css/style.css" />
        <link rel="stylesheet" href="css/player.css" />
        <link rel="stylesheet" href="css/fancybox.css" />
        <link rel="stylesheet" type="text/css" href="css/color.css" title="styles7" media="screen" />
        <link rel="alternate stylesheet" type="text/css" href="css/red.css" title="styles1" media="screen" />
        <link rel="alternate stylesheet" type="text/css" href="css/blue.css" title="styles2" media="screen" />
        <link rel="alternate stylesheet" type="text/css" href="css/gray.css" title="styles3" media="screen" />
        <link rel="alternate stylesheet" type="text/css" href="css/orange.css" title="styles4" media="screen" />
        <link rel="alternate stylesheet" type="text/css" href="css/green.css" title="styles5" media="screen" />
        <link rel="alternate stylesheet" type="text/css" href="css/orange-red.css" title="styles6" media="screen" />
        <link rel="shortcut icon" href="images/favicon.ico" />
        <link rel="rockit-touch-icon" href="images/rockit-touch-icon.png" />
        <link rel="rockit-touch-icon" sizes="72x72" href="images/rockit-touch-icon-72x72.png" />
        <link rel="rockit-touch-icon" sizes="114x114" href="images/rockit-touch-icon-114x114.png" />
        <script type="text/javascript" src="js/jquery.min.js"></script>
        <script type="text/javascript" src="js/styleswitch.js"></script>
        <script type="text/javascript" src="js/jquery.infinite-carousel.js"></script>
        <script type="text/javascript" src="js/animatedcollapse.js"></script>
        <script type="text/javascript" src="js/ddsmoothmenu.js"></script>
        <script type="text/javascript" src="js/jquery.nivo.slider.js"></script>
        <script type="text/javascript" src="js/scrolltopcontrol.js"></script>
        <script type="text/javascript" src="js/tabs.js"></script>
        <script type="text/javascript" src="js/jquery.countdown.js"></script>
        <script type="text/javascript" src="js/jquery.jplayer.min.js"></script>
        <script type="text/javascript" src="js/jplayer.playlist.min.js"></script>
        <script type="text/javascript" src="js/player.js"></script>
        <script type="text/javascript" src="js/jquery.fancybox-1.3.1.js"></script>
        <script type="text/javascript" src="js/lightbox.js"></script>
        <script type="text/javascript" src="js/jquery-ui.min.js"></script>
        <script type="text/javascript" src="js/jquery.easing.1.3.js"></script>
        <script type="text/javascript" src="js/jquery.mCustomScrollbar.js"></script>
        
        <script type="text/javascript">var switchTo5x=true;</script>
        <script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
        <script type="text/javascript">stLight.options({publisher: "d61f6a85-907f-442e-81a4-b8b5c3697454", doNotHash: false, doNotCopy: false, hashAddressBar: false});</script>
    </head>
    <body>
        <!-- Styleswitcher Start -->
        <!--<div class="styles">
            <ul>
                <li><a href="?style=style7" rel="styles7" class="styleswitch brown">&nbsp;</a></li>
                <li><a href="?style=style1" rel="styles1" class="styleswitch red">&nbsp;</a></li>
                <li><a href="?style=style2" rel="styles2" class="styleswitch blue">&nbsp;</a></li>
                <li><a href="?style=style3" rel="styles3" class="styleswitch gray">&nbsp;</a></li>
                <li><a href="?style=style4" rel="styles4" class="styleswitch orange">&nbsp;</a></li>
                <li><a href="?style=style5" rel="styles5" class="styleswitch green">&nbsp;</a></li>
                <li><a href="?style=style6" rel="styles6" class="styleswitch orange-red">&nbsp;</a></li>
            </ul>
        </div>-->
        <div id="outer-wrapper">
            <div class="inner">
                <!-- Header Start -->
                <div id="header">
                    <span class="topbar">&nbsp;</span>
                    <!-- Container Start -->
                    <div class="container">
                        <!-- Logo Start -->
                        <div class="five columns left">
                            <a href="index.php" class="logo"><img height="80px" width="180px" src="<?= $apdata->logo ?>" /></a>
                        </div>
                        <!-- Logo End -->
                        <div class="eleven columns right">
                            <!-- Top Links Start -->
                            <ul class="top-links">
                                <li>
                                    <a href="javascript:animatedcollapse.toggle('login-box')">
                                        <?= (isset($_SESSION['user'])) ? $_SESSION['user'] : 'Log In'; ?>
                                    </a>
                                    <div id="login-box">
                                        <?php if (isset($_SESSION['user'])) { ?>
                                            <ul>
                                                <li><h5 class="white"> <a href="fav.php">Favorited Videos</a></h5></li>
                                                <li><h5 class="white"> <a href="liked.php">Liked Video</a></h5></li>
                                                <li> <h4 class="white"><a href="logout.php?url=<?php echo "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>">log-Out</a></h4></li>
                                            </ul>
                                        <?php } else { ?>
                                            <ul>         
                                                <li><h4 class="white">User Login</h4></li>
                                                <form action="" method="POST">
                                                    <li>
                                                        <input name="username" placeholder="yourname@email.com" class="bar" />
                                                    </li>
                                                    <li>
                                                        <input name="password" placeholder="password" type="password" class="bar" />
                                                    </li>
                                                    <li>
                                                        <input name="remember" type="checkbox" class="left" />
                                                        <p>Remember me</p>
                                                    </li>
                                                    <li>
                                                        <button name="login" class="backcolr">Login</button>
                                                    </li>
                                                </form>
                                            </ul>
                                            <div class="forgot">
                                                <a href="#">Forget Password?</a>
                                            </div>
                                        <?php } ?>

                                        <div class="clear"></div>
                                    </div>
                                </li>
                                <?php if (!isset($_SESSION['user'])) { ?>
                                <li>
                                <a href="javascript:animatedcollapse.toggle('signup')">Signup</a>
                                <div id="signup" style="display:none;">
                                    <ul>         
                                        <li><h4 class="white">User Signup</h4></li>
                                        <form action="" method="POST">
                                            <li>
                                                <input name="username" placeholder="yourname@email.com" class="bar" />
                                            </li>
                                            <li>
                                                <input name="password" placeholder="password" type="password" class="bar" />
                                            </li>
                                            <li>
                                                <input name="remember" type="checkbox" class="left" />
                                                <p>Remember me</p>
                                            </li>
                                            <li>
                                                <button name="login" class="backcolr">Login</button>
                                            </li>
                                        </form>
                                    </ul>
                                </div>
                                </li>
                                <?php } ?>
                                <li>
                                    <a href="javascript:animatedcollapse.toggle('search-box')">Search</a>
                                    <div id="search-box">
                                        <form action="index.php">
                                            <input name="s" value="<?php
                                            if (isset($_GET['s'])) {
                                                echo $_GET['s'];
                                            }
                                            ?>" class="bar" />
                                            <button class="backcolr">Search</button>
                                        </form>
                                    </div>
                                </li>
                            </ul><span style="color: red">
                                <?php
                                if (isset($msg)) {
                                    echo $msg;
                                }
                                ?></span>
                            <!-- Top Links End -->
                            <!-- Navigation Start -->
                            <div class="navigation">
                                <div id="smoothmenu1" class="ddsmoothmenu">
                                    <ul>
                                        <li class="<?= $isHomeActive ?>"><a href="index.php">Home</a></li>
                                        <li class="<?= $ispopulerActive ?>"><a href="populer.php">Popular</a></li>
                                        <li class="<?= $iscatActive ?>"><a href="#">Category</a>
                                            <ul>
                                                <?php foreach ($cat->result as $val) { ?>
                                                    <li><a href="category.php?c=<?php echo $val->id; ?>"><?php echo $val->category; ?></a></li>
                                                <?php } ?>
                                            </ul>
                                        </li>
                                        <li><a href="#">Contact</a></li>
                                    </ul>
                                    <div class="clear"></div>
                                </div>
                            </div>
                            <!-- Navigation End -->
                            <div class="clear"></div>
                        </div>
                    </div>
                    <!-- Container End -->
                </div>
                <script type="text/javascript">
                    jQuery.fn.center = function () {
                        this.css("position", "absolute");
                        this.css("top", Math.max(0, (($(window).height() - $(this).outerHeight()) / 2) +
                                $(window).scrollTop()) + "px");
                        this.css("left", Math.max(0, (($(window).width() - $(this).outerWidth()) / 2) +
                                $(window).scrollLeft()) + "px");
                        return this;
                    }
                    $("login-box").center();
                </script>