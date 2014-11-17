<?php
ini_set('display_errors',1);
require_once('header.php');
$liked = likedVideo(0, 6);
if(isset($_GET['id'])){
$detail = videoDetail($_GET['id']);
$temp = $detail->result[0];
}
//echo '<pre>';print_r($temp);echo '</pre>';
?>
<div class="container">
    <div class="row">
        <div class="col-sm-8">
            <div class="video-info small">

                <h1><?php echo $temp->title ?></h1>

                <span class="views"><i class="fa fa-eye"></i><?php echo $temp->views ?></span>
                <a data-toggle="modal" data-target="#loginmodal" class="option" href="javascript:void(0)" action="like" id="2508">
                    <span class="likes"><i class="fa fa-thumbs-up"></i>
                        <label class="likevideo2508"><?php echo $temp->likes ?></label></span></a>
            </div>

            <div class="videoWrapper player">
                <iframe hight="98%" width="97%" scrolling="no" src="http://182.18.165.252/multitvfinal/index.php/details?id=<?php echo $temp->id ?>&device=3g" frameborder="0"  webkitallowfullscreen="" mozallowfullscreen="" allowfullscreen=""></iframe>	                	
            </div>


            <div id="lightoff"></div>

            <div class="row video-options">
                <div class="col-xs-3">
                    <a href="javascript:void(0)" class="option comments-scrolling">
                        <i class="fa fa-comments"></i>
                        <span class="option-text">Comments</span>
                    </a>
                </div>

                <div class="col-xs-3">
                    <a href="javascript:void(0)" class="option share-button" id="off">
                        <i class="fa fa-share"></i>
                        <span class="option-text">Share</span>
                    </a>
                </div>

                <div class="col-xs-3">
                    <a class="option likes-dislikes" href="javascript:void(0)" action="like" id="2508" video="2508">
                        <i class="fa fa-thumbs-up"></i>
                        <span class="option-text likes-dislikes">
                            <label class="likevideo2508">115</label>
                        </span>
                    </a>
                </div>
                <div class="col-xs-3">
                    <!-- LIGHT SWITCH -->
                    <a href="javascript:void(0)" class="option switch-button">
                        <i class="fa fa-lightbulb-o"></i>
                        <span class="option-text">Turn off Light</span>
                    </a>	
                </div>
            </div>	
            <!-- IF SHARE BUTTON IS CLICKED SHOW THIS -->
            <div class="row social-share-buttons" style="display: none;">
                <div class="col-xs-12">
                    <a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=http://videotube.marstheme.com/video/awesome-film-perfomance/"><img src="http://videotube.marstheme.com/wp-content/themes/videotube/img/facebook.png" alt=""></a>
                    <a target="_blank" href="https://twitter.com/home?status=http://videotube.marstheme.com/video/awesome-film-perfomance/"><img src="http://videotube.marstheme.com/wp-content/themes/videotube/img/twitter.png" alt=""></a>
                    <a target="_blank" href="https://plus.google.com/share?url=http://videotube.marstheme.com/video/awesome-film-perfomance/"><img src="http://videotube.marstheme.com/wp-content/themes/videotube/img/googleplus.png" alt=""></a>
                    <a target="_blank" href="https://pinterest.com/pin/create/button/?url=http://videotube.marstheme.com/video/awesome-film-perfomance/&amp;media=http://videotube.marstheme.com/wp-content/uploads/2014/09/awesome-film-perfomance.jpg&amp;description=Lorem ipsum dolor sit amet, ex vim nostrud phaedrum, docendi facilisi te sed. An molestie inimicus temporibus per, vel debet aeque consequat et. Usu insolens deserunt suscipiantur et, ne enim laudem iudicabit his. Dicam luptatum interpretaris te his, quo te nisl nostrum, id lobortis senserit contentiones cum. Dicant corpora platonem usu ad, his sint officiis ad, et usu oratio cetero iisque.

                       Choro interesset ei vis. Prompta singulis gubergren eum no, ex mei mazim constituto. Ad appareat erroribus est, at legendos petentium vix, ei mea timeam nostrum. His soluta possim no. Probo contentiones ut usu, dicam aliquando ius ad."><img src="http://videotube.marstheme.com/wp-content/themes/videotube/img/pinterest.png" alt=""></a>
                    <a target="_blank" href="http://www.reddit.com/submit?url"><img src="http://videotube.marstheme.com/wp-content/themes/videotube/img/reddit.png" alt=""></a>
                    <a target="_blank" href="https://www.linkedin.com/shareArticle?mini=true&amp;url=http://videotube.marstheme.com/video/awesome-film-perfomance/&amp;title=Awesome Film Perfomance&amp;summary=Lorem ipsum dolor sit amet, ex vim nostrud phaedrum, docendi facilisi te sed. An molestie inimicus temporibus per, vel debet aeque consequat et. Usu insolens deserunt suscipiantur et, ne enim laudem iudicabit his. Dicam luptatum interpretaris te his, quo te nisl nostrum, id lobortis senserit contentiones cum. Dicant corpora platonem usu ad, his sint officiis ad, et usu oratio cetero iisque.

                       Choro interesset ei vis. Prompta singulis gubergren eum no, ex mei mazim constituto. Ad appareat erroribus est, at legendos petentium vix, ei mea timeam nostrum. His soluta possim no. Probo contentiones ut usu, dicam aliquando ius ad.&amp;source=http://videotube.marstheme.com"><img src="http://videotube.marstheme.com/wp-content/themes/videotube/img/linkedin.png" alt=""></a>
                    <a target="_blank" href="http://www.odnoklassniki.ru/dk?st.cmd=addShare&amp;st._surl=http://videotube.marstheme.com/video/awesome-film-perfomance/&amp;title=Awesome Film Perfomance"><img src="http://videotube.marstheme.com/wp-content/themes/videotube/img/odnok.png" alt=""></a>
                    <a target="_blank" href="http://vkontakte.ru/share.php?url=http://videotube.marstheme.com/video/awesome-film-perfomance/"><img src="http://videotube.marstheme.com/wp-content/themes/videotube/img/vkontakte.png" alt=""></a>
                    <a href="mailto:?Subject=Awesome Film Perfomance&amp;Body=I saw this and thought of you! http://videotube.marstheme.com/video/awesome-film-perfomance/"><img src="http://videotube.marstheme.com/wp-content/themes/videotube/img/email.png" alt=""></a>
                </div>
            </div>
            <div class="video-details">
                <span class="date">Published on Sep 29, 2014 by <a href="http://videotube.marstheme.com/author/demo/">ovxsolutions.com</a></span>
                <div class="post-entry"><p>Lorem ipsum dolor sit amet, ex vim nostrud phaedrum, docendi facilisi te sed. An molestie inimicus temporibus per, vel debet aeque consequat et. Usu insolens deserunt suscipiantur et, ne enim laudem iudicabit his. Dicam luptatum interpretaris te his, quo te nisl nostrum, id lobortis senserit contentiones cum. Dicant corpora platonem usu ad, his sint officiis ad, et usu oratio cetero iisque.</p>
                    <p>Choro interesset ei vis. Prompta singulis gubergren eum no, ex mei mazim constituto. Ad appareat erroribus est, at legendos petentium vix, ei mea timeam nostrum. His soluta possim no. Probo contentiones ut usu, dicam aliquando ius ad.<span itemprop="video" itemscope="" itemtype="http://schema.org/VideoObject"><meta itemprop="name" content="Awesome Film Perfomance"><meta itemprop="thumbnailURL" content="http://videotube.marstheme.com/wp-content/uploads/2014/09/awesome-film-perfomance-videotube-a-responsive-video-wordpress-theme.jpg"><meta itemprop="description" content="Lorem ipsum dolor sit amet, ex vim nostrud phaedrum, docendi facilisi te sed. An molestie inimicus temporibus per, vel debet aeque consequat et. Usu insolens deserunt suscipiantur et, ne enim laudem iudicabit his. Dicam luptatum interpretaris te his, quo te nisl nostrum, id lobortis senserit content"><meta itemprop="uploadDate" content="2014-09-29T03:20:45+00:00"><meta itemprop="embedURL" content="https://www.vimeo.com/moogaloop.swf?clip_id=106786265"><meta itemprop="duration" content="PT9M18S"></span></p>
                </div>
                <span class="meta"><span class="meta-info">Category</span> <a href="http://videotube.marstheme.com/categories/music/" rel="tag">Music</a> <a href="http://videotube.marstheme.com/categories/sports/" rel="tag">Sports</a> <a href="http://videotube.marstheme.com/categories/trailers/" rel="tag">Trailers</a></span>
                <span class="meta"></span>
            </div>
            <div id="carousel-latest-mars-relatedvideo-widgets-2" class="carousel carousel-mars-relatedvideo-widgets-2 slide video-section" data-ride="carousel">
                <div class="section-header">

                    <h3>Related Videos</h3>
                    <ol class="carousel-indicators section-nav">
                        <li data-target="#carousel-latest-mars-relatedvideo-widgets-2" data-slide-to="0" class="bullet active"></li>
                        <li data-target="#carousel-latest-mars-relatedvideo-widgets-2" data-slide-to="1" class="bullet"></li> <li data-target="#carousel-latest-mars-relatedvideo-widgets-2" data-slide-to="2" class="bullet"></li> 				            </ol>
                </div>
                <div class="latest-wrapper">
                    <div class="row">
                        <div class="carousel-inner">
                            <div class="item active">

                                <div class="col-sm-4 col-xs-6 item mars-relatedvideo-widgets-2-2360">
                                    <div class="item-img">
                                        <a href="http://videotube.marstheme.com/video/best-trance-music-2014/"><img width="230" height="150" src="http://videotube.marstheme.com/wp-content/uploads/2014/09/best-trance-music-2014-230x150.jpg" class="img-responsive wp-post-image" alt="Best TRANCE music 2014"></a>												<a href="http://videotube.marstheme.com/video/best-trance-music-2014/"><div class="img-hover"></div></a>
                                    </div>				                                
                                    <h3><a title="Best TRANCE music 2014" href="http://videotube.marstheme.com/video/best-trance-music-2014/">Best TRANCE music 2014</a></h3>

                                    <div class="meta"><span class="date">3 months ago</span>
                                        <span class="views"><i class="fa fa-eye"></i>1993</span><span class="heart"><i class="fa fa-heart"></i>177</span>
                                        <span class="fcomments"><i class="fa fa-comments"></i>0</span>

                                    </div>
                                </div> 

                                <div class="col-sm-4 col-xs-6 item mars-relatedvideo-widgets-2-2132">
                                    <div class="item-img">
                                        <a href="http://videotube.marstheme.com/video/hd-music-nexus-playlist/"><img width="230" height="150" src="http://videotube.marstheme.com/wp-content/uploads/2014/07/hd-music-nexus-playlist-230x150.jpg" class="img-responsive wp-post-image" alt="HD Music Nexus Playlist"></a>												<a href="http://videotube.marstheme.com/video/hd-music-nexus-playlist/"><div class="img-hover"></div></a>
                                    </div>				                                
                                    <h3><a title="HD Music Nexus Playlist" href="http://videotube.marstheme.com/video/hd-music-nexus-playlist/">HD Music Nexus Playlist</a></h3>

                                    <div class="meta"><span class="date">4 months ago</span>
                                        <span class="views"><i class="fa fa-eye"></i>2578</span><span class="heart"><i class="fa fa-heart"></i>358</span>
                                        <span class="fcomments"><i class="fa fa-comments"></i>0</span>

                                    </div>
                                </div> 

                                <div class="col-sm-4 col-xs-6 item mars-relatedvideo-widgets-2-1903">
                                    <div class="item-img">
                                        <a href="http://videotube.marstheme.com/video/wonderfull-chill-out-music-love/"><img width="230" height="150" src="http://videotube.marstheme.com/wp-content/uploads/2014/04/wonderfull-chill-out-music-love-230x150.jpg" class="img-responsive wp-post-image" alt="Wonderfull Chill Out Music Love"></a>												<a href="http://videotube.marstheme.com/video/wonderfull-chill-out-music-love/"><div class="img-hover"></div></a>
                                    </div>				                                
                                    <h3><a title="Wonderfull Chill Out Music Love" href="http://videotube.marstheme.com/video/wonderfull-chill-out-music-love/">Wonderfull Chill Out Music Love</a></h3>

                                    <div class="meta"><span class="date">7 months ago</span>
                                        <span class="views"><i class="fa fa-eye"></i>3655</span><span class="heart"><i class="fa fa-heart"></i>221</span>
                                        <span class="fcomments"><i class="fa fa-comments"></i>3</span>

                                    </div>
                                </div> 
                            </div><div class="item">			                       		
                                <div class="col-sm-4 col-xs-6 item mars-relatedvideo-widgets-2-1721">
                                    <div class="item-img">
                                        <a href="http://videotube.marstheme.com/video/titanfall-launch-trailer/"><img width="230" height="150" src="http://videotube.marstheme.com/wp-content/uploads/2014/03/game6-230x150.jpg" class="img-responsive wp-post-image" alt="game6"></a>												<a href="http://videotube.marstheme.com/video/titanfall-launch-trailer/"><div class="img-hover"></div></a>
                                    </div>				                                
                                    <h3><a title="Titanfall Launch Trailer" href="http://videotube.marstheme.com/video/titanfall-launch-trailer/">Titanfall Launch Trailer</a></h3>

                                    <div class="meta"><span class="date">8 months ago</span>
                                        <span class="views"><i class="fa fa-eye"></i>3120</span><span class="heart"><i class="fa fa-heart"></i>159</span>
                                        <span class="fcomments"><i class="fa fa-comments"></i>0</span>

                                    </div>
                                </div> 

                                <div class="col-sm-4 col-xs-6 item mars-relatedvideo-widgets-2-1719">
                                    <div class="item-img">
                                        <a href="http://videotube.marstheme.com/video/selfie-the-chainsmokers/"><img width="230" height="150" src="http://videotube.marstheme.com/wp-content/uploads/2014/03/music2-230x150.jpg" class="img-responsive wp-post-image" alt="music2"></a>												<a href="http://videotube.marstheme.com/video/selfie-the-chainsmokers/"><div class="img-hover"></div></a>
                                    </div>				                                
                                    <h3><a title="#SELFIE – The Chainsmokers" href="http://videotube.marstheme.com/video/selfie-the-chainsmokers/">#SELFIE – The Chainsmokers</a></h3>

                                    <div class="meta"><span class="date">8 months ago</span>
                                        <span class="views"><i class="fa fa-eye"></i>4730</span><span class="heart"><i class="fa fa-heart"></i>371</span>
                                        <span class="fcomments"><i class="fa fa-comments"></i>0</span>

                                    </div>
                                </div> 

                                <div class="col-sm-4 col-xs-6 item mars-relatedvideo-widgets-2-1717">
                                    <div class="item-img">
                                        <a href="http://videotube.marstheme.com/video/godzilla-official-main-trailer/"><img width="230" height="150" src="http://videotube.marstheme.com/wp-content/uploads/2014/03/game9-230x150.jpg" class="img-responsive wp-post-image" alt="game9"></a>												<a href="http://videotube.marstheme.com/video/godzilla-official-main-trailer/"><div class="img-hover"></div></a>
                                    </div>				                                
                                    <h3><a title="Godzilla Official Main Trailer" href="http://videotube.marstheme.com/video/godzilla-official-main-trailer/">Godzilla Official Main Trailer</a></h3>

                                    <div class="meta"><span class="date">8 months ago</span>
                                        <span class="views"><i class="fa fa-eye"></i>2442</span><span class="heart"><i class="fa fa-heart"></i>145</span>
                                        <span class="fcomments"><i class="fa fa-comments"></i>0</span>

                                    </div>
                                </div> 
                            </div><div class="item">			                       		
                                <div class="col-sm-4 col-xs-6 item mars-relatedvideo-widgets-2-265">
                                    <div class="item-img">
                                        <a href="http://videotube.marstheme.com/video/winter-olympics-2014/"><img width="230" height="150" src="http://videotube.marstheme.com/wp-content/uploads/2014/03/sport2-230x150.jpg" class="img-responsive wp-post-image" alt="sport2"></a>												<a href="http://videotube.marstheme.com/video/winter-olympics-2014/"><div class="img-hover"></div></a>
                                    </div>				                                
                                    <h3><a title="Winter Olympics 2014" href="http://videotube.marstheme.com/video/winter-olympics-2014/">Winter Olympics 2014</a></h3>

                                    <div class="meta"><span class="date">8 months ago</span>
                                        <span class="views"><i class="fa fa-eye"></i>1224</span><span class="heart"><i class="fa fa-heart"></i>50</span>
                                        <span class="fcomments"><i class="fa fa-comments"></i>0</span>

                                    </div>
                                </div> 

                                <div class="col-sm-4 col-xs-6 item mars-relatedvideo-widgets-2-263">
                                    <div class="item-img">
                                        <a href="http://videotube.marstheme.com/video/sprite-slam-dunk-contest-2014/"><img width="230" height="150" src="http://videotube.marstheme.com/wp-content/uploads/2014/03/sport3-230x150.jpg" class="img-responsive wp-post-image" alt="sport3"></a>												<a href="http://videotube.marstheme.com/video/sprite-slam-dunk-contest-2014/"><div class="img-hover"></div></a>
                                    </div>				                                
                                    <h3><a title="Sprite Slam Dunk Contest 2014" href="http://videotube.marstheme.com/video/sprite-slam-dunk-contest-2014/">Sprite Slam Dunk Contest 2014</a></h3>

                                    <div class="meta"><span class="date">8 months ago</span>
                                        <span class="views"><i class="fa fa-eye"></i>740</span><span class="heart"><i class="fa fa-heart"></i>19</span>
                                        <span class="fcomments"><i class="fa fa-comments"></i>0</span>

                                    </div>
                                </div> 

                                <div class="col-sm-4 col-xs-6 item mars-relatedvideo-widgets-2-260">
                                    <div class="item-img">
                                        <a href="http://videotube.marstheme.com/video/2014-world-cup-group-g-preview/"><img width="230" height="150" src="http://videotube.marstheme.com/wp-content/uploads/2014/03/sport4-230x150.jpg" class="img-responsive wp-post-image" alt="sport4"></a>												<a href="http://videotube.marstheme.com/video/2014-world-cup-group-g-preview/"><div class="img-hover"></div></a>
                                    </div>				                                
                                    <h3><a title="2014 World Cup: Group G Preview" href="http://videotube.marstheme.com/video/2014-world-cup-group-g-preview/">2014 World Cup: Group G Preview</a></h3>

                                    <div class="meta"><span class="date">8 months ago</span>
                                        <span class="views"><i class="fa fa-eye"></i>612</span><span class="heart"><i class="fa fa-heart"></i>12</span>
                                        <span class="fcomments"><i class="fa fa-comments"></i>0</span>

                                    </div>
                                </div> 
                            </div> 
                        </div>
                    </div>
                </div>
            </div><!-- /#carousel-->
            <div class="widget widget_text">			<div class="textwidget"><img src="http://placehold.it/750x100"></div>
            </div>								<div class="comments">
                <div class="section-header">
                    <h3>no comment</h3>
                </div>

                <ul class="list-unstyled comment-list">
                </ul>




                <div id="respond" class="comment-respond">
                    <h3 id="reply-title" class="comment-reply-title">Add your comment <small><a rel="nofollow" id="cancel-comment-reply-link" href="/video/awesome-film-perfomance/#respond" style="display:none;">Cancel Reply</a></small></h3>
                    <form action="http://videotube.marstheme.com/wp-comments-post.php" method="post" id="commentform" class="comment-form">
                        <p class="comment-notes">Your email address will not be published.</p>							
                        <div class="form-group">
                            <label for="author">Your Name</label>
                            <input type="text" class="form-control" id="author" name="author" placeholder="Enter name" value="">
                        </div>			


                        <div class="form-group">
                            <label for="email">Your Email</label>
                            <input type="text" class="form-control" id="email" name="email" placeholder="Enter Email" value="">
                        </div>


                        <div class="form-group">
                            <label for="url">Website</label>
                            <input type="text" class="form-control" id="url" name="url" placeholder="Enter Website" value="">
                        </div>


                        <div class="form-group">
                            <label for="comment">Comment</label>
                            <textarea class="form-control" aria-required="true" id="comment" name="comment" rows="6"></textarea>
                        </div>
                        <p class="form-submit">
                            <input name="submit" type="submit" id="submit" value="Submit" class="btn btn-default">
                            <input type="hidden" name="comment_post_ID" value="2508" id="comment_post_ID">
                            <input type="hidden" name="comment_parent" id="comment_parent" value="0">
                        </p>
                        <p style="display: none;"><input type="hidden" id="akismet_comment_nonce" name="akismet_comment_nonce" value="c8452f965d"></p><p style="display: none;"></p>					<input type="hidden" id="ak_js" name="ak_js" value="1415777449880"></form>
                </div><!-- #respond -->
            </div>			</div>

            
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
                                    <a title="<?=$val->title ?>" href="<?php echo BASEURL.'play.php?id='.$val->id ?>">
                                    <img width="165" height="108" src="<?=$val->thumbs->large ?>" class="img-responsive wp-post-image" alt="music2" /></a>
                                    <a href="<?php echo BASEURL.'play.php?id='.$val->id ?>"><div class="img-hover"></div></a>
                                </div>	            	
                                <h3><a title="<?=$val->title ?>" href="<?php echo BASEURL.'play.php?id='.$val->id ?>"><?=$val->title ?></a></h3>

                                <div class="meta"><span class="date"><?php echo dateFormat($val->created); ?></span>
                                    <span class="views"><i class="fa fa-eye"></i><?=$val->views ?></span><span class="heart">
                                    <i class="fa fa-heart"></i><?=$val->likes ?></span>
                                    <span class="fcomments"><i class="fa fa-comments"></i><?=$val->comments ?></span>

                                </div>
                            </div>
                            <?php } ?>

                        </div>
                    </div>
                </div><!-- /.sidebar -->	
            
            
            
    </div><!-- /.row -->
</div>
<?php require_once('footer.php'); ?>
