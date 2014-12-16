<?php
require 'header.php';
$lt = 8;
$cur = isset($_GET['p']) ? $_GET['p'] : 1;
$ob = isset($_GET['ob']) ? $_GET['ob'] : 'latest';
$data = liked(($cur - 1) * $lt, $lt);
//print_r($data);
//print_r($data); 
//$recent = recentVideo(0, 4);
$pagination = range(1, $data->tr, $lt);
$totalPage = floor($data->tr / $lt) + 1;
?>
<div class="container row">
    <div class="sixteen columns left">
        <h1 class="heading">Your Liked Video</h1>
        <!-- Album Detail Start -->
        <div class="in-sec nopad-bot">
            <div id="tab-rock">
                <ul class="prod-list ">
                    <?php foreach ($data->result as $value) { ?>
                        <li>
                            <a href="<?=getLink($value->id, $value->price) ?>" class="thumb">
                                <img src="<?= $value->thumbs->small ?>" alt=""><span>&nbsp;</span></a>
                            <h4 class="title"><a href="<?=getLink($value->id, $value->price) ?>"><?=$value->title?></a></h4>
                            <div class="prod-opts">
                                <h6>BUY NOW</h6>
                                <a href="#" class="amazon">&nbsp;<span>Amazon</span></a>
                                <a href="#" class="apple">&nbsp;<span>ITUNES</span></a>
                                <a href="#" class="grooveshark">&nbsp;<span>GrooveShark</span></a>
                                <a href="#" class="soundcloud">&nbsp;<span>SoundCloud</span></a>
                            </div>
                        </li>
                    <?php } ?>
                </ul>
                <div class="clear"></div>
            </div>
            <div class="clear"></div>
        </div>
        <!-- Tabs Section End -->
        <div class="clear"></div>
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
    </div>
</div>
<?php require 'footer.php'; ?>