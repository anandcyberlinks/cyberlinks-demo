<?php require 'header.php'; $result = json_decode(pages('about_us')->result) ?>
        <div class="clear"></div>
        <div class="inner shadow">
            <!-- Sub Page Banner End -->
            <div class="clear"></div>
        	<!-- Container Start -->
            <div class="container row">
            	<div class="sixteen columns left">
                	<h1 class="heading">about us</h1>
                    <!-- Static Page Start -->
                    <div class="in-sec nopad-bot">
                    	<div class="static">
                            <h4><?=$result->header?></h4>
                            <p><?=$result->body?></p>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <!-- Static Page End -->
                </div>
            </div>
            <div class="clear"></div>
<?php require 'footer.php'; ?>