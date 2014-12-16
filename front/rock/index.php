<?php
include 'header.php';

$lt = 5;
if (isset($_GET['s'])) {
    $val = $_GET['s'];
} else {
    $val = "";
}
$k = 'title';
if (isset($_GET['k'])) {
    $k = $_GET['k'];
}
$cur = isset($_GET['p']) ? $_GET['p'] : 1;
$ob = isset($_GET['ob']) ? $_GET['ob'] : 'latest';
if (isset($val) && $val != '') {
    $data = searchVideo(($cur - 1) * $lt, $lt, $val, $ob, $k);
} else {
    $data = videoList(($cur - 1) * $lt, $lt);
}
$recent = recentVideo();
//print_r($recent); 
//$recent = recentVideo(0, 4);
$pagination = range(1, $data->tr, $lt);
$totalPage = floor($data->tr / $lt) + 1;
//echo "<pre>"; print_r($data);
?>
<div class="container row">
    <div class="two-thirds column left">
        <h1 class="heading">From our blogs</h1>
        <!-- Blog Post Start -->
        <?php
        foreach ($data->result as $value) {
            //echo "<pre>";print_r($value); 
            ?>
            <div class="in-sec">
                <div class="blog-detail">
                    <a href="<?=getLink($value->id, $value->price) ?>" class="thumb">
                        <img src="<?= $value->thumbs->large ?>" alt="" />
                        <span class="featured"><?php
                            if ($value->featured == 1) {
                                echo 'Featured';
                            }
                            ?></span></a>
                    <div class="blog-opts">
                        <div class="date">
                            <h6><?php echo date('l',strtotime($value->created)) ?></h6>
                            <h1><?php echo date('dM',strtotime($value->created)) ?></h1>
                        </div>
                        <div class="desc">
                            <h4><a href="<?=getLink($value->id, $value->price) ?>"><?php echo $value->title ?></a></h4>
                        <p class="by"><?php echo $value->likes . ' like(s)'?></p>
                        <p class="by">
                            <?php if(isset($_SESSION['ut'])){?>
                                <button class="<?php if($value->liked == '1'){ echo 'unlike'; } else {echo 'like';} ?>" cid="<?php echo $value->id ?>" value="<?=($value->liked == 1)?0:1;?>"><div id="<?php echo $value->id ?>"><?php if($value->liked == '1'){ echo 'unlike'; }else{ echo 'like';} ?></div></button>
                            <?php }?>
                        </p>
                        <p class="by"><?= $value->comments ?> Comments</p>
                        <p class="by">
                                <?php if(isset($_SESSION['ut'])){?>
                                <button class="<?php if($value->favorited == '1'){ echo 'favorited'; } else {echo 'make_favorite';} ?>" cid="<?php echo $value->id ?>" value="<?=($value->favorited == 1)?0:1;?>"><div id="f<?php echo $value->id ?>"><?php if($value->favorited == '1'){ echo 'favorited'; }else{ echo 'Make favorite';} ?></div></button>
                            <?php } ?>
                        </p>
                            <?php if(!in_array($value->price,array('Free','free'))) { ?>
                            <p class="tags">
                                <a href="<?=getLink($value->id, $value->price) ?>">Buy Now</a>
                            </p>
                            <?php } ?>
                            <div class="clear"></div>
                        </div>
                    </div>
                    <div class="blog-desc">
                        <p class="txt">
                            <?php echo $value->description ?>
                        </p>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
            <?php }
        if ($data->tr > $lt) {
            ?>
            <div class="in-sec">
                
                <ul class="pagination">
                    <?php
                    foreach ($pagination as $key => $val) {
                        $page = $key + 1;
                        $qs = http_build_query(array_merge($_GET, array('p' => $page)));
                        if ($page == count($pagination)) {
                            echo sprintf('<li class="%s"><a href="%s">Last →</a></li>', ($page) == $cur ? 'pactive' : '', '?' . $qs);
                        } else {
                            echo sprintf('<li class="%s"><a href="%s">%d</a></li>', ($page) == $cur ? 'pactive ' : '', '?' . $qs, $page);
                        }
                    }
                    ?>
                </ul>
                <div class="clear"></div>
            </div>
<?php } ?>
        <!-- Blog Post End -->
    </div>
    <div class="one-third column left">
        <div class="box-small">
            <h1 class="heading">Latest Videos</h1>
            <div class="box-in">
                <!-- Now Playing Start -->
                <div class="list-thumb">
                    <ul>
                    <?php foreach ($recent->result as $value) { ?>
                            <li>
                                <a href="<?=getLink($value->id, $value->price) ?>" class="thumb"><img height="50px" width="70px" src="<?= $value->thumbs->small ?>" alt="" /></a>
                                <div class="desc">
                                    <h5><a href="<?=getLink($value->id, $value->price) ?>"><?php echo $value->title ?></a></h5>
                                    <p><?= $value->description ?></p>
                                    <a href="<?=getLink($value->id, $value->price) ?>" class="readmore">Read More</a>
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
                        <li><a href="category.php?c=<?= $val->id ?>">
                                    <span><?php echo $val->category ?></span>
                                    <span><?php echo $val->total ?></span></a>
                            </li>
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
                <a href="<?=$link ?>"><img src="images/facebook.jpg" alt="" /></a>
                <!-- Now Playing End -->
                <div class="clear"></div>
            </div>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>

