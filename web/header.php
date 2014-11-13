<?php
require_once('constant.php');
$appdetail = appdetail();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=edge"><![endif]-->
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>VideoTube</title>
        <!--[if lt IE 9]>
            <script src="<?= JS_PATH ?>ie8/html5shiv.js"></script>
            <script src="<?= JS_PATH ?>ie8/respond.min.js"></script>
        <![endif]-->
        <link rel='stylesheet' id='style-css'  href='<?= CSS_PATH ?>videotube-child/style.css' type='text/css' media='all' />
        <link rel='stylesheet' href='<?= CSS_PATH ?>font-awesome.min.css?ver=4.0' type='text/css' media='all' />
        <link rel='stylesheet' href='<?= CSS_PATH ?>/bootstrap.min.css?ver=4.0' type='text/css' media='all' />
        <link rel='stylesheet' href='<?= CSS_PATH ?>font-awesome.css?ver=4.0' type='text/css' media='all' />
        <link rel='stylesheet' href='<?= CSS_PATH ?>fonts/css.css?family=Lato%3A300%2C400%2C700%2C900&#038;ver=4.0' type='text/css' media='all' />
        <link rel='stylesheet' href='<?= CSS_PATH ?>style.css' type='text/css' media='all' />
        <link rel='stylesheet' href='<?= CSS_PATH ?>bootstrap-multiselect.css?ver=4.0' type='text/css' media='all' />
        <link rel='stylesheet' href='<?= CSS_PATH ?>front.end.css?ver=4.0' type='text/css' media='all' />
      <!--- JS --->
        <script type='text/javascript' src='<?= JS_PATH ?>jquery/jquery.js?ver=1.11.1'></script>
        <script type='text/javascript' src='<?= JS_PATH ?>jquery/jquery-migrate.min.js?ver=1.2.1'></script>
        <script type='text/javascript' src='<?= JS_PATH ?>bootstrap.min.js?ver=4.0'></script>
    </head>
    <body class="vc_responsive">
        <div id="header">
            <div class="container">
                <div class="row">
                    <div class="col-sm-3" id="logo">
                        <a title="<?php echo  $appdetail->first_name.' '.$appdetail->last_name ?>" href="<?=BASEURL ?>">
                            <img id="logo_image" src="<?php echo  $appdetail->logo ?>" alt="Just another WordPress site" />
                        </a>
                    </div>
                    <form method="GET" action="search.php">	
                        <div class="col-sm-6" id="header-search">
                            <span class="glyphicon glyphicon-search search-icon"></span>
                            <input value="" name="s" type="text" placeholder="Search here..." id="search">
                        </div>
                    </form>
                    <div class="col-sm-3" id="header-social">
                        <a href="456093831125324"><i class="fa fa-facebook"></i></a><a href="#"><i class="fa fa-twitter"></i></a><a href="#"><i class="fa fa-google-plus"></i></a><a href="#"><i class="fa fa-instagram"></i></a><a href="#"><i class="fa fa-linkedin"></i></a><a href="#"><i class="fa fa-tumblr"></i></a>					<a href="http://videotube.marstheme.com/feed/rss/"><i class="fa fa-rss"></i></a>
                    </div>
                </div>
            </div>
        </div><!-- /#header -->
        <div id="navigation-wrapper">
            <div class="container">
                <div class="navbar-header">
                    <button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".bs-navbar-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>
                <nav class="collapse navbar-collapse bs-navbar-collapse" role="navigation">
                    <!-- menu -->
                    <ul id="menu-header-menu" class="nav navbar-nav list-inline menu"><li id="menu-item-2090" class="menu-item menu-item-type-post_type menu-item-object-page current-menu-item page_item page-item-28 current_page_item menu-item-has-children active dropdown menu-item-2090 depth active "><a href="http://videotube.marstheme.com/">Home <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li id="menu-item-2224" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-2224 depth"><a href="http://videotube.marstheme.com/homepage-v1-2-columns-left-sidebar/">V1 &#8211; 2 Columns &#8211; Left Sidebar</a></li>
                                <li id="menu-item-2225" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-2225 depth"><a href="http://videotube.marstheme.com/homepage-v1-2-columns-right-sidebar/">V1 &#8211; 2 Columns &#8211; Right Sidebar</a></li>
                                <li id="menu-item-2232" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-2232 depth"><a href="http://videotube.marstheme.com/homepage-v2-fullwidth-3-columns/">V2 &#8211; Fullwidth &#8211; 3 Columns</a></li>
                                <li id="menu-item-2231" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-2231 depth"><a href="http://videotube.marstheme.com/homepage-v3-fullwidth-4columns/">V3 &#8211; Fullwidth &#8211; 4columns</a></li>
                                <li id="menu-item-2230" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-2230 depth"><a href="http://videotube.marstheme.com/homepage-v4-fullwidth-6-columns/">V4 &#8211; Fullwidth &#8211; 6 Columns</a></li>
                                <li id="menu-item-2229" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-2229 depth"><a href="http://videotube.marstheme.com/homepage-v5-1-column-left-sidebar/">V5 &#8211; 1 Column &#8211; Left Sidebar</a></li>
                                <li id="menu-item-2228" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-2228 depth"><a href="http://videotube.marstheme.com/homepage-v5-1-column-right-sidebar/">V5 &#8211; 1 Column &#8211; Right Sidebar</a></li>
                                <li id="menu-item-2227" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-2227 depth"><a href="http://videotube.marstheme.com/homepage-v5-1-column-right-sidebar-version-2/">V5 &#8211; 1 Column &#8211; Right Sidebar &#8211; Version 2</a></li>
                                <li id="menu-item-2226" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-2226 depth"><a href="http://videotube.marstheme.com/homepage-v5-1-column-left-sidebar-version-2/">V5 &#8211; 1 Column &#8211; Left Sidebar &#8211; Version 2</a></li>
                                <li id="menu-item-2269" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-2269 depth"><a href="http://videotube.marstheme.com/layout-with-4-columns-and-featured-widget/">Layout with 4 Columns and Featured Widget</a></li>
                                <li id="menu-item-2098" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-2098 depth"><a href="http://videotube.marstheme.com/video-scrolling-page/">Video scrolling Page</a></li>
                                <li id="menu-item-2097" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-2097 depth"><a href="http://videotube.marstheme.com/post-scrolling-page/">Post scrolling Page</a></li>
                            </ul>
                        </li>
                        <li id="menu-item-2048" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children dropdown menu-item-2048 depth"><a href="http://videotube.marstheme.com/video">Browse <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li id="menu-item-2072" class="menu-item menu-item-type-taxonomy menu-item-object-categories menu-item-2072 depth"><a href="http://videotube.marstheme.com/categories/trailers/">Trailers</a></li>
                                <li id="menu-item-2073" class="menu-item menu-item-type-taxonomy menu-item-object-categories menu-item-2073 depth"><a href="http://videotube.marstheme.com/categories/food-health/">Food &#038; Health</a></li>
                                <li id="menu-item-2074" class="menu-item menu-item-type-taxonomy menu-item-object-categories menu-item-2074 depth"><a href="http://videotube.marstheme.com/categories/gaming/">Gaming</a></li>
                                <li id="menu-item-2075" class="menu-item menu-item-type-taxonomy menu-item-object-categories menu-item-2075 depth"><a href="http://videotube.marstheme.com/categories/music/">Music</a></li>
                                <li id="menu-item-2076" class="menu-item menu-item-type-taxonomy menu-item-object-categories menu-item-2076 depth"><a href="http://videotube.marstheme.com/categories/sports/">Sports</a></li>
                                <li id="menu-item-2077" class="menu-item menu-item-type-taxonomy menu-item-object-categories menu-item-2077 depth"><a href="http://videotube.marstheme.com/categories/vlogs/">Vlogs</a></li>
                            </ul>
                        </li>
                        <li id="menu-item-2091" class="menu-item menu-item-type-taxonomy menu-item-object-categories menu-item-2091 depth"><a href="http://videotube.marstheme.com/categories/trailers/">Trailers</a></li>
                        <li id="menu-item-2092" class="menu-item menu-item-type-taxonomy menu-item-object-categories menu-item-2092 depth"><a href="http://videotube.marstheme.com/categories/food-health/">Food &#038; Health</a></li>
                        <li id="menu-item-2080" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-2080 depth"><a href="http://videotube.marstheme.com/blog/">Blog</a></li>
                        <li id="menu-item-2044" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children dropdown menu-item-2044 depth"><a href="#">Page <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li id="menu-item-2079" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-2079 depth"><a href="http://videotube.marstheme.com/profile/">Profile</a></li>
                                <li id="menu-item-2084" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-2084 depth"><a href="http://videotube.marstheme.com/submit-video/">Submit Video</a></li>
                                <li id="menu-item-2086" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-has-children dropdown-submenu menu-item-2086 depth"><a href="http://videotube.marstheme.com/shortcodes/">Shortcodes</a>
                                    <ul class="dropdown-menu">
                                        <li id="menu-item-2087" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-2087 depth"><a href="http://videotube.marstheme.com/faqs/">FAQs</a></li>
                                    </ul>
                                </li>
                                <li id="menu-item-2082" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-2082 depth"><a href="http://videotube.marstheme.com/page-fullwidth/">Page &#8211; Fullwidth</a></li>
                                <li id="menu-item-2083" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-2083 depth"><a href="http://videotube.marstheme.com/page-right-sidebar/">Page &#8211; Right Sidebar</a></li>
                                <li id="menu-item-2088" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-2088 depth"><a href="http://videotube.marstheme.com/privacy-policy/">Privacy Policy</a></li>
                            </ul>
                        </li>
                        <li id="menu-item-2078" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-2078 depth"><a href="http://themeforest.net/item/videotube-a-responsive-video-wordpress-theme/7214445?ref=phpface">Buy Now</a></li>
                    </ul>			</nav>
            </div>
        </div><!-- /#navigation-wrapper -->				    



