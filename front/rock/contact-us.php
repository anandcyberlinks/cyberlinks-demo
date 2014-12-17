<?php require 'header.php'; $result = json_decode(pages('contact_us')->result) ;?>
        <div class="clear"></div>
        <div class="inner shadow">
            <!-- Second Level Navigation End -->
            <div class="clear"></div>
            <!-- Sub Page Banner Start -->
            <div id="sub-banner" class="row">
            	<iframe width="100%" height="228" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/?ie=UTF8&amp;ll=51.504375,-0.215671&amp;spn=0.00947,0.026157&amp;t=m&amp;z=16&amp;output=embed"></iframe>
            </div>
            <!-- Sub Page Banner End -->
            <div class="clear"></div>
        	<!-- Container Start -->
            <div class="container row">
            	<div class="two-thirds column left">
                	<h1 class="heading">Contact Us</h1>
                    <!-- Contact Us Start -->
                    <div class="in-sec">
                        <div class="contact-us">
                        	<h2><?=$result->header?></h2>
                            <p>
                                <?=$result->body?>
                            </p>
                            <div class="clear"></div>
                            <!-- Quick Inquary Start -->
                            <div class="quickinquiry">
                            	<h2>Quick Inquary</h2>
                                <form class="forms" id="contact" action="mail.php">
                                    <ul>
                                        <li>
                                            <label for="name" generated="true" class="error"></label>
                                            <input name="name" placeholder="Enter Name" type="text" />
                                        </li>
                                        <li>
                                            <label for="email" generated="true" class="error"></label>
                                            <input name="email" placeholder="Enter Email" type="text" />
                                        </li>
                                        <li>
                                            <label for="coment" generated="true" class="error"></label>
                                            <textarea name="coment" placeholder="Enter Massage"></textarea>
                                            <div id="msg" style="color: green"></div>
                                        </li>
                                        <li>
                                            <button onclick="return SubmitIfValid();">Submit Comment</button>
                                        </li>
                                    </ul>
                                </form>
                            </div>
                            <!-- Quick Inquary End -->
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>
                <div class="one-third column left">
                	<div class="box-small">
                    	<h1 class="heading">Contact Info</h1>
                        <div class="box-in">
                        	<!-- Now Playing Start -->
                        	<div class="contact-widget">
                            	<p><?=$result->contact_info->address?>
                                <p>
                                	Call: <?=$result->contact_info->phone?><br />
					Email: <a href="mailto:<?=$result->contact_info->email?>"><?=$result->contact_info->email?></a><br />
                                </p>
                                
                            </div>
                            <!-- Now Playing End -->
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="clear"></div>
<?php require 'footer.php'; ?>