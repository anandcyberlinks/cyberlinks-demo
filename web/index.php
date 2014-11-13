<?php
require_once('header.php');
$VideoList = videoList(0,18);
$featuredList = featuredList();
if(count($featuredList->result) > 0){
    $feature_data = array_chunk($featuredList->result,3);
}

if(count($VideoList->result) > 0){
    $video_list = array_chunk($VideoList->result,9);
}

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
                                    <div class="item active">

                                        <div id="video-main-mars-mainvideo-widgets-3-1719" class="col-sm-4 col-xs-6 item video-1719">
                                            <div class="item-img">
                                                <a title="#SELFIE &#8211; The Chainsmokers" href="http://videotube.marstheme.com/video/selfie-the-chainsmokers/"><img width="230" height="150" src="http://videotube.marstheme.com/wp-content/uploads/2014/03/music2-230x150.jpg" class="img-responsive wp-post-image" alt="music2" /></a>													<a href="http://videotube.marstheme.com/video/selfie-the-chainsmokers/"><div class="img-hover"></div></a>
                                            </div>
                                            <h3><a title="#SELFIE &#8211; The Chainsmokers" href="http://videotube.marstheme.com/video/selfie-the-chainsmokers/">#SELFIE &#8211; The Chainsmokers</a></h3>

                                            <div class="meta"><span class="date">8 months ago</span>
                                                <span class="views"><i class="fa fa-eye"></i>4681</span><span class="heart"><i class="fa fa-heart"></i>351</span>
                                                <span class="fcomments"><i class="fa fa-comments"></i>0</span>

                                            </div>
                                        </div> 

                                        <div id="video-main-mars-mainvideo-widgets-3-1903" class="col-sm-4 col-xs-6 item video-1903">
                                            <div class="item-img">
                                                <a title="Wonderfull Chill Out Music Love" href="http://videotube.marstheme.com/video/wonderfull-chill-out-music-love/"><img width="230" height="150" src="http://videotube.marstheme.com/wp-content/uploads/2014/04/wonderfull-chill-out-music-love-230x150.jpg" class="img-responsive wp-post-image" alt="Wonderfull Chill Out Music Love" /></a>													<a href="http://videotube.marstheme.com/video/wonderfull-chill-out-music-love/"><div class="img-hover"></div></a>
                                            </div>
                                            <h3><a title="Wonderfull Chill Out Music Love" href="http://videotube.marstheme.com/video/wonderfull-chill-out-music-love/">Wonderfull Chill Out Music Love</a></h3>

                                            <div class="meta"><span class="date">7 months ago</span>
                                                <span class="views"><i class="fa fa-eye"></i>3561</span><span class="heart"><i class="fa fa-heart"></i>218</span>
                                                <span class="fcomments"><i class="fa fa-comments"></i>3</span>

                                            </div>
                                        </div> 

                                        <div id="video-main-mars-mainvideo-widgets-3-34" class="col-sm-4 col-xs-6 item video-34">
                                            <div class="item-img">
                                                <a title="New Destiny Trailer" href="http://videotube.marstheme.com/video/destiny/"><img width="230" height="150" src="http://videotube.marstheme.com/wp-content/uploads/2014/03/game1-230x150.jpg" class="img-responsive wp-post-image" alt="game1" /></a>													<a href="http://videotube.marstheme.com/video/destiny/"><div class="img-hover"></div></a>
                                            </div>
                                            <h3><a title="New Destiny Trailer" href="http://videotube.marstheme.com/video/destiny/">New Destiny Trailer</a></h3>

                                            <div class="meta"><span class="date">8 months ago</span>
                                                <span class="views"><i class="fa fa-eye"></i>3264</span><span class="heart"><i class="fa fa-heart"></i>62</span>
                                                <span class="fcomments"><i class="fa fa-comments"></i>0</span>

                                            </div>
                                        </div> 

                                        <div id="video-main-mars-mainvideo-widgets-3-1721" class="col-sm-4 col-xs-6 item video-1721">
                                            <div class="item-img">
                                                <a title="Titanfall Launch Trailer" href="http://videotube.marstheme.com/video/titanfall-launch-trailer/"><img width="230" height="150" src="http://videotube.marstheme.com/wp-content/uploads/2014/03/game6-230x150.jpg" class="img-responsive wp-post-image" alt="game6" /></a>													<a href="http://videotube.marstheme.com/video/titanfall-launch-trailer/"><div class="img-hover"></div></a>
                                            </div>
                                            <h3><a title="Titanfall Launch Trailer" href="http://videotube.marstheme.com/video/titanfall-launch-trailer/">Titanfall Launch Trailer</a></h3>

                                            <div class="meta"><span class="date">8 months ago</span>
                                                <span class="views"><i class="fa fa-eye"></i>3071</span><span class="heart"><i class="fa fa-heart"></i>150</span>
                                                <span class="fcomments"><i class="fa fa-comments"></i>0</span>

                                            </div>
                                        </div> 

                                        <div id="video-main-mars-mainvideo-widgets-3-2132" class="col-sm-4 col-xs-6 item video-2132">
                                            <div class="item-img">
                                                <a title="HD Music Nexus Playlist" href="http://videotube.marstheme.com/video/hd-music-nexus-playlist/"><img width="230" height="150" src="http://videotube.marstheme.com/wp-content/uploads/2014/07/hd-music-nexus-playlist-230x150.jpg" class="img-responsive wp-post-image" alt="HD Music Nexus Playlist" /></a>													<a href="http://videotube.marstheme.com/video/hd-music-nexus-playlist/"><div class="img-hover"></div></a>
                                            </div>
                                            <h3><a title="HD Music Nexus Playlist" href="http://videotube.marstheme.com/video/hd-music-nexus-playlist/">HD Music Nexus Playlist</a></h3>

                                            <div class="meta"><span class="date">4 months ago</span>
                                                <span class="views"><i class="fa fa-eye"></i>2522</span><span class="heart"><i class="fa fa-heart"></i>342</span>
                                                <span class="fcomments"><i class="fa fa-comments"></i>0</span>

                                            </div>
                                        </div> 

                                        <div id="video-main-mars-mainvideo-widgets-3-1717" class="col-sm-4 col-xs-6 item video-1717">
                                            <div class="item-img">
                                                <a title="Godzilla Official Main Trailer" href="http://videotube.marstheme.com/video/godzilla-official-main-trailer/"><img width="230" height="150" src="http://videotube.marstheme.com/wp-content/uploads/2014/03/game9-230x150.jpg" class="img-responsive wp-post-image" alt="game9" /></a>													<a href="http://videotube.marstheme.com/video/godzilla-official-main-trailer/"><div class="img-hover"></div></a>
                                            </div>
                                            <h3><a title="Godzilla Official Main Trailer" href="http://videotube.marstheme.com/video/godzilla-official-main-trailer/">Godzilla Official Main Trailer</a></h3>

                                            <div class="meta"><span class="date">8 months ago</span>
                                                <span class="views"><i class="fa fa-eye"></i>2412</span><span class="heart"><i class="fa fa-heart"></i>140</span>
                                                <span class="fcomments"><i class="fa fa-comments"></i>0</span>

                                            </div>
                                        </div> 
                                    </div><div class="item">				                       		
                                        <div id="video-main-mars-mainvideo-widgets-3-201" class="col-sm-4 col-xs-6 item video-201">
                                            <div class="item-img">
                                                <a title="Earth to Echo Trailer" href="http://videotube.marstheme.com/video/earth-to-echo-trailer/"><img width="230" height="150" src="http://videotube.marstheme.com/wp-content/uploads/2014/03/game8-230x150.jpg" class="img-responsive wp-post-image" alt="game8" /></a>													<a href="http://videotube.marstheme.com/video/earth-to-echo-trailer/"><div class="img-hover"></div></a>
                                            </div>
                                            <h3><a title="Earth to Echo Trailer" href="http://videotube.marstheme.com/video/earth-to-echo-trailer/">Earth to Echo Trailer</a></h3>

                                            <div class="meta"><span class="date">8 months ago</span>
                                                <span class="views"><i class="fa fa-eye"></i>2087</span><span class="heart"><i class="fa fa-heart"></i>66</span>
                                                <span class="fcomments"><i class="fa fa-comments"></i>0</span>

                                            </div>
                                        </div> 

                                        <div id="video-main-mars-mainvideo-widgets-3-275" class="col-sm-4 col-xs-6 item video-275">
                                            <div class="item-img">
                                                <a title="Monday Vlog (Mlog)" href="http://videotube.marstheme.com/video/monday-vlog-mlog/"><img width="230" height="150" src="http://videotube.marstheme.com/wp-content/uploads/2014/03/vlog3-230x150.jpg" class="img-responsive wp-post-image" alt="vlog3" /></a>													<a href="http://videotube.marstheme.com/video/monday-vlog-mlog/"><div class="img-hover"></div></a>
                                            </div>
                                            <h3><a title="Monday Vlog (Mlog)" href="http://videotube.marstheme.com/video/monday-vlog-mlog/">Monday Vlog (Mlog)</a></h3>

                                            <div class="meta"><span class="date">8 months ago</span>
                                                <span class="views"><i class="fa fa-eye"></i>1936</span><span class="heart"><i class="fa fa-heart"></i>103</span>
                                                <span class="fcomments"><i class="fa fa-comments"></i>0</span>

                                            </div>
                                        </div> 

                                        <div id="video-main-mars-mainvideo-widgets-3-2360" class="col-sm-4 col-xs-6 item video-2360">
                                            <div class="item-img">
                                                <a title="Best TRANCE music 2014" href="http://videotube.marstheme.com/video/best-trance-music-2014/"><img width="230" height="150" src="http://videotube.marstheme.com/wp-content/uploads/2014/09/best-trance-music-2014-230x150.jpg" class="img-responsive wp-post-image" alt="Best TRANCE music 2014" /></a>													<a href="http://videotube.marstheme.com/video/best-trance-music-2014/"><div class="img-hover"></div></a>
                                            </div>
                                            <h3><a title="Best TRANCE music 2014" href="http://videotube.marstheme.com/video/best-trance-music-2014/">Best TRANCE music 2014</a></h3>

                                            <div class="meta"><span class="date">3 months ago</span>
                                                <span class="views"><i class="fa fa-eye"></i>1910</span><span class="heart"><i class="fa fa-heart"></i>164</span>
                                                <span class="fcomments"><i class="fa fa-comments"></i>0</span>

                                            </div>
                                        </div> 

                                        <div id="video-main-mars-mainvideo-widgets-3-214" class="col-sm-4 col-xs-6 item video-214">
                                            <div class="item-img">
                                                <a title="Dawn of the Planet of the Apes Trailer" href="http://videotube.marstheme.com/video/dawn-of-the-planet-of-the-apes-trailer/"><img width="230" height="150" src="http://videotube.marstheme.com/wp-content/uploads/2014/03/movie1-230x150.jpg" class="img-responsive wp-post-image" alt="movie1" /></a>													<a href="http://videotube.marstheme.com/video/dawn-of-the-planet-of-the-apes-trailer/"><div class="img-hover"></div></a>
                                            </div>
                                            <h3><a title="Dawn of the Planet of the Apes Trailer" href="http://videotube.marstheme.com/video/dawn-of-the-planet-of-the-apes-trailer/">Dawn of the Planet of the Apes Trailer</a></h3>

                                            <div class="meta"><span class="date">8 months ago</span>
                                                <span class="views"><i class="fa fa-eye"></i>1589</span><span class="heart"><i class="fa fa-heart"></i>91</span>
                                                <span class="fcomments"><i class="fa fa-comments"></i>0</span>

                                            </div>
                                        </div> 

                                        <div id="video-main-mars-mainvideo-widgets-3-273" class="col-sm-4 col-xs-6 item video-273">
                                            <div class="item-img">
                                                <a title="Jenna Marbles" href="http://videotube.marstheme.com/video/jenna-marbles/"><img width="230" height="150" src="http://videotube.marstheme.com/wp-content/uploads/2014/03/vlog2-230x150.jpg" class="img-responsive wp-post-image" alt="vlog2" /></a>													<a href="http://videotube.marstheme.com/video/jenna-marbles/"><div class="img-hover"></div></a>
                                            </div>
                                            <h3><a title="Jenna Marbles" href="http://videotube.marstheme.com/video/jenna-marbles/">Jenna Marbles</a></h3>

                                            <div class="meta"><span class="date">8 months ago</span>
                                                <span class="views"><i class="fa fa-eye"></i>1576</span><span class="heart"><i class="fa fa-heart"></i>56</span>
                                                <span class="fcomments"><i class="fa fa-comments"></i>0</span>

                                            </div>
                                        </div> 

                                        <div id="video-main-mars-mainvideo-widgets-3-249" class="col-sm-4 col-xs-6 item video-249">
                                            <div class="item-img">
                                                <a title="Purity Ring &#8211; &#8220;Ungirthed&#8221;" href="http://videotube.marstheme.com/video/purity-ring-ungirthed/"><img width="230" height="150" src="http://videotube.marstheme.com/wp-content/uploads/2014/03/music5-230x150.jpg" class="img-responsive wp-post-image" alt="music5" /></a>													<a href="http://videotube.marstheme.com/video/purity-ring-ungirthed/"><div class="img-hover"></div></a>
                                            </div>
                                            <h3><a title="Purity Ring &#8211; &#8220;Ungirthed&#8221;" href="http://videotube.marstheme.com/video/purity-ring-ungirthed/">Purity Ring &#8211; &#8220;Ungirthed&#8221;</a></h3>

                                            <div class="meta"><span class="date">8 months ago</span>
                                                <span class="views"><i class="fa fa-eye"></i>1305</span><span class="heart"><i class="fa fa-heart"></i>28</span>
                                                <span class="fcomments"><i class="fa fa-comments"></i>0</span>

                                            </div>
                                        </div> 
                                    </div><div class="item">				                       		
                                        <div id="video-main-mars-mainvideo-widgets-3-265" class="col-sm-4 col-xs-6 item video-265">
                                            <div class="item-img">
                                                <a title="Winter Olympics 2014" href="http://videotube.marstheme.com/video/winter-olympics-2014/"><img width="230" height="150" src="http://videotube.marstheme.com/wp-content/uploads/2014/03/sport2-230x150.jpg" class="img-responsive wp-post-image" alt="sport2" /></a>													<a href="http://videotube.marstheme.com/video/winter-olympics-2014/"><div class="img-hover"></div></a>
                                            </div>
                                            <h3><a title="Winter Olympics 2014" href="http://videotube.marstheme.com/video/winter-olympics-2014/">Winter Olympics 2014</a></h3>

                                            <div class="meta"><span class="date">8 months ago</span>
                                                <span class="views"><i class="fa fa-eye"></i>1206</span><span class="heart"><i class="fa fa-heart"></i>46</span>
                                                <span class="fcomments"><i class="fa fa-comments"></i>0</span>

                                            </div>
                                        </div> 

                                        <div id="video-main-mars-mainvideo-widgets-3-97" class="col-sm-4 col-xs-6 item video-97">
                                            <div class="item-img">
                                                <a title="Humans Are Amazing" href="http://videotube.marstheme.com/video/humans-are-amazing/"><img width="230" height="150" src="http://videotube.marstheme.com/wp-content/uploads/2014/07/humans-are-amazing-230x150.jpg" class="img-responsive wp-post-image" alt="Humans Are Amazing" /></a>													<a href="http://videotube.marstheme.com/video/humans-are-amazing/"><div class="img-hover"></div></a>
                                            </div>
                                            <h3><a title="Humans Are Amazing" href="http://videotube.marstheme.com/video/humans-are-amazing/">Humans Are Amazing</a></h3>

                                            <div class="meta"><span class="date">8 months ago</span>
                                                <span class="views"><i class="fa fa-eye"></i>1079</span><span class="heart"><i class="fa fa-heart"></i>28</span>
                                                <span class="fcomments"><i class="fa fa-comments"></i>0</span>

                                            </div>
                                        </div> 

                                        <div id="video-main-mars-mainvideo-widgets-3-271" class="col-sm-4 col-xs-6 item video-271">
                                            <div class="item-img">
                                                <a title="Vlog Update: Israel" href="http://videotube.marstheme.com/video/vlog-update-israel/"><img width="230" height="150" src="http://videotube.marstheme.com/wp-content/uploads/2014/03/vlog-230x150.jpg" class="img-responsive wp-post-image" alt="vlog" /></a>													<a href="http://videotube.marstheme.com/video/vlog-update-israel/"><div class="img-hover"></div></a>
                                            </div>
                                            <h3><a title="Vlog Update: Israel" href="http://videotube.marstheme.com/video/vlog-update-israel/">Vlog Update: Israel</a></h3>

                                            <div class="meta"><span class="date">8 months ago</span>
                                                <span class="views"><i class="fa fa-eye"></i>982</span><span class="heart"><i class="fa fa-heart"></i>44</span>
                                                <span class="fcomments"><i class="fa fa-comments"></i>0</span>

                                            </div>
                                        </div> 

                                        <div id="video-main-mars-mainvideo-widgets-3-52" class="col-sm-4 col-xs-6 item video-52">
                                            <div class="item-img">
                                                <a title="Skyrim" href="http://videotube.marstheme.com/video/skyrim/"><img width="230" height="150" src="http://videotube.marstheme.com/wp-content/uploads/2014/03/game2-230x150.jpg" class="img-responsive wp-post-image" alt="game2" /></a>													<a href="http://videotube.marstheme.com/video/skyrim/"><div class="img-hover"></div></a>
                                            </div>
                                            <h3><a title="Skyrim" href="http://videotube.marstheme.com/video/skyrim/">Skyrim</a></h3>

                                            <div class="meta"><span class="date">8 months ago</span>
                                                <span class="views"><i class="fa fa-eye"></i>863</span><span class="heart"><i class="fa fa-heart"></i>12</span>
                                                <span class="fcomments"><i class="fa fa-comments"></i>0</span>

                                            </div>
                                        </div> 

                                        <div id="video-main-mars-mainvideo-widgets-3-199" class="col-sm-4 col-xs-6 item video-199">
                                            <div class="item-img">
                                                <a title="Trials Frontier Motorcycle Game" href="http://videotube.marstheme.com/video/trials-frontier/"><img width="230" height="150" src="http://videotube.marstheme.com/wp-content/uploads/2014/03/game3-230x150.jpg" class="img-responsive wp-post-image" alt="game3" /></a>													<a href="http://videotube.marstheme.com/video/trials-frontier/"><div class="img-hover"></div></a>
                                            </div>
                                            <h3><a title="Trials Frontier Motorcycle Game" href="http://videotube.marstheme.com/video/trials-frontier/">Trials Frontier Motorcycle Game</a></h3>

                                            <div class="meta"><span class="date">8 months ago</span>
                                                <span class="views"><i class="fa fa-eye"></i>848</span><span class="heart"><i class="fa fa-heart"></i>14</span>
                                                <span class="fcomments"><i class="fa fa-comments"></i>0</span>

                                            </div>
                                        </div> 

                                        <div id="video-main-mars-mainvideo-widgets-3-269" class="col-sm-4 col-xs-6 item video-269">
                                            <div class="item-img">
                                                <a title="Travel Vlog: Langtang Valley" href="http://videotube.marstheme.com/video/travel-vlog-langtang/"><img width="230" height="150" src="http://videotube.marstheme.com/wp-content/uploads/2014/03/vlog4-230x150.jpg" class="img-responsive wp-post-image" alt="vlog4" /></a>													<a href="http://videotube.marstheme.com/video/travel-vlog-langtang/"><div class="img-hover"></div></a>
                                            </div>
                                            <h3><a title="Travel Vlog: Langtang Valley" href="http://videotube.marstheme.com/video/travel-vlog-langtang/">Travel Vlog: Langtang Valley</a></h3>

                                            <div class="meta"><span class="date">8 months ago</span>
                                                <span class="views"><i class="fa fa-eye"></i>795</span><span class="heart"><i class="fa fa-heart"></i>26</span>
                                                <span class="fcomments"><i class="fa fa-comments"></i>0</span>

                                            </div>
                                        </div> 
                                    </div> 
                                </div>
                            </div>
                        </div>

                    </div><!-- /#carousel-->
                    <div class="row video-section meta-maxwidth-360">
                        <div class="col-sm-12 section-header">
                            <h3><i class="fa fa-play"></i> Sports Videos</h3>
                        </div>
                        <!-- 2 columns -->

                        <div class="col-sm-6 item">
                            <div class="item-img">
                                <a href="http://videotube.marstheme.com/video/awesome-film-perfomance/" title="Awesome Film Perfomance"><img width="360" height="240" src="http://videotube.marstheme.com/wp-content/uploads/2014/09/awesome-film-perfomance-360x240.jpg" class="img-responsive wp-post-image" alt="Awesome Film Perfomance" /></a>								<a href="http://videotube.marstheme.com/video/awesome-film-perfomance/"><div class="img-hover"></div></a>
                            </div>                                
                            <h3><a href="http://videotube.marstheme.com/video/awesome-film-perfomance/">Awesome Film Perfomance</a></h3>

                            <div class="meta"><span class="date">1 month ago</span>
                                <span class="views"><i class="fa fa-eye"></i>640</span><span class="heart"><i class="fa fa-heart"></i>86</span>
                                <span class="fcomments"><i class="fa fa-comments"></i>0</span>

                            </div>
                        </div>

                        <div class="col-sm-6 item">
                            <div class="item-img">
                                <a href="http://videotube.marstheme.com/video/hd-music-nexus-playlist/" title="HD Music Nexus Playlist"><img width="360" height="240" src="http://videotube.marstheme.com/wp-content/uploads/2014/07/hd-music-nexus-playlist-360x240.jpg" class="img-responsive wp-post-image" alt="HD Music Nexus Playlist" /></a>								<a href="http://videotube.marstheme.com/video/hd-music-nexus-playlist/"><div class="img-hover"></div></a>
                            </div>                                
                            <h3><a href="http://videotube.marstheme.com/video/hd-music-nexus-playlist/">HD Music Nexus Playlist</a></h3>

                            <div class="meta"><span class="date">4 months ago</span>
                                <span class="views"><i class="fa fa-eye"></i>2522</span><span class="heart"><i class="fa fa-heart"></i>342</span>
                                <span class="fcomments"><i class="fa fa-comments"></i>0</span>

                            </div>
                        </div>

                        <div class="col-sm-6 item">
                            <div class="item-img">
                                <a href="http://videotube.marstheme.com/video/wonderfull-chill-out-music-love/" title="Wonderfull Chill Out Music Love"><img width="360" height="240" src="http://videotube.marstheme.com/wp-content/uploads/2014/04/wonderfull-chill-out-music-love-360x240.jpg" class="img-responsive wp-post-image" alt="Wonderfull Chill Out Music Love" /></a>								<a href="http://videotube.marstheme.com/video/wonderfull-chill-out-music-love/"><div class="img-hover"></div></a>
                            </div>                                
                            <h3><a href="http://videotube.marstheme.com/video/wonderfull-chill-out-music-love/">Wonderfull Chill Out Music Love</a></h3>

                            <div class="meta"><span class="date">7 months ago</span>
                                <span class="views"><i class="fa fa-eye"></i>3561</span><span class="heart"><i class="fa fa-heart"></i>218</span>
                                <span class="fcomments"><i class="fa fa-comments"></i>3</span>

                            </div>
                        </div>

                        <div class="col-sm-6 item">
                            <div class="item-img">
                                <a href="http://videotube.marstheme.com/video/winter-olympics-2014/" title="Winter Olympics 2014"><img width="360" height="240" src="http://videotube.marstheme.com/wp-content/uploads/2014/03/sport2-360x240.jpg" class="img-responsive wp-post-image" alt="sport2" /></a>								<a href="http://videotube.marstheme.com/video/winter-olympics-2014/"><div class="img-hover"></div></a>
                            </div>                                
                            <h3><a href="http://videotube.marstheme.com/video/winter-olympics-2014/">Winter Olympics 2014</a></h3>

                            <div class="meta"><span class="date">8 months ago</span>
                                <span class="views"><i class="fa fa-eye"></i>1206</span><span class="heart"><i class="fa fa-heart"></i>46</span>
                                <span class="fcomments"><i class="fa fa-comments"></i>0</span>

                            </div>
                        </div>
                    </div><!-- /#carousel-->
                    <div id="carousel-latest-mars-mainvideo-widgets-5" class="carousel carousel-mars-mainvideo-widgets-5 slide video-section"  data-ride="carousel">
                        <div class="section-header">
                            <h3><i class="fa fa-play"></i> Gaming Videos</h3>
                            <ol class="carousel-indicators section-nav">
                                <li data-target="#carousel-latest-mars-mainvideo-widgets-5" data-slide-to="0" class="bullet active"></li>
                                <li data-target="#carousel-latest-mars-mainvideo-widgets-5" data-slide-to="1" class="bullet"></li> <li data-target="#carousel-latest-mars-mainvideo-widgets-5" data-slide-to="2" class="bullet"></li> 				          
                            </ol>
                        </div>
                        <!-- 2 columns -->
                        <!-- 1 colum -->
                        <div class="gaming-wrapper">
                            <div class="carousel-inner carousel-mars-mainvideo-widgets-5">
                                <div class="item active">
                                    <div class="row">
                                        <div class="col-sm-5 item list">
                                            <div class="item-img">
                                                <a href="http://videotube.marstheme.com/video/hd-music-nexus-playlist/" title="HD Music Nexus Playlist"><img width="295" height="197" src="http://videotube.marstheme.com/wp-content/uploads/2014/07/hd-music-nexus-playlist-295x197.jpg" class="img-responsive wp-post-image" alt="HD Music Nexus Playlist" /></a>                                        		<a href="http://videotube.marstheme.com/video/hd-music-nexus-playlist/"><div class="img-hover"></div></a>
                                            </div>                                        	
                                        </div>
                                        <div class="col-sm-7 item list mars-mainvideo-widgets-5-2132">
                                            <h3><a title="HD Music Nexus Playlist" href="http://videotube.marstheme.com/video/hd-music-nexus-playlist/">HD Music Nexus Playlist</a></h3>

                                            <div class="meta"><span class="date">4 months ago</span>
                                                <span class="views"><i class="fa fa-eye"></i>2522</span><span class="heart"><i class="fa fa-heart"></i>342</span>
                                                <span class="fcomments"><i class="fa fa-comments"></i>0</span>

                                            </div>
                                            <p>Lorem ipsum dolor sit amet, erant complectitur ea nec, no sea vide nullam, ne appareat quaerendum vel. Cu meliore accusata vis, nam facer evertitur cu, id facilisi abhorreant per. Putant animal eu sea, ne quot nihil mediocritatem cum, cu ius torquatos dissentiet contentiones. Eos ex propriae invidunt accusamus, in vidit deterruisset eum, everti ponderum suavitate</p>

                                            <p><a href="http://videotube.marstheme.com/video/hd-music-nexus-playlist/"><i class="fa fa-play-circle"></i>Watch Video</a></p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-5 item list">
                                            <div class="item-img">
                                                <a href="http://videotube.marstheme.com/video/titanfall-launch-trailer/" title="Titanfall Launch Trailer"><img width="295" height="197" src="http://videotube.marstheme.com/wp-content/uploads/2014/03/game6-295x197.jpg" class="img-responsive wp-post-image" alt="game6" /></a>                                        		<a href="http://videotube.marstheme.com/video/titanfall-launch-trailer/"><div class="img-hover"></div></a>
                                            </div>                                        	
                                        </div>
                                        <div class="col-sm-7 item list mars-mainvideo-widgets-5-1721">
                                            <h3><a title="Titanfall Launch Trailer" href="http://videotube.marstheme.com/video/titanfall-launch-trailer/">Titanfall Launch Trailer</a></h3>

                                            <div class="meta"><span class="date">8 months ago</span>
                                                <span class="views"><i class="fa fa-eye"></i>3071</span><span class="heart"><i class="fa fa-heart"></i>150</span>
                                                <span class="fcomments"><i class="fa fa-comments"></i>0</span>

                                            </div>
                                            <p>Lorem ipsum dolor sit amet, erant complectitur ea nec, no sea vide nullam, ne appareat quaerendum vel. Cu meliore accusata vis, nam facer evertitur cu, id facilisi abhorreant per. Putant animal eu sea, ne quot nihil mediocritatem cum, cu ius torquatos dissentiet contentiones. Eos ex propriae invidunt accusamus, in vidit deterruisset eum, everti ponderum suavitate [&hellip;]</p>

                                            <p><a href="http://videotube.marstheme.com/video/titanfall-launch-trailer/"><i class="fa fa-play-circle"></i>Watch Video</a></p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-5 item list">
                                            <div class="item-img">
                                                <a href="http://videotube.marstheme.com/video/trials-frontier/" title="Trials Frontier Motorcycle Game"><img width="295" height="197" src="http://videotube.marstheme.com/wp-content/uploads/2014/03/game3-295x197.jpg" class="img-responsive wp-post-image" alt="game3" /></a>                                        		<a href="http://videotube.marstheme.com/video/trials-frontier/"><div class="img-hover"></div></a>
                                            </div>                                        	
                                        </div>
                                        <div class="col-sm-7 item list mars-mainvideo-widgets-5-199">
                                            <h3><a title="Trials Frontier Motorcycle Game" href="http://videotube.marstheme.com/video/trials-frontier/">Trials Frontier Motorcycle Game</a></h3>

                                            <div class="meta"><span class="date">8 months ago</span>
                                                <span class="views"><i class="fa fa-eye"></i>848</span><span class="heart"><i class="fa fa-heart"></i>14</span>
                                                <span class="fcomments"><i class="fa fa-comments"></i>0</span>

                                            </div>
                                            <p>Aliquam erat volutpat. Maecenas convallis pharetra quam id hendrerit. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Fusce dapibus sapien sed tellus malesuada laoreet. Aliquam non magna vitae ante egestas faucibus sit amet et massa. Sed dictum eros vel faucibus condimentum. Vivamus rutrum tincidunt odio, sit amet volutpat urna lacinia</p>

                                            <p><a href="http://videotube.marstheme.com/video/trials-frontier/"><i class="fa fa-play-circle"></i>Watch Video</a></p>
                                        </div>
                                    </div>
                                </div><div class="item">		                       	                                    <div class="row">
                                        <div class="col-sm-5 item list">
                                            <div class="item-img">
                                                <a href="http://videotube.marstheme.com/video/skyrim/" title="Skyrim"><img width="295" height="197" src="http://videotube.marstheme.com/wp-content/uploads/2014/03/game2-295x197.jpg" class="img-responsive wp-post-image" alt="game2" /></a>                                        		<a href="http://videotube.marstheme.com/video/skyrim/"><div class="img-hover"></div></a>
                                            </div>                                        	
                                        </div>
                                        <div class="col-sm-7 item list mars-mainvideo-widgets-5-52">
                                            <h3><a title="Skyrim" href="http://videotube.marstheme.com/video/skyrim/">Skyrim</a></h3>

                                            <div class="meta"><span class="date">8 months ago</span>
                                                <span class="views"><i class="fa fa-eye"></i>863</span><span class="heart"><i class="fa fa-heart"></i>12</span>
                                                <span class="fcomments"><i class="fa fa-comments"></i>0</span>

                                            </div>
                                            <p>Aenean feugiat in ante et blandit. Vestibulum posuere molestie risus, ac interdum magna porta non. Pellentesque rutrum fringilla elementum. Curabitur tincidunt porta lorem vitae accumsan. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Donec ac euismod turpis. Nullam tincidunt orci nec eros tincidunt ultricies. Phasellus congue ante mi, eu bibendum [&hellip;]</p>

                                            <p><a href="http://videotube.marstheme.com/video/skyrim/"><i class="fa fa-play-circle"></i>Watch Video</a></p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-5 item list">
                                            <div class="item-img">
                                                <a href="http://videotube.marstheme.com/video/wow-retrospective/" title="World of Warcraft Retrospective"><img width="295" height="197" src="http://videotube.marstheme.com/wp-content/uploads/2014/03/game5-295x197.jpg" class="img-responsive wp-post-image" alt="game5" /></a>                                        		<a href="http://videotube.marstheme.com/video/wow-retrospective/"><div class="img-hover"></div></a>
                                            </div>                                        	
                                        </div>
                                        <div class="col-sm-7 item list mars-mainvideo-widgets-5-48">
                                            <h3><a title="World of Warcraft Retrospective" href="http://videotube.marstheme.com/video/wow-retrospective/">World of Warcraft Retrospective</a></h3>

                                            <div class="meta"><span class="date">8 months ago</span>
                                                <span class="views"><i class="fa fa-eye"></i>371</span><span class="heart"><i class="fa fa-heart"></i>4</span>
                                                <span class="fcomments"><i class="fa fa-comments"></i>0</span>

                                            </div>
                                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse eleifend fermentum velit. Aliquam erat volutpat. Duis cursus enim ante, sed tristique orci luctus ac. Nulla euismod nisi sed aliquam fermentum. Donec tempus sem sollicitudin velit gravida pharetra. Integer id lacinia risus, a convallis lorem. Fusce quis elit blandit, luctus felis in, euismod arcu. Integer faucibus [&hellip;]</p>

                                            <p><a href="http://videotube.marstheme.com/video/wow-retrospective/"><i class="fa fa-play-circle"></i>Watch Video</a></p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-5 item list">
                                            <div class="item-img">
                                                <a href="http://videotube.marstheme.com/video/diablo3/" title="Diablo III: Reaper of Souls"><img width="295" height="197" src="http://videotube.marstheme.com/wp-content/uploads/2014/03/game6-295x197.jpg" class="img-responsive wp-post-image" alt="game6" /></a>                                        		<a href="http://videotube.marstheme.com/video/diablo3/"><div class="img-hover"></div></a>
                                            </div>                                        	
                                        </div>
                                        <div class="col-sm-7 item list mars-mainvideo-widgets-5-46">
                                            <h3><a title="Diablo III: Reaper of Souls" href="http://videotube.marstheme.com/video/diablo3/">Diablo III: Reaper of Souls</a></h3>

                                            <div class="meta"><span class="date">8 months ago</span>
                                                <span class="views"><i class="fa fa-eye"></i>380</span><span class="heart"><i class="fa fa-heart"></i>4</span>
                                                <span class="fcomments"><i class="fa fa-comments"></i>1</span>

                                            </div>
                                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse eleifend fermentum velit. Aliquam erat volutpat. Duis cursus enim ante, sed tristique orci luctus ac. Nulla euismod nisi sed aliquam fermentum. Donec tempus sem sollicitudin velit gravida pharetra. Integer id lacinia risus, a convallis lorem. Fusce quis elit blandit, luctus felis in, euismod arcu. Integer faucibus [&hellip;]</p>

                                            <p><a href="http://videotube.marstheme.com/video/diablo3/"><i class="fa fa-play-circle"></i>Watch Video</a></p>
                                        </div>
                                    </div>
                                </div><div class="item">		                       	                                    <div class="row">
                                        <div class="col-sm-5 item list">
                                            <div class="item-img">
                                                <a href="http://videotube.marstheme.com/video/assassins-creed/" title="Assassin&#8217;s Creed 4"><img width="295" height="197" src="http://videotube.marstheme.com/wp-content/uploads/2014/03/game10-295x197.jpg" class="img-responsive wp-post-image" alt="game10" /></a>                                        		<a href="http://videotube.marstheme.com/video/assassins-creed/"><div class="img-hover"></div></a>
                                            </div>                                        	
                                        </div>
                                        <div class="col-sm-7 item list mars-mainvideo-widgets-5-36">
                                            <h3><a title="Assassin&#8217;s Creed 4" href="http://videotube.marstheme.com/video/assassins-creed/">Assassin&#8217;s Creed 4</a></h3>

                                            <div class="meta"><span class="date">8 months ago</span>
                                                <span class="views"><i class="fa fa-eye"></i>710</span><span class="heart"><i class="fa fa-heart"></i>10</span>
                                                <span class="fcomments"><i class="fa fa-comments"></i>0</span>

                                            </div>
                                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse eleifend fermentum velit. Aliquam erat volutpat. Duis cursus enim ante, sed tristique orci luctus ac. Nulla euismod nisi sed aliquam fermentum. Donec tempus sem sollicitudin velit gravida pharetra. Integer id lacinia risus, a convallis lorem. Fusce quis elit blandit, luctus felis in, euismod arcu. Integer faucibus [&hellip;]</p>

                                            <p><a href="http://videotube.marstheme.com/video/assassins-creed/"><i class="fa fa-play-circle"></i>Watch Video</a></p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-5 item list">
                                            <div class="item-img">
                                                <a href="http://videotube.marstheme.com/video/destiny/" title="New Destiny Trailer"><img width="295" height="197" src="http://videotube.marstheme.com/wp-content/uploads/2014/03/game1-295x197.jpg" class="img-responsive wp-post-image" alt="game1" /></a>                                        		<a href="http://videotube.marstheme.com/video/destiny/"><div class="img-hover"></div></a>
                                            </div>                                        	
                                        </div>
                                        <div class="col-sm-7 item list mars-mainvideo-widgets-5-34">
                                            <h3><a title="New Destiny Trailer" href="http://videotube.marstheme.com/video/destiny/">New Destiny Trailer</a></h3>

                                            <div class="meta"><span class="date">8 months ago</span>
                                                <span class="views"><i class="fa fa-eye"></i>3264</span><span class="heart"><i class="fa fa-heart"></i>62</span>
                                                <span class="fcomments"><i class="fa fa-comments"></i>0</span>

                                            </div>
                                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse eleifend fermentum velit. Aliquam erat volutpat. Duis cursus enim ante, sed tristique orci luctus ac. Nulla euismod nisi sed aliquam fermentum. Donec tempus sem sollicitudin velit gravida pharetra. Integer id lacinia risus, a convallis lorem. Fusce quis elit blandit, luctus felis in, euismod arcu. Integer faucibus [&hellip;]</p>

                                            <p><a href="http://videotube.marstheme.com/video/destiny/"><i class="fa fa-play-circle"></i>Watch Video</a></p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-5 item list">
                                            <div class="item-img">
                                                <a href="http://videotube.marstheme.com/video/zelda/" title="Zelda"><img width="295" height="197" src="http://videotube.marstheme.com/wp-content/uploads/2014/03/game7-295x197.jpg" class="img-responsive wp-post-image" alt="game7" /></a>                                        		<a href="http://videotube.marstheme.com/video/zelda/"><div class="img-hover"></div></a>
                                            </div>                                        	
                                        </div>
                                        <div class="col-sm-7 item list mars-mainvideo-widgets-5-26">
                                            <h3><a title="Zelda" href="http://videotube.marstheme.com/video/zelda/">Zelda</a></h3>

                                            <div class="meta"><span class="date">8 months ago</span>
                                                <span class="views"><i class="fa fa-eye"></i>438</span><span class="heart"><i class="fa fa-heart"></i>3</span>
                                                <span class="fcomments"><i class="fa fa-comments"></i>0</span>

                                            </div>
                                            <p>Lorem ipsum dolor sit amet, erant complectitur ea nec, no sea vide nullam, ne appareat quaerendum vel. Cu meliore accusata vis, nam facer evertitur cu, id facilisi abhorreant per. Putant animal eu sea, ne quot nihil mediocritatem cum, cu ius torquatos dissentiet contentiones. Eos ex propriae invidunt accusamus, in vidit deterruisset eum, everti ponderum suavitate [&hellip;]</p>

                                            <p><a href="http://videotube.marstheme.com/video/zelda/"><i class="fa fa-play-circle"></i>Watch Video</a></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end 1 colum -->
                        <!-- 4/6 columns -->
                    </div><!-- /#carousel-->
                    <div class="video-section">
                        <div class="section-header">
                            <h3><i class="fa fa-play"></i> Food &amp; Health Videos</h3>
                        </div>
                        <div class="post-28 page type-page status-publish hentry item list big">
                            <div class="item-img">						<a href="http://videotube.marstheme.com/video/monday-vlog-mlog/"><img width="750" height="500" src="http://videotube.marstheme.com/wp-content/uploads/2014/03/vlog3-750x500.jpg" class="img-responsive wp-post-image" alt="vlog3" /></a>													<a href="http://videotube.marstheme.com/video/monday-vlog-mlog/"><div class="img-hover"></div></a>
                            </div>
                            <h3><a href="http://videotube.marstheme.com/video/monday-vlog-mlog/">Monday Vlog (Mlog)</a></h3>

                            <div class="meta"><span class="date">8 months ago</span>
                                <span class="views"><i class="fa fa-eye"></i>1936</span><span class="heart"><i class="fa fa-heart"></i>103</span>
                                <span class="fcomments"><i class="fa fa-comments"></i>0</span>

                            </div>
                            <p>Aenean feugiat in ante et blandit. Vestibulum posuere molestie risus, ac interdum magna porta non. Pellentesque rutrum fringilla elementum. Curabitur tincidunt porta lorem vitae accumsan. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Donec ac euismod turpis.</p>
                            <p><a href="http://videotube.marstheme.com/video/monday-vlog-mlog/"><i class="fa fa-play-circle"></i>Watch Video</a></p>
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

                    </div><div class="widget widget_text"><h4 class="widget-title">About VideoTube</h4>			<div class="textwidget"><p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. Separated they live in Bookmarksgrove right at the coast of the Semantics, a large language ocean which is great.</p>
                            <p>A small river named Duden flows by their place and supplies it with the necessary regelialia. It is a paradisematic country, in which roasted parts of sentences.</p>
                        </div>
                    </div><div class="widget mars-posts-sidebar-widget"><h4 class="widget-title">Lastest Posts</h4>				        <div class="row">
                            <div id="post-right-mars-posts-sidebar-widget-2-2370" class="post-2370 post type-post status-publish format-standard has-post-thumbnail hentry category-food tag-delicious tag-food-2 tag-recipe-2 col-xs-6 item">
                                <a title="5 Delicious Video Recipes" href="http://videotube.marstheme.com/5-delicious-video-recipes-2/"><img width="165" height="108" src="http://videotube.marstheme.com/wp-content/uploads/2014/03/All-0057-165x108.jpg" class="img-responsive wp-post-image" alt="" /></a>                    <div class="post-header">              	         	
                                    <h3><a title="5 Delicious Video Recipes" href="http://videotube.marstheme.com/5-delicious-video-recipes-2/">5 Delicious Video Recipes</a></h3>
                                    <span class="post-meta">
                                        <i class="fa fa-clock-o"></i> August 21 ,2014						</span>	                	
                                </div>
                            </div>
                            <div id="post-right-mars-posts-sidebar-widget-2-2369" class="post-2369 post type-post status-publish format-standard has-post-thumbnail hentry category-music tag-band tag-live tag-music-3 tag-perfomance col-xs-6 item">
                                <a title="Watch This Awesome Music Perfomance" href="http://videotube.marstheme.com/watch-this-awesome-music-perfomance-2/"><img width="165" height="108" src="http://videotube.marstheme.com/wp-content/uploads/2014/03/DeathtoStock_Band10-165x108.jpg" class="img-responsive wp-post-image" alt="DeathtoStock_Band10" /></a>                    <div class="post-header">              	         	
                                    <h3><a title="Watch This Awesome Music Perfomance" href="http://videotube.marstheme.com/watch-this-awesome-music-perfomance-2/">Watch This Awesome Music Perfomance</a></h3>
                                    <span class="post-meta">
                                        <i class="fa fa-clock-o"></i> August 21 ,2014						</span>	                	
                                </div>
                            </div>
                            <div id="post-right-mars-posts-sidebar-widget-2-2368" class="post-2368 post type-post status-publish format-standard has-post-thumbnail hentry category-viral-videos tag-fail tag-funny tag-videos tag-viral col-xs-6 item">
                                <a title="10 Viral Videos You Have to See" href="http://videotube.marstheme.com/10-viral-videos-you-have-to-see-2/"><img width="165" height="108" src="http://videotube.marstheme.com/wp-content/uploads/2014/03/All-0055-165x108.jpg" class="img-responsive wp-post-image" alt="All-0055" /></a>                    <div class="post-header">              	         	
                                    <h3><a title="10 Viral Videos You Have to See" href="http://videotube.marstheme.com/10-viral-videos-you-have-to-see-2/">10 Viral Videos You Have to See</a></h3>
                                    <span class="post-meta">
                                        <i class="fa fa-clock-o"></i> August 21 ,2014						</span>	                	
                                </div>
                            </div>
                            <div id="post-right-mars-posts-sidebar-widget-2-2367" class="post-2367 post type-post status-publish format-standard has-post-thumbnail hentry category-music tag-band tag-live tag-music-3 tag-perfomance col-xs-6 item">
                                <a title="Watch This Awesome Music Perfomance" href="http://videotube.marstheme.com/watch-this-awesome-music-perfomance-3/"><img width="165" height="108" src="http://videotube.marstheme.com/wp-content/uploads/2014/03/DeathtoStock_Band10-165x108.jpg" class="img-responsive wp-post-image" alt="DeathtoStock_Band10" /></a>                    <div class="post-header">              	         	
                                    <h3><a title="Watch This Awesome Music Perfomance" href="http://videotube.marstheme.com/watch-this-awesome-music-perfomance-3/">Watch This Awesome Music Perfomance</a></h3>
                                    <span class="post-meta">
                                        <i class="fa fa-clock-o"></i> August 21 ,2014						</span>	                	
                                </div>
                            </div>
                        </div>
                    </div><div class="widget mars-videos-sidebar-widget"><h4 class="widget-title">Most Liked videos</h4>				        <div class="row">
                            <div class="col-xs-6 item mars-videos-sidebar-widget-2-1719">
                                <div class="item-img">
                                    <a title="#SELFIE &#8211; The Chainsmokers" href="http://videotube.marstheme.com/video/selfie-the-chainsmokers/"><img width="165" height="108" src="http://videotube.marstheme.com/wp-content/uploads/2014/03/music2-165x108.jpg" class="img-responsive wp-post-image" alt="music2" /></a>						<a href="http://videotube.marstheme.com/video/selfie-the-chainsmokers/"><div class="img-hover"></div></a>
                                </div>	            	
                                <h3><a title="#SELFIE &#8211; The Chainsmokers" href="http://videotube.marstheme.com/video/selfie-the-chainsmokers/">#SELFIE &#8211; The Chainsmokers</a></h3>

                                <div class="meta"><span class="date">8 months ago</span>
                                    <span class="views"><i class="fa fa-eye"></i>4681</span><span class="heart"><i class="fa fa-heart"></i>351</span>
                                    <span class="fcomments"><i class="fa fa-comments"></i>0</span>

                                </div>
                            </div>
                            <div class="col-xs-6 item mars-videos-sidebar-widget-2-2132">
                                <div class="item-img">
                                    <a title="HD Music Nexus Playlist" href="http://videotube.marstheme.com/video/hd-music-nexus-playlist/"><img width="165" height="108" src="http://videotube.marstheme.com/wp-content/uploads/2014/07/hd-music-nexus-playlist-165x108.jpg" class="img-responsive wp-post-image" alt="HD Music Nexus Playlist" /></a>						<a href="http://videotube.marstheme.com/video/hd-music-nexus-playlist/"><div class="img-hover"></div></a>
                                </div>	            	
                                <h3><a title="HD Music Nexus Playlist" href="http://videotube.marstheme.com/video/hd-music-nexus-playlist/">HD Music Nexus Playlist</a></h3>

                                <div class="meta"><span class="date">4 months ago</span>
                                    <span class="views"><i class="fa fa-eye"></i>2522</span><span class="heart"><i class="fa fa-heart"></i>342</span>
                                    <span class="fcomments"><i class="fa fa-comments"></i>0</span>

                                </div>
                            </div>
                            <div class="col-xs-6 item mars-videos-sidebar-widget-2-1903">
                                <div class="item-img">
                                    <a title="Wonderfull Chill Out Music Love" href="http://videotube.marstheme.com/video/wonderfull-chill-out-music-love/"><img width="165" height="108" src="http://videotube.marstheme.com/wp-content/uploads/2014/04/wonderfull-chill-out-music-love-165x108.jpg" class="img-responsive wp-post-image" alt="Wonderfull Chill Out Music Love" /></a>						<a href="http://videotube.marstheme.com/video/wonderfull-chill-out-music-love/"><div class="img-hover"></div></a>
                                </div>	            	
                                <h3><a title="Wonderfull Chill Out Music Love" href="http://videotube.marstheme.com/video/wonderfull-chill-out-music-love/">Wonderfull Chill Out Music Love</a></h3>

                                <div class="meta"><span class="date">7 months ago</span>
                                    <span class="views"><i class="fa fa-eye"></i>3561</span><span class="heart"><i class="fa fa-heart"></i>218</span>
                                    <span class="fcomments"><i class="fa fa-comments"></i>3</span>

                                </div>
                            </div>
                            <div class="col-xs-6 item mars-videos-sidebar-widget-2-2360">
                                <div class="item-img">
                                    <a title="Best TRANCE music 2014" href="http://videotube.marstheme.com/video/best-trance-music-2014/"><img width="165" height="108" src="http://videotube.marstheme.com/wp-content/uploads/2014/09/best-trance-music-2014-165x108.jpg" class="img-responsive wp-post-image" alt="Best TRANCE music 2014" /></a>						<a href="http://videotube.marstheme.com/video/best-trance-music-2014/"><div class="img-hover"></div></a>
                                </div>	            	
                                <h3><a title="Best TRANCE music 2014" href="http://videotube.marstheme.com/video/best-trance-music-2014/">Best TRANCE music 2014</a></h3>

                                <div class="meta"><span class="date">3 months ago</span>
                                    <span class="views"><i class="fa fa-eye"></i>1910</span><span class="heart"><i class="fa fa-heart"></i>164</span>
                                    <span class="fcomments"><i class="fa fa-comments"></i>0</span>

                                </div>
                            </div>
                        </div>
                    </div><div class="widget widget_text"><h4 class="widget-title">Like us on Facebook</h4>			<div class="textwidget"><iframe src="//www.facebook.com/plugins/likebox.php?href=http%3A%2F%2Fwww.facebook.com%2FFacebookDevelopers&amp;width=360&amp;height=290&amp;colorscheme=light&amp;show_faces=true&amp;header=true&amp;stream=false&amp;show_border=false" width="360" height="260"></iframe></div>
                    </div></div><!-- /.sidebar -->		</div><!-- /.row -->
        </div><!-- /.container -->
        <!-- /#footer -->
<?php require_once('footer.php'); ?>