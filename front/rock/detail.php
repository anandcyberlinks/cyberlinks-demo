<?php
require 'header.php';
$recent = recentVideo();
$cat = catList();
if (isset($_GET['v'])) {
    $data = videoDetail($_GET['v'])->result[0];
    $comment = getComment($_GET['v']);
?>
    <div class="clear"></div>
    <div class="inner shadow"></div>
        
        <div class="container row">
            <div class="two-thirds column left">
                <h1 class="heading"><?= $data->title ?></h1>
                <!-- Album Detail Start -->
                <div class="in-sec">
                    <div class="album-detail">
                        <a href="" class="thumb">
                            <iframe height="300px" width="550px" scrolling="no" src="http://182.18.165.43/multitvfinal/index.php/details?id=<?php echo $data->id ?>&device=3g" frameborder="0"  webkitallowfullscreen="" mozallowfullscreen="" allowfullscreen=""></iframe>	                	
                        </a>
                        <div class="clear"></div>
                    </div>
                    <div class="desc">
                        <p class="musicby"><?= $data->title ?></p>
                        <h4>Album Discription</h4>
                        <p class="txt"><?= $data->description ?> </p>
                    </div>

                </div>
                <div class="in-sec">
                    <div class="album-opts">
                        <p class="by">
                            <?php if(isset($_SESSION['ut'])){?>
                                <button class="<?php if($data->liked == '1'){ echo 'unlike'; } else {echo 'like';} ?>" cid="<?php echo $_GET['v'] ?>" value="<?=($data->liked == 1)?0:1;?>"><div id="<?php echo $_GET['v'] ?>"><?php if($data->liked == '1'){ echo 'unlike'; }else{ echo 'like';} ?></div></button>
                        <div class="t<?=$_GET['v']?>"><?php } echo $data->likes . ' like(s)'?></div>    
                        </p>
                        <p class="by">
                                <?php if(isset($_SESSION['ut'])){?>
                                <button class="<?php if($data->favorited == '1'){ echo 'favorited'; } else {echo 'make_favorite';} ?>" cid="<?php echo $_GET['v'] ?>" value="<?=($data->favorited == 1)?0:1;?>"><div id="f<?php echo $_GET['v'] ?>"><?php if($data->favorited == '1'){ echo 'favorited'; }else{ echo 'Make favorite';} ?></div></button>
                            <?php } ?>
                        </p>
                        <div class="share-album">
                            <h6>Share This Album</h6>
                            <!--<span class='st_sharethis_hcount' displayText='ShareThis'></span>-->
                            <span class='st_facebook_hcount' displayText='Facebook'></span>
                            <span class='st_twitter_hcount' displayText='Tweet'></span>
                            <span class='st_linkedin_hcount' displayText='LinkedIn'></span>
                            <!--<span class='st_pinterest_hcount' displayText='Pinterest'></span>-->
                        </div>
                        <!--<div class="availble">
                            <h6>Buy This Album</h6>
                            <a href="#" class="amazon-ind">&nbsp;<span>Amazon</span></a>
                            <a href="#" class="apple-ind">&nbsp;<span>ITUNES</span></a>
                            <a href="#" class="grooveshark-ind">&nbsp;<span>GrooveShark</span></a>
                            <a href="#" class="soundcloud-ind">&nbsp;<span>SoundCloud</span></a>
                        </div>-->
                        <!--<a class="button" href="#">Buy Video</a>-->
                        <div class="clear"></div>
                    </div>
                
                <!-- Comments Start -->
                <div class="comments">
                    <h1 class="heading">comments</h1>
                    <ul>
                        <?php
                        if ($comment->tr != 0) {
                            foreach ($comment->result as $value) {
                                ?>

                                <li>
                                    <div class="avatar">
                                        <a href="#"><img height="50" width="50" src="<?php echo $value->image ?>" alt="" /></a>
                                    </div>
                                    <div class="desc">
                                        <div class="desc-in">
                                            <span class="pointer">&nbsp;</span>
                                            <div class="text-desc">
                                                <h5><?php echo $value->username ?></h5>
                                                <p class="ago"><?php echo $value->created ?></p>
                                                <p class="txt">
                                                    <?php echo $value->comment ?>
                                                </p>
                                                <div class="clear"></div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
        <?php }
    } else {
        echo '<li><h5>No Comment</h5></li>';
    } ?>
                    </ul>
                </div>
                <!-- Comments End -->
                <!-- Leave a Reply Start -->
                <div class="leavereply">
                    <div id="msg"></div>
                    <h1 class="heading">Album comments</h1>
                    <form class="forms" id="comment">
                        <ul>
                            <li>
                                <input name="content_id" type="hidden" value="<?= $_GET['v'] ?>" />
                                <textarea id="comm"  name="comment"/></textarea>
                            </li>
                            <li>
                                <button>Submit Comment</button>
                            </li>
                        </ul>
                    </form>
                </div>
        </div>
        
                <!-- Leave a Reply End -->
            <!-- Album Detail End -->
            <div class="one-third column right">
                <div class="box-small">
                    <h1 class="heading">Latest Videos</h1>
                    <div class="box-in">
                        <!-- Now Playing Start -->
                        <div class="list-thumb">
                            <ul>
    <?php foreach ($recent->result as $value) { ?>
                                    <li>
                                        <a href="detail.php?v=<?= $value->id ?>" class="thumb"><img height="50px" width="70px" src="<?= $value->thumbs->small ?>" alt="" /></a>
                                        <div class="desc">
                                            <h5><a href="detail.php?v=<?= $value->id ?>"><?php echo $value->title ?></a></h5>
                                            <p><?= $value->description ?></p>
                                            <a href="blog-detail.html" class="readmore">Read More</a>
                                        </div>
                                    </li>

    <?php } ?>
                            </ul>
                        </div>
                        <!-- Now Playing End -->
                        <div class="clear"></div>
                    </div>
                </div>
                <div class="box-small">
                    <h1 class="heading">Categories</h1>
                    <div class="box-in">
                        <!-- Now Playing Start -->
                        <div class="widget-links">
                            <ul>
    <?php foreach ($cat->result as $val) { ?>
                                    <li><a href="category.php?c=<?= $val->id ?>"><span><?php echo $val->category ?></span>
                                            <span><?= $val->total ?></span></a></li>
    <?php } ?>
                            </ul>
                        </div>
                        <!-- Now Playing End -->
                        <div class="clear"></div>
                    </div>
                </div>
                <div class="box-small">
                    <div class="box-in">
                        <!-- Now Playing Start -->
                        <a href="#"><img src="images/facebook.jpg" alt="" /></a>
                        <!-- Now Playing End -->
                        <div class="clear"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="clear"></div>
        <?php
    } else {
        echo "<br><br><br><br><br><br><br><br><h1>No record</h1><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>";
    }
    require 'footer.php';
    ?>
    <script>
        $("#comment").submit(function (event) {
            // Stop form from submitting normally
            event.preventDefault();
            var ut = '<?= $_SESSION['ut'] ?>';
            var com = $("#comm").attr('value');
            if(com != ''){
            if (ut != '') {
                $.post("http://182.18.165.43/multitvfinal/apis/users/comment/ut/<?= $_SESSION['ut'] ?>", $("#comment").serialize(), function (data) {
                    $("#comm").attr('value', '');
                    var result = JSON.parse(data);
                    $("#msg").html(result.result);
                    setTimeout(function () {
                        location.reload();
                    }, 500);
                });
            } else {
                $("#msg").html('Please Login');
            }
            }else{
                $("#msg").html('Comment Field is required');
            }
        });
    </script>