            <div class="clear"></div>
            <!-- Footer Start -->
            <div id="footer">
            	<div class="foot-top">
                	<!-- Footer Logo Start -->
                    <div class="logo-foot">
                        <a href="#"><img height="50px" width="100px" src="<?php echo $apdata->logo ?>" alt="" /></a>
                    </div>
                    <!-- Footer Logo End -->
                    <!-- Footer Navigation Start -->
                    <div class="links-foot">
                    	<ul>
                            <li><a href="index.php">Home</a></li>
			    <li><a href="populer.php">Popular</a></li>
                            <li><a href="#">Contact</a></li>
                        </ul>
                    </div>
                    <!-- Footer Navigation End -->
                    <!-- Newsletter Start -->
                    <!--<div class="newsletter">
                    	<h5 class="white">NEWSLETTER</h5>
                        <ul>
                        	<li class="left">
                            	<input name="" value="Enter Email Address"
                                onfocus="if(this.value=='Enter Email Address') {this.value='';}"
                                onblur="if(this.value=='') {this.value='Enter Email Address';}" type="text" class="bar" />    
                            </li>
                            <li class="right"><button class="backcolr">Submit</button></li>
                        </ul>
                    </div>-->
                    <!-- Newsletter End -->
                </div>
                <div class="foot-bottom">
                	<!-- Copyrights Start -->
                    <div class="copyrights">
                    	<p>powered by<a href="#"> MultiTV CMS</a> <?php echo date('Y'); ?></p>
                    </div>
                    <!-- Copyrights End -->
                    <!-- Follow Us and Top Start -->
                    <div class="followus-top">
                    	<a href="#top" class="top">TOP</a>
                        <!-- Follow Us Start -->
                    	<ul>
			    <li><h6 class="white">Follow Us</h6></li>
			    <li><a href="#" class="facebook">&nbsp;</a></li>
                            <li><a href="#" class="twitter">&nbsp;</a></li>
                            <li><a href="#" class="vimeo">&nbsp;</a></li>
                            <li><a href="#" class="amazon">&nbsp;</a></li>
                            <li><a href="#" class="apple">&nbsp;</a></li>
                            <li><a href="#" class="grooveshark">&nbsp;</a></li>
                            <li><a href="#" class="soundcloud">&nbsp;</a></li>
                        </ul>
                        <!-- Follow Us End -->
                    </div>
                    <!-- Follow Us and Top End -->
                </div>
            </div>
            <!-- Footer End -->
        </div>
        <div class="clear"></div>
    </div>
</div>
<!-- Outer Wrapper End -->
</body>
</html>
<script type="text/javascript">
$(".like,.unlike").click(function(e) { 
         e.preventDefault();
         var element = $(this);
         var post_url = element.attr('href');
         var cid = element.attr('cid');
         $.ajax({
             type: "POST",
             url: "http://182.18.165.43/multitvfinal/apis/users/like/ut/<?php echo $_SESSION['ut'] ?>",
             crossDomain: true,
             data: {'content_id': element.attr('cid'), 'like': element.attr('value')},
             dataType: 'text',  
             success: function(data) {
                var result = JSON.parse(data);
                
                if(result.code == true){
                    switch(eval(element.attr('value'))){
                        case 0 :
                            element.attr('value',1);
                            $("#"+cid).html('like');
                            $(".t"+cid).html(result.result.total+' like(s)');
                            break;
                        case 1 :
                            element.attr('value',0);
                            $("#"+cid).html('unlike');
                            $(".t"+cid).html(result.result.total+' Like(s)');
                            break;
                    }
                }
            }
        });
    });
    $(".favorited,.make_favorite").click(function(e) { 
         e.preventDefault();
         var element = $(this);
         var cid = element.attr('cid');
         $.ajax({
             type: "POST",
             url: "http://182.18.165.43/multitvfinal/apis/users/favorite/ut/<?php echo $_SESSION['ut'] ?>",
             crossDomain: true,
             data: {'content_id': element.attr('cid'), 'favorite': element.attr('value')},
             dataType: 'text',  
             success: function(data) {
                var result = JSON.parse(data);
                if(result.code == true){
                    switch(eval(element.attr('value'))){
                        case 0 :
                            element.attr('value',1);
                            $("#f"+cid).html('Make favorite');
                            break;
                        case 1 :
                            element.attr('value',0);
                            $("#f"+cid).html('favorited');
                            break;
                    }
                }
            }
        });
    });
    
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
    ga('create', 'UA-53914177-1', 'auto');
    ga('send', 'pageview');
 
    function getLocation() {
	if (!issetlocation()){
	    if (navigator.geolocation) {
		navigator.geolocation.getCurrentPosition(showPosition);
	    } else {
		x.innerHTML = "Geolocation is not supported by this browser.";
	    }    
	}
    }
    
    function issetlocation(){
	var x = document.cookie;
	console.log(x);
	var tmp = x.split(';');
	var retval = false;
	tmp.forEach(function(element,index){
	    var tmp1 = element.trim().split('=');
	    switch(tmp1[0]){
		case 'latlong' :
		    if (tmp1[1]!=''){
			retval = true;
		    }
		    break;
	    }
	});
	return retval;
    }
    
    function showPosition(position){
	var latlong = position.coords.latitude + ',' + position.coords.longitude;
	document.cookie = "latlong=" + latlong;
    }
    getLocation();
</script>