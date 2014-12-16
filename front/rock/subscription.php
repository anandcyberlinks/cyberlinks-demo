<?php
require 'header.php';
if(isset($_POST['cart'])){
    $val = apicall('http://182.18.165.43/multitvfinal/api/subscription/checkout/token/'.$_SESSION['ut'].'/callback/www.google.com/', $_POST); ?>  
<script>
    window.location = '<?=json_decode($val)->result?>';
    </script>
<?php }

$data = subList($_GET['v']);
//echo '<pre>';print_r($data->result->package);
?>
<div class="container row">
    <div class="two-thirds column left">
        
            <table align="center">
                <tr>
                    <td colspan="3"><img src="<?= $data->result->video->image->medium ?>" /></td>
                </tr>
                <tr>
                    <td colspan="3"><h1 class="heading">Subscription For <?= $data->result->video->title ?></h1></td>
                </tr>
                <tr>
                    <th>Days</th>
                    <th>Amount</th>
                    <td>Subscribe</td>
                </tr>

                <?php foreach ($data->result->video->subscription as $value) { //echo "<pre>"; print_r($value); ?>
                <form action="" method="POST">
                    <tr>
                        <td align="center"><?= $value->days ?></td>
                        <td align="center"><?= $value->amount ?></td>
                        <?php
                        $cart = array('subscription_id'=>$value->subscription_id, 
                            'subscription_name'=>$value->subscription_name,
                                'amount'=>$value->amount);
                        ?>
                            <input type="hidden" name="cart" value='<?php echo json_encode($cart);?>' >
                            <input type="hidden" name="user_id" value="<?=$_SESSION['userDetail']->id ?>" >
                            <input type="hidden" name="total_amount" value="<?= $value->amount?>" >
                            <input type="hidden" name="action" value="process">
                        <td align="right"><button class="backcolr">Subscribe</button></td>
                    </tr>

                    

                </form>
                <?php } ?>
                
            </table>
        
    </div>
    <?php if(isset($data->result->package)){ ?>
    <div class="one-third column left">
        <?php foreach ($data->result->package as $pack) { //echo '<pre>'; print_r($pack); ?>

            <div class="box-small">
                <h1 class="heading"><?= $pack->title ?></h1>
                <div class="box-in">
                    <!-- Now Playing Start -->
                    <div class="list-thumb">
                        <ul>
                            <li>
                                <form action="" method="POST">
                                    <table align="center" width="100%">
                                        <tr>
                                            <th>Select</th>
                                            <th>Days</th>
                                            <th>Amount</th>
                                        </tr>

                                        <?php foreach ($pack->subscription as $value) { ?>
                                            <tr>
                                                <td align="center"><input type="radio" name="subscription_id" value="<?= $value->subscription_id ?>"</td>
                                                <td align="center"><?= $value->days ?></td>
                                                <td align="center"><?= $value->amount ?></td>
                                            </tr>

                                        <?php } ?>
                                        <tr>
                                            <td colspan="3" align="right"><button class="backcolr">Subscribe</button></td>
                                        </tr>
                                    </table>
                                </form>



                            </li>

                            <?php foreach ($pack->info as $value) { ?>
                                <li>
                                    <a href="" class="thumb"><img height="50px" width="70px" src="<?= $value->image->small ?>" alt="" /></a>
                                    <div class="desc">
                                        <h5><a href=""><?php echo $value->title ?></a></h5>
                                    </div>
                                </li>

                            <?php } ?>
                            <div class="clearfix">&nbsp;</div>
                        </ul>
                    </div>
                    <!-- Now Playing End -->
                    <div class="clear"></div>
                </div>
            </div>
        <?php } ?>
    </div>
    <?php } ?>




</div>
<?php require 'footer.php'; ?>