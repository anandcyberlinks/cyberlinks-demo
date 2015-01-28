<!DOCTYPE html>
<html lang="en">
<head>
    <meta name='viewport' content="initial-scale=1, maximum-scale=1, user-scalable=0">
	<meta charset="utf-8">
        <!-- jquery-1.10.2 -->
             <script>
  /*(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-53914177-1', 'auto');
  ga('send', 'pageview');
*/
 
</script>
	   
    <style>
      html, body, #map-canvas {
        height: 100%;
        margin: 0px;
        padding: 0px
      }
      #panel {
        position: absolute;
        top: 5px;
        left: 50%;
        margin-left: -180px;
        z-index: 5;
        background-color: #fff;
        padding: 5px;
        border: 1px solid #999;
      }
    </style>
 
    <style>
      #panel {
        position: absolute;
        top: 5px;
        left: 50%;
        margin-left: -180px;
        width: 350px;
        z-index: 5;
        background-color: #fff;
        padding: 5px;
        border: 1px solid #999;
      }
      #latlng {
        width: 225px;
      }
    </style>

  <body>
    

<body style='background:#000'>
		<input type='hidden' name='meta' id='meta'>
	<input type='hidden' name='analytics_id' id='analytics_id'>
	<input type='hidden' name='is_complete' id='is_complete'>
        <div id="myElement" style='width:100%;height:100%'></div>
       
<script type="text/javascript" src="<?php echo base_url(); ?>./assets/js/jwplayer.js" ></script>
<script type="text/javascript">jwplayer.key = "BC9ahgShNRQbE4HRU9gujKmpZItJYh5j/+ltVg==";</script>
<script src="<?php echo base_url() ?>assets/js/jquery-1.10.2.js"></script>

<?php if(count($result)>0){
	$content_id = $result->content_id;
        $content_provider = $result->content_provider;
	$video_path = $result->video_path;
	$thumbnail_path = $result->thumbnail_path;
}else{
	$content_id = '';
	$video_path = '';
	$thumbnail_path = '';
}
?>

<script>

