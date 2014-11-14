<?php
include "header.php";
$lt = 9;
$val = $_GET['s'];
$cur = isset($_GET['p']) ? $_GET['p'] : 1;
$ob = isset($_GET['ob']) ? $_GET['ob'] : 'latest';
$data = searchVideo(($cur - 1) * $lt, $lt, $val, $ob);
$recent = recentVideo(0, 4);
$pagination = range(0, $data->tr, $lt); 
?>
<div class="container">
    <div class="row">
        <div class="col-sm-8">
            <div class="section-header">
                <h3><?php echo $data->tr; ?> results Found</h3>
                <div class="section-nav">
                    <ul class="sorting">
                        <li class="sort-text">Sort by:</li>
                        <?php
                        list($l, $v, $li, $c) = array($_GET['ob'] == 'created' ? 'active' : '',
                            $_GET['ob'] == 'views' ? 'active' : '',
                            $_GET['ob'] == 'likes' ? 'active' : '',
                            $_GET['ob'] == 'comments' ? 'active' : '');
                        echo sprintf('<li class="%s"><a href="?%s">Latest</a></li>', $l, http_build_query(array_merge($_GET, array('ob' => 'created'))));
                        echo sprintf('<li class="%s"><a href="?%s">Viewed</a></li>', $v, http_build_query(array_merge($_GET, array('ob' => 'views'))));
                        echo sprintf('<li class="%s"><a href="?%s">Liked</a></li>', $li, http_build_query(array_merge($_GET, array('ob' => 'likes'))));
                        echo sprintf('<li class="%s"><a href="?%s">Comments</a></li>', $c, http_build_query(array_merge($_GET, array('ob' => 'comments'))));
                        ?>
                    </ul>
                </div>
            </div>
            <div class="row video-section meta-maxwidth-230">
                <?php foreach ($data->result as $value) { //echo '<pre>';print_r($value);echo '</pre>'; ?>
                    <div class="col-sm-4 col-xs-6 item">
                        <a title="<?php echo $value->title; ?>" href="http://videotube.marstheme.com/video/best-trance-music-2014/">
                            <img src="<?php echo $value->thumbs->large; ?>" class="img-responsive wp-post-image" alt="Best TRANCE music 2014" height="150" width="230"></a>
                        <h3><a title="<?php echo $value->title; ?>" href="http://videotube.marstheme.com/video/best-trance-music-2014/"><?php echo $value->title; ?></a></h3>
                        <div class="meta"><span class="date"><?php echo dateFormat($value->created); ?></span>
                            <span class="views"><i class="fa fa-eye"></i><?php echo $value->views; ?></span>
                            <span class="heart"><i class="fa fa-heart"></i><?php echo $value->likes; ?></span>
                            <span class="fcomments"><i class="fa fa-comments"></i><?php echo $value->comments; ?></span>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
        <div class="col-sm-4 sidebar">
            <div class="widget mars-subscribox-widget"><h4 class="widget-title">Social Subscribox</h4>
                <div class="social-counter-item">
                    <a target="_blank" href="456093831125324">
                        <i class="fa fa-facebook"></i>
                        <span class="counter">135</span>
                        <span class="counter-text">Fans</span>
                    </a>
                </div>
                <div class="social-counter-item">
                    <a target="_blank" href="#">
                        <i class="fa fa-twitter"></i>
                        <span class="counter">73</span>
                        <span class="counter-text">Followers</span>
                    </a>
                </div>
                <div class="social-counter-item">
                    <a target="_blank" href="#">
                        <i class="fa fa-google-plus"></i>
                        <span class="counter">214</span>
                        <span class="counter-text">Fans</span>
                    </a>
                </div>

                <div class="social-counter-item last">
                    <a href="#" data-toggle="modal" data-target="#subscrib-modal">
                        <i class="fa fa-rss"></i>
                        <span class="counter">15</span>
                        <span class="counter-text">Subscribers</span>
                    </a>
                </div>
                <!-- Modal -->
                <div class="modal fade" id="subscrib-modal" tabindex="-1" role="dialog" aria-labelledby="subscrib-modal-label" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                <h4 class="modal-title" id="subscrib-modal-label">Subscribe</h4>
                            </div>
                            <div class="modal-body">
                                <form method="post" role="form" action="" name="mars-subscribe-form" id="mars-subscribe-form">
                                    <div class="form-group name">
                                        <label for="name">Your Name</label>
                                        <input class="form-control" id="name" type="text">
                                    </div>
                                    <div class="form-group email">
                                        <label for="email">Your Email Address</label>
                                        <input class="form-control" id="email" type="email">
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input name="agree" id="agree" type="checkbox"> <a href="http://videotube.marstheme.com/privacy-policy/">User Agreement &amp; Privacy Policy</a>
                                        </label>
                                    </div>
                                    <input id="mars_subscrib" name="mars_subscrib" value="989fcc75ea" type="hidden"><input name="_wp_http_referer" value="/?post_type=video&amp;s=best" type="hidden">				  <button type="submit" class="btn btn-primary">Register</button>
                                    <input name="submit-label" value="Register" type="hidden">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <input name="referer" id="referer" value="40" type="hidden">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="widget mars-videos-sidebar-widget"><h4 class="widget-title">Latest Videos</h4>
                <div class="row">
                    <div class="widget mars-videos-sidebar-widget">
                        <?php foreach ($recent->result as $value) { //echo '<pre>';print_r($value);echo '</pre>'; ?>
                            <div class="col-xs-6 item mars-videos-sidebar-widget-3-2360">
                                <a title="<?php echo $value->title; ?>" href="http://videotube.marstheme.com/video/best-trance-music-2014/">
                                    <img src="<?php echo $value->thumbs->large; ?>" class="img-responsive wp-post-image" alt="Best TRANCE music 2014" height="150" width="230"></a>
                                <h3><a title="<?php echo $value->title; ?>" href="http://videotube.marstheme.com/video/best-trance-music-2014/"><?php echo $value->title; ?></a></h3>
                                <div class="meta"><span class="date"><?php echo dateFormat($value->created); ?></span>
                                    <span class="views"><i class="fa fa-eye"></i><?php echo $value->views; ?></span>
                                    <span class="heart"><i class="fa fa-heart"></i><?php echo $value->likes; ?></span>
                                    <span class="fcomments"><i class="fa fa-comments"></i><?php echo $value->comments; ?></span>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- /.sidebar -->
    <ul class="pagination">
        <?php
        foreach ($pagination as $key => $val) {
            $page = $key + 1;
            $qs = http_build_query(array_merge($_GET, array('p' => $page)));
            if ($page == count($pagination))
                echo sprintf('<li><span class="page-nmbers %s"><a href="%s">Last →</a></span></li>', ($page) == $cur ? 'current' : '', '?' . $qs);
            else
                echo sprintf('<li><span class="page-numbers %s"><a href="%s">%d</a></span></li>', ($page) == $cur ? 'current' : '', '?' . $qs, $page);
        }
        ?>
    </ul>
</div><!-- /.row -->   
</div>
<?php include "footer.php" ?>