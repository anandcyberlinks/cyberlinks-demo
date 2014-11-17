<?php require_once('constant.php');
$appdetail = appdetail();
$cat = catList()->result;
//echo '<pre>';print_r($cat);echo '</pre>';
?>
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
                    <ul id="menu-header-menu" class="nav navbar-nav list-inline menu">
                        <li id="menu-item-2090">
                            <a href="<?=BASEURL ?>">Home </a>
                        </li>
                        <li id="menu-item-2048" class="menu-item-has-children dropdown <?php if(isset($_GET['k']) && $_GET['k'] == 'category'){ echo active; } ?>"><a href="">Browse <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <?php foreach($cat as $val){ ?>
                                <li id="menu-item-2072" class="menu-item-object-categories"><a href="<?php echo BASEURL.'search.php?k=category&s='.$val->id; ?>"><?php echo $val->category ?></a></li>
                            <?php } ?>
                            </ul>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>