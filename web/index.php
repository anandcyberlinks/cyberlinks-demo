<?php
require_once('header.php');
$VideoList = videoList(0,18);
$featuredList = featuredList();
$viewed = viewedVideo(0, 18);
$liked = likedVideo(0, 6);

if(count($featuredList->result) > 0){
    $feature_data = array_chunk($featuredList->result,3);
}

if(count($VideoList->result) > 0){
    $video_list = array_chunk($VideoList->result,9);
}
if(count($VideoList->result) > 0){
    $viewed_list = array_chunk($viewed->result,6);
}
//echo '<pre>';print_r($viewed_list);echo '</pre>';
?>
<div id="carousel-featured-mars-featuredvideo-widgets-2" class="carousel carousel-mars-featuredvideo-widgets-2 slide" data-ride="carousel">
            <div class="container section-header">
                <h3><i class="fa fa-star"></i> Featured Videos</h3>
                <ol class="carousel-indicators section-nav">
                    <li data-target="#carousel-featured-mars-featuredvideo-widgets-2" data-slide-to="0" class="bullet active"></li>
                    <li data-target="#carousel-featured-mars-featuredvideo-widgets-2" data-slide-to="1" class="bullet"></li>
                    <li data-target="#carousel-featured-mars-featuredvideo-widgets-2" data-slide-to="2" class="bullet"></li>
                </ol>
            </div>
            <div class="featured-wrapper">
                <div class="container">
                    <div class="row">
                        <div class="carousel-inner">
                            <?php
                                    foreach($feature_data as $key=>$val){
                                                echo sprintf('<div class="item %s %d">',$key == 0 ? 'active' : '',$key + 1);
                                                foreach($val as $k=>$v){ //print_r($v->thumbs); exit; ?>
                                                <div id="video-featured-2360" class="col-sm-4 mars-featuredvideo-widgets-2-2360">
                                                    <div class="item-img">
                                                        <a title="<?php echo $v->title ?>" href="http://videotube.marstheme.com/video/best-trance-music-2014/">
                                                        <img width="360" height="240" src="<?php echo $v->thumbs->large; ?>" class="img-responsive wp-post-image" alt="Best TRANCE music 2014" /></a>                                        		<a href="http://videotube.marstheme.com/video/best-trance-music-2014/"><div class="img-hover"></div></a>
                                                    </div> 				                                
                                                    <div class="feat-item">
                                                        <div class="feat-info video-info-2360">
                                                            <h3><a title="Best TRANCE music 2014" href="http://localhost/mobiletvweb/playvideo?id=<?php echo $v->id ?>"><?php echo $v->title ?></a></h3>
                                                            <div class="meta"><span class="date"><?php echo dateFormat($v->created); ?></span>
                                                                <span class="views"><i class="fa fa-eye"></i><?php echo $v->views; ?></span>
                                                                <span class="heart"><i class="fa fa-heart"></i><?php echo $v->likes; ?></span>
                                                                <span class="fcomments"><i class="fa fa-comments"></i><?php echo $v->comments; ?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>             
                                                <?php }
                                                echo '</div>';
                                    }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /#carousel-featured -->
        <script>
            (function ($) {
                "use strict";
                jQuery('.carousel-mars-featuredvideo-widgets-2').carousel({
                    pause: false
                });
            })(jQuery);
        </script>				
        <div class="container">

            <div class="row">
                <div class="col-sm-8">
                    

                    <div id="carousel-latest-mars-mainvideo-widgets-2" class="carousel carousel-mars-mainvideo-widgets-2 slide video-section" data-ride="carousel">
                        <div class="section-header">
                            <h3><i class="fa fa-play"></i> Latest Videos</h3>
                            <ol class="carousel-indicators section-nav">
                                <li data-target="#carousel-latest-mars-mainvideo-widgets-2" data-slide-to="0" class="bullet active"></li>
                                <li data-target="#carousel-latest-mars-mainvideo-widgets-2" data-slide-to="1" class="bullet"></li> 				          
                            </ol>
                        </div>
                        <!-- 2 columns -->
                        <div class="latest-wrapper">
                            <div class="row">
                                <div class="carousel-inner">
                                    <?php
                                        foreach($video_list as $key=>$val){
                                                echo sprintf('<div class="item %s %d">',$key == 0 ? 'active' : '',$key + 1);
                                                foreach($val as $k=>$v){ //print_r($v->thumbs); exit; ?>
                                                <div id="video-main-mars-mainvideo-widgets-2-2508" class="col-sm-4 col-xs-6 item video-2508">
                                                    <div class="item-img">
                                                        <a title="<?php echo $v->title ?>" href="http://localhost/mobiletvweb/playvideo.php">
                                                            <img width="230" height="150" src="<?php echo $v->thumbs->large; ?>" class="img-responsive wp-post-image" alt="<?php echo $v->title ?>" />
                                                        </a>
                                                        <a href="http://videotube.marstheme.com/video/awesome-film-perfomance/">
                                                            <div class="img-hover"></div>
                                                        </a>
                                                    </div>
                                                    <h3><a title="<?php echo $v->title ?>" href="http://localhost/mobiletvweb/playvideo.php"><?php echo $v->title ?></a></h3>
                                                    <div class="meta">
                                                        <span class="date"><?php echo dateFormat($v->created); ?></span>
                                                        <span class="views"><i class="fa fa-eye"></i><?php echo $v->views; ?></span>
                                                        <span class="heart"><i class="fa fa-heart"></i><?php echo $v->likes; ?></span>
                                                        <span class="fcomments"><i class="fa fa-comments"></i><?php echo $v->comments; ?></span>
                                                    </div>
                                                </div>       
                                                <?php }
                                                echo '</div>';
                                        }
                                    ?>
                                </div>
                            </div>
                        </div>

                    </div><!-- /#carousel-->
                    <div id="carousel-latest-mars-mainvideo-widgets-3" class="carousel carousel-mars-mainvideo-widgets-3 slide video-section" data-ride="carousel">
                        <div class="section-header">
                            <h3><i class="fa fa-play"></i> Most Viewed Videos</h3>
                            <ol class="carousel-indicators section-nav">
                                <li data-target="#carousel-latest-mars-mainvideo-widgets-3" data-slide-to="0" class="bullet active"></li>
                                <li data-target="#carousel-latest-mars-mainvideo-widgets-3" data-slide-to="1" class="bullet"></li> <li data-target="#carousel-latest-mars-mainvideo-widgets-3" data-slide-to="2" class="bullet"></li> 				          
                            </ol>
                        </div>
                        <!-- 2 columns -->
                        <div class="latest-wrapper">
                            <div class="row">
                                <div class="carousel-inner">
                                    <?php
                                        foreach($viewed_list as $key=>$val){
                                                echo sprintf('<div class="item %s %d">',$key == 0 ? 'active' : '',$key + 1);
                                                foreach($val as $k=>$v){ //print_r($v->thumbs); exit; ?>
                                                <div id="video-main-mars-mainvideo-widgets-2-2508" class="col-sm-4 col-xs-6 item video-2508">
                                                    <div class="item-img">
                                                        <a title="<?php echo $v->title ?>" href="http://localhost/mobiletvweb/playvideo.php">
                                                            <img width="230" height="150" src="<?php echo $v->thumbs->large; ?>" class="img-responsive wp-post-image" alt="<?php echo $v->title ?>" />
                                                        </a>
                                                        <a href="http://videotube.marstheme.com/video/awesome-film-perfomance/">
                                                            <div class="img-hover"></div>
                                                        </a>
                                                    </div>
                                                    <h3><a title="<?php echo $v->title ?>" href="http://localhost/mobiletvweb/playvideo.php"><?php echo $v->title ?></a></h3>
                                                    <div class="meta">
                                                        <span class="date"><?php echo dateFormat($v->created); ?></span>
                                                        <span class="views"><i class="fa fa-eye"></i><?php echo $v->views; ?></span>
                                                        <span class="heart"><i class="fa fa-heart"></i><?php echo $v->likes; ?></span>
                                                        <span class="fcomments"><i class="fa fa-comments"></i><?php echo $v->comments; ?></span>
                                                    </div>
                                                </div>       
                                                <?php }
                                                echo '</div>';
                                        }
                                    ?>
                                </div>
                            </div>
                        </div>

                    </div>	
                </div><!-- /.video-section -->
                <div class="col-sm-4 sidebar">
                    <div class="widget mars-loginform-widget"><h4 class="widget-title">Profile</h4><div class="alert alert-danger" style="display:none;"></div>
                        <form name="vt_loginform" id="vt_loginform" action="http://videotube.marstheme.com/wp-login.php" method="post">

                            <p class="login-username">
                                <label for="user_login">Username</label>
                                <input type="text" name="log" id="user_login" class="input" value="" size="20" />
                            </p>
                            <p class="login-password">
                                <label for="user_pass">Password</label>
                                <input type="password" name="pwd" id="user_pass" class="input" value="" size="20" />
                            </p>
                            <a href="http://videotube.marstheme.com/wp-login.php?action=lostpassword&redirect_to=http://videotube.marstheme.com">Lost Password?</a>
                            <p class="login-remember"><label><input name="rememberme" type="checkbox" id="rememberme" value="forever" /> Remember Me</label></p>
                            <p class="login-submit">
                                <input type="submit" name="wp-submit" id="wp-submit" class="button-primary" value="Log In" />
                                <input type="hidden" name="redirect_to" value="http://videotube.marstheme.com" />
                            </p>

                            <input type="hidden" name="action" value="vt_ajax_login">
                            <input type="hidden" name="button_label" value="Log In">

                        </form></div><div class="widget mars-subscribox-widget"><h4 class="widget-title">Social Subscribox</h4>			        <div class="social-counter-item">
                            <a target="_blank" href="456093831125324">
                                <i class="fa fa-facebook"></i>
                                <span class="counter">135</span>
                                <span class="counter-text">Fans</span>
                            </a>
                        </div>
                        <div class="social-counter-item">
                            <a target="_blank" href="#">
                                <i class="fa fa-twitter"></i>
                                <span class="counter">72</span>
                                <span class="counter-text">Followers</span>
                            </a>
                        </div>
                        <div class="social-counter-item">
                            <a target="_blank" href="#">
                                <i class="fa fa-google-plus"></i>
                                <span class="counter">213</span>
                                <span class="counter-text">Fans</span>
                            </a>
                        </div>

                        <div class="social-counter-item last">
                            <a href="#" data-toggle="modal" data-target="#subscrib-modal">
                                <i class="fa fa-rss"></i>
                                <span class="counter">14</span>
                                <span class="counter-text">Subscribers</span>
                            </a>
                        </div>
                        <!-- Modal -->
                        <div class="modal fade" id="subscrib-modal" tabindex="-1" role="dialog" aria-labelledby="subscrib-modal-label" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                        <h4 class="modal-title" id="subscrib-modal-label">Subscribe</h4>
                                    </div>
                                    <div class="modal-body">
                                        <form method="post" role="form" action="" name="mars-subscribe-form" id="mars-subscribe-form">
                                            <div class="form-group name">
                                                <label for="name">Your Name</label>
                                                <input type="text" class="form-control" id="name">
                                            </div>
                                            <div class="form-group email">
                                                <label for="email">Your Email Address</label>
                                                <input type="email" class="form-control" id="email">
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input name="agree" id="agree" type="checkbox"> <a href="http://videotube.marstheme.com/privacy-policy/">User Agreement & Privacy Policy</a>
                                                </label>
                                            </div>
                                            <input type="hidden" id="mars_subscrib" name="mars_subscrib" value="e174d964a1" /><input type="hidden" name="_wp_http_referer" value="/" />				  <button type="submit" class="btn btn-primary">Register</button>
                                            <input type="hidden" name="submit-label" value="Register">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                            <input type="hidden" name="referer" id="referer" value="28">
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    
                    
                    <div class="widget mars-videos-sidebar-widget">
                        <h4 class="widget-title">Most Liked videos</h4>
                        <div class="row">
                            <?php foreach($liked->result as $val){ ?>
                            
                            <div class="col-xs-6 item mars-videos-sidebar-widget-2-1719">
                                <div class="item-img">
                                    <a title="<?=$val->title ?>" href="http://videotube.marstheme.com/video/selfie-the-chainsmokers/">
                                    <img width="165" height="108" src="<?=$val->thumbs->large ?>" class="img-responsive wp-post-image" alt="music2" /></a>
                                    <a href="http://videotube.marstheme.com/video/selfie-the-chainsmokers/"><div class="img-hover"></div></a>
                                </div>	            	
                                <h3><a title="<?=$val->title ?>" href="http://videotube.marstheme.com/video/selfie-the-chainsmokers/"><?=$val->title ?></a></h3>

                                <div class="meta"><span class="date"><?php echo dateFormat($v->created); ?></span>
                                    <span class="views"><i class="fa fa-eye"></i><?=$val->views ?></span><span class="heart">
                                    <i class="fa fa-heart"></i><?=$val->likes ?></span>
                                    <span class="fcomments"><i class="fa fa-comments"></i><?=$val->comments ?></span>

                                </div>
                            </div>
                            <?php } ?>

                        </div>
                    </div>
                </div><!-- /.sidebar -->		</div><!-- /.row -->
        </div><!-- /.container -->
        <!-- /#footer -->
<?php require_once('footer.php'); ?>