//-- execute when browser closed --//
$(window).on('beforeunload', function(){
      jwplayer().pause();
      var pos = jwplayer().getPosition();
      pause(pos);     
});
//------------------------//

 var route='';
 var city ='';
 var state = '';
 var country ='';
 var country_code = '';
 var postal_code = '';
 
 	///--- location data ---//
	<?php if($geodata){
			foreach($geodata as $row){?>
						
			<?php if ($row['types'][0] == "route"){	?>		
				route = "<?php echo $row['long_name']?>";
			<?php } ?>
			
			<?php if ($row['types'][0] == "locality"){	?>		
				city = "<?php echo $row['long_name']?>";
			<?php } ?>
			
			<?php if ($row['types'][0] == "administrative_area_level_1"){	?>		
				state = "<?php echo $row['long_name']?>";
			<?php } ?>
			
			<?php if ($row['types'][0] == "country"){	?>		
				country = "<?php echo $row['long_name']?>";
				country_code = "<?php echo $row['short_name']?>";
			<?php } ?>
			
			<?php if ($row['types'][0] == "postal_code"){	?>		
				postal_code = "<?php echo $row['long_name']?>";
			<?php } ?>
			
		<?php } }?>
		//------------------------------//
		
    function autoplay() {
	if(typeof duration === 'undefined'){
		duration=jwplayer().getDuration();		
		//ga('send', 'event', 'IOSVideo', 'Play', 'IOS video test' ,pos);
		//-- send view count in database --//            
	      // addview();
	}
	jwplayer().play(true); //-- auto play for mobile
	//--- get meta info ---//

	//var serialized = JSON.stringify(meta);
	//console.log(serialized);
	play();
    }
    
    function play() {
	//code
	/*  lat=28.6328;
	  lng=77.2197;
	*/
	
	//alert(JSON.stringify(address[1]));
	
	//alert(JSON.stringify(arrAddress1));
		//--get details --//

		
	$.ajax({
		url: "<?php echo base_url() ?>analytics/play",
	        data: {		
                user_id:'<?php echo $user_id;?>',
                content_id:'<?php echo $content_id;?>',
                content_provider:'<?php echo $content_provider;?>',
                play: '1',
		city: city,
		state: state,
		country: country,
		country_code: country_code,
		route: route,
		postal_code: postal_code,
		latitude: '<?php echo $lat;?>',
		longitude: '<?php echo $long;?>'
	       },
	       cache: false,
	       type: "POST",
	       dataType: 'json'	
	})
	.done(function(data){
		$('#analytics_id').val(data);	
	});
    }
    
    function pause(duration){
	var id = $('#analytics_id').val();
	//alert(analytics_id);
        $.ajax({
            url:"<?php echo base_url()?>analytics/pause",
            data: {
	        id: id,
                watched_time: duration,
                pause: '1'
		},
                cache: false,
                type: "POST"            
        })
        .done(function(data){
            
        });
    }
    
    function complete(duration) {
        //code
	var id = $('#analytics_id').val();	
        $.ajax({
            url: "<?php echo base_url()?>analytics/complete",
            data: {
                id: id,
                watched_time: duration,
                complete: '1',
		pause: 0
                },
                cache: false,
                type: "POST"            
        })
        .done(function(data){
            if (data > 0) {
		$('#is_complete').val('1');
	    }
        });
    }
    
    function replay(id){
	$.ajax({
		url: "<?php echo base_url()?>analytics/replay",
		data: {
			//id: id,
			user_id:'<?php echo $user_id;?>',
			content_id:'<?php echo $content_id;?>',
			content_provider:'<?php echo $content_provider;?>',
			replay: '1',
			city: city,
			state: state,
			country: country,
			country_code: country_code,
			route: route,
			postal_code: postal_code,
			latitude: '<?php echo $lat;?>',
			longitude: '<?php echo $long;?>'
		},
		cache: false,
		type: "POST"
	})
	.done(function(data){
		$('#analytics_id').val(data);
	});
    }
    
    function addview(){
        var id = "1";
                    $.ajax({
                    url: "<?php echo base_url() ?>details/analytics?id=" + id,
                    type: 'GET',                    
                    cache: false,
                    dataType: 'json',
                    processData: false, // Don't process the files
                    contentType: false, // Set content type to false as jQuery will tell the server its a query string request
                    success: function(data, textStatus, jqXHR)
                    {
                        console.log('Response: ' + data);
                    },
                    error: function(jqXHR, textStatus, errorThrown)
                    {
                        // Handle errors here
                        console.log('ERRORS: ' + textStatus);
                        // STOP LOADING SPINNER
                    }
                });
                }
                
    jwplayer("myElement").setup({
       //flashplayer: "assets/player.swf",
        primary: "html5",
       // file: "<?php echo base_url();?>assets/upload/video/53f709efce75f.mp4",
       // image: "<?php echo base_url().THUMB_LARGE_PATH;?>5433b0b42146b.jpg",
        //file: "<?php echo $video_path;?>",
	file: "http://54.208.234.47:1935/live/ptc_160p/playlist.m3u8",
        image: "<?php echo base_url().THUMB_LARGE_PATH. $thumbnail_path;?>",
        width: "100%",
 aspectratio: "16:9",
 //controls: false,
 //stretching: "exactfit",
//autostart: 1,
        logo: {
        file: "<?php echo base_url()?>assets/img/logo.jpg",        
        },
       
        //skin: "myCoolSkin/roundster.xml",       
    });
    
  var meta1 =  jwplayer().onMeta(function (event){
		//$('#meta').val(JSON.stringify(event.metadata)) ;
		
		meta =JSON.stringify(event.metadata);
		var obj = $.parseJSON( meta);
		
		if (obj.renderstate !='software') {
			//code
			meta = meta;
			//$('#meta').val(meta);
		}
		return JSON.stringify(event.metadata);
		
	});
   
    /*
    jwplayer().onMeta(function (event){
	
	console.log(event.metadata);
    })
      */ 
    autoplay(); //-- auto play
    
    	var duration;  
      var pos=0;
    
    jwplayer().onPause(function () {
            state = jwplayer().getState();
	//alert(state);
            if(pos >0){
                pos = parseInt(this.getPosition())-pos1;
                pos1 = pos1+pos;
            }else{
                pos = parseInt(this.getPosition());
                pos1=pos;
            }
            pause(pos1);
            //alert(pos);
              //ga('send', 'event', 'IOSVideo', 'Pause', 'IOS video test' ,pos);
            }
    );

    jwplayer().onPlay(function () {
	//var id = $('#analytics_id').val();
	//alert(state);
	var is_complete = $('#is_complete').val();
		//alert(is_complete);
		if (is_complete == '1' && state != 'PAUSED') {
		//code
			replay();
		}
            
        });
    
    jwplayer().onComplete(function () {
	state=''; //-- set reset state --//
        /*
    if(pos >0){
        pos = parseInt(this.getPosition())-pos;
    }else{
        pos = parseInt(this.getPosition());
    }*/
    pos = parseInt(this.getPosition());
    complete(pos);
    //var title = "<?php //echo $content->content_title;?>-<?php //echo $content->contentid;?>";
    //alert(pos);
    //ga('send', 'event', 'IOSVideo', 'Complete', 'IOS video test' ,pos);
   pos=0;
    /*var seconds = pos/1000;
    var hours = parseInt( seconds / 3600 ); // 3,600 seconds in 1 hour
    seconds = seconds % 3600;
    
     var minutes = parseInt( seconds / 60 ); // 60 seconds in 1 minute
    // 4- Keep only seconds not extracted to minutes:
    seconds = seconds % 60;
    alert( hours+" hours and "+minutes+" minutes and "+seconds+" seconds!" );
*/    
    
});
/*
jwplayer().onComplete(function () { 
    if(pos >0){
        pos = parseInt(this.getPosition())-pos;
    }else{
        pos = parseInt(this.getPosition());
    }
    //var title = "<?php //echo $content->content_title;?>-<?php //echo $content->contentid;?>";
    //alert(pos);
  //  ga('send', 'event', 'Video', 'Complete', title ,pos);
   // pos=0;
    /*var seconds = pos/1000;
    var hours = parseInt( seconds / 3600 ); // 3,600 seconds in 1 hour
    seconds = seconds % 3600;
    
     var minutes = parseInt( seconds / 60 ); // 60 seconds in 1 minute
    // 4- Keep only seconds not extracted to minutes:
    seconds = seconds % 60;
    alert( hours+" hours and "+minutes+" minutes and "+seconds+" seconds!" );
*/    
    
//});

</script>

</body>
</html>
