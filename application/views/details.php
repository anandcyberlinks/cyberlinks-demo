<!DOCTYPE html>
<html lang="en">
	<script>  
  
</script>
<head>
	

<script>

   /*$(window).load(function () {
      // var endTime = (new Date()).getTime();
     //  var millisecondsLoading = endTime - startTime;
       log('Page load','End','<?php //echo $user_id;?>','<?php //echo $user_id;?>','<?php //echo $platform;?>');
       // Put millisecondsLoading in a hidden form field
       // or Ajax it back to the server or whatever.
   });*/
</script>
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
 
  function log(title,pos) {
	$.ajax({
		url: "<?php echo base_url() ?>index.php/details/log_load",
	        data: {		
                title:title,
                pos:pos,               
	       },
	       cache: false,
	       type: "POST",
	       dataType: 'json'	
	})	
    }
</script>
</head>
<body style='background:#000'>
	<input type='hidden' name='analytics_id' id='analytics_id'>
	<input type='hidden' name='ads_analytics_id' id='ads_analytics_id'>
	<input type='hidden' name='is_complete' id='is_complete'>
	<input type='hidden' name='totalTime' id='totalTime' value=0>
	<input type='hidden' name='totalTimeElapsed' id='totalTimeElapsed' value=0>
	<input type='hidden' name='flagad' id='flagad' value='0'>
	<input type='hidden' name='tag' id='tag' value=0>
        <div id="myElement" style='width:100%;height:100%'></div>
       <pre id="log"></pre>
       
<!--<script type="text/javascript" src="<?php echo base_url(); ?>./assets/js/jwplayer.js" ></script>-->

<script type="text/javascript" src="http://p.jwpcdn.com/6/12/jwplayer.js"></script>

<script type="text/javascript">jwplayer.key = "BC9ahgShNRQbE4HRU9gujKmpZItJYh5j/+ltVg==";</script>
<script src="<?php echo base_url() ?>assets/js/jquery-1.10.2.js"></script>

<?php if(count($result)>0){
	$content_id = $result->content_id;
	$content_provider = $result->content_provider;
	$video_path = $result->video_path;
	$thumbnail_path = $result->thumbnail_path;
	$content_type = $result->content_type;
}else{
	$content_id = '';
	$content_provider ='';
	$video_path = '';
	$thumbnail_path = '';
}
//print_r($scheduleBreaks);die;
?>
<script>
	
	//-- log start --//	
	//log('Page load','Start','<?php echo $user_id;?>','<?php echo $platform;?>');
	
//-- execute when browser closed --//
//$(window).on('beforeunload', function(){

function beforeUnload() {	
      jwplayer().pause();
      var pos = jwplayer().getPosition();
      if (pos >= 1) {
	pause(pos);
      }      
}
//});


//------------------------//

 var route='';
 var city ='';
 var states = '';
 var country ='';
 var country_code = '';
 var postal_code = '';

 <?php
 if($scheduleBreaks){
 foreach($scheduleBreaks as $row){
	if($row['cue_points']==''){?>
	var tag = "<?php echo $row['vast_file'];?>";
<?php
	} }
 }?>
// var tag="http://localhost/multitvfinal-demo/assets/upload/video/53f709efce75f.mp4";
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
			states = "<?php echo $row['long_name']?>";
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
		
		function play_video()
		{
			jwplayer().play(true);	
		}
		
		function pause_video() {			
			jwplayer().pause();	
		}
		
	function playVideo(){
		player.bind("finish", function() {
		    jwplayer().play(true);
		});
	}

   function autoplay() {
	if(typeof duration === 'undefined'){
		duration=jwplayer().getDuration();		
		//ga('send', 'event', 'IOSVideo', 'Play', 'IOS video test' ,pos);
		//-- send view count in database --//	     
	}
	//console.log(jwplayer().getState());
	jwplayer().play(true); //-- auto play for mobile	
	play();
    }
    
    function play() {	 
	$.ajax({
		url: "<?php echo base_url() ?>analytics/play",
	        data: {		
                user_id:'<?php echo $user_id;?>',
                content_id:'<?php echo $content_id;?>',
                content_provider:'<?php echo $content_provider;?>',
				content_type: '<?php echo $content_type;?>',
                play: '1',
		city: city,
		state: states,
		country: country,
		country_code: country_code,
		route: route,
		postal_code: postal_code,
		latitude: '<?php echo $lat;?>',
		longitude: '<?php echo $long;?>',
		platform: '<?php echo $platform;?>'
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
			content_type: '<?php echo $content_type;?>',
			replay: '1',
			city: city,
			state: states,
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
       
       function switch_ad(id){
	$.ajax({
		url: "<?php echo base_url()?>/index.php/details/switch_ad",		
		cache: false,
		type: "GET"
	})
	.done(function(data){		
		//console.log(tag);		
		if (data=='1') {
		$('#flagad').val('1');
		jwplayer().playAd(tag);
		}
	});
    }
    function switch_ad_skip(id){
	$.ajax({
		url: "<?php echo base_url()?>/index.php/details/switch_ad",		
		cache: false,
		type: "GET"
	})
	.done(function(data){		
		//console.log(tag);		
		if (data=='0') {
			 $('#flagad').val('0');
			jwplayer().load([{file:"<?php echo $video_path;?>"}]);
		}		

	});
    }
    //switch_ad();
    jwplayer("myElement").setup({
       //flashplayer: "assets/player.swf",
        primary: "html5",
		type: "mp4",        
        file: "<?php echo $video_path;?>",	   
	image: "<?php echo base_url().THUMB_LARGE_PATH. $thumbnail_path;?>",       
       // skin: "<?php echo base_url()?>assets/myskinjw/custom.xml",	
	width: "100%",
 aspectratio: "16:9",   
 controls: false,
 stretching: "exactfit",
 //mute: true,
autostart: 1,
        logo: {
       // file: "<?php echo base_url()?>assets/img/logo.png",
	margin: 1,
        },
        advertising: {
	client: "vast",
	//skipoffset: 5,
	schedule: {		
       <?php       
       $i = 1;
       
       if($scheduleBreaks){	
       foreach ($scheduleBreaks as $row) {
	if(trim($row['nn'] !='1')){
	   //$offset = ($row->offset_hrs * 3600) + ($row->offset_minutes * 60) + ($row->offset_seconds);
	   $offset = $row['cue_points'];	   
	   ?>
		adbreak<?php echo $i; ?>: {
		offset: '<?php echo ($offset==0 ? 'pre': $offset); ?>',
		//'skipoffset':5,
		//tag: "<?php //echo ($row['ad_type'] != 'External' ? base_url():'') . $row['vast_file']; ?>?<?php //echo $row['ads_id']?>/<?php //echo $user_id?>/<?php //echo $row['uid']?>"
		tag: "<?php echo $row['vast_file']?>/<?php echo $user_id?>/<?php echo $content_provider;?>/<?php echo $row['advertiser']?>/<?php echo ($offset==0 ? 'pre':'mid')?>"
		//tag: "http://localhost/multitvfinal-demo/assets/upload/ads/vast/vast1.xml"
		//tag: "http://54.179.170.143/multitvfinal/assets/upload/ads/vast/d53be859b9314be0885eda3794321e05.xml"
		},
	   <?php $i++;
       } }
      } ?>    
	}	
}
	
        //skin: "myCoolSkin/roundster.xml",       
    });

    jwplayer().onTime(function(event){	
	//console.log('hit');
	//--- log for stream load End--//
	var tag = $('#tag').val();
	/*
	if(tag==''){
	$('#tag').val('0');	
		log('Stream load','Stop','<?php echo $user_id;?>','<?php echo $platform;?>');
	}*/
	//-------------------------//
	var epos = event.position;	
       // var totalTimeElapsed = $( "#totalTimeElapsed" ).val();
	//console.log(parseInt(epos));
	
	if (epos >= 2.0 && epos < 4.0) {
		jwplayer().setMute(false);
		console.log('123stop');
		window.location.href="<?php echo $uri;?>#1234"
	}
	
	
        /*
        if(totalTimeElapsed!=0){
            var json = $.parseJSON($('#totalTimeElapsed').val());
        } else{
            var cuepoints = '<?php echo $cuePoints;?>';
            //console.log($.parseJSON(cuepoints));
        
            var json = $.parseJSON(cuepoints);
        }
	//var arr = $.map(cuepoints, function(el) { return el; });
	//console.log(arr);
	$(json).each(function(k,val){
		var totalTime = parseInt(epos);
		//totalTime = parseInt(totalTime) + parseInt(totalTimeElapsed);
		
		//console.log(totalTime);
		if (totalTime==val) {
			//console.log('ad is coming in 5 sec');
                        
                        var index = json.indexOf(val);
                        if (index > -1) {
                            json.splice(index, 1);
                        }
                        
                        $('#totalTimeElapsed').val(JSON.stringify(json));
		}	
	});*/	
	//console.log(this.getPosition());
	//-- switch newsnation ad ---//
	var id = "<?php echo $content_id;?>";
	//if (epos % 2 ==1 && id ==56) {
	if (id ==56) {
	    switch_ad();
	}
	//-------------------//
    });
    /*
    var id ='<?php echo $content_id;?>';
	setInterval(function(){
		console.log('aa');
		var tag = $('#tag').val();
		var pre =/pre/i.test(tag);
		console.log(pre);
		var flagad = $('#flagad').val();
		console.log(flagad);
		if (id == 38 && flagad=='0' && pre==false) {		    
		    //switch_ad();		    
		}
	}, 1000);
    */
    
	autoplay(); //--auto play jwplayer --//
	
	var duration;  
	var pos=0;
	
    /*
    jwplayer().onBuffer(function(event){
	console.log(jwplayer().getState());
	//-- log for stream play start---//
		log('Stream load','Start');
	//-----------------------------//
    });*/


    jwplayer().onPause(function () {           
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

    jwplayer().onPlay(function (){    
	//var id = $('#analytics_id').val();
	window.location.href="<?php echo $uri;?>#1234"
	//console.log(jwplayer().getState());
	//console.log('123stop');
	//console.log(jwplayer().getControls());
	var is_complete = $('#is_complete').val();
		//alert(is_complete);
		if (is_complete == '1' && state != 'PAUSED') {		
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
    
    jwplayer().onBeforePlay(function () {
	//--- create log start---//	
	if(jwplayer().getState() !='PAUSED'){
		log('Ad load:'+tag,'Start','<?php echo $user_id;?>','<?php echo $platform;?>');
		}
	//--------------//
	var id = "<?php echo $content_id;?>";
	if (typeof flag==='undefined' && id==56) {
		//console.log('before');
		jwplayer().playAd(tag+'?pre');		
		flag=1;
	}
    });
   
   //--- advetising analytics ---//
   
   function playAds(tag) {
	$.ajax({
		url: "<?php echo base_url()?>analytics/playads",
		data:{
		tag:tag,		
                play: '1',
		city: city,
		state: states,
		country: country,
		country_code: country_code,
		route: route,
		postal_code: postal_code,
		latitude: '<?php echo $lat;?>',
		longitude: '<?php echo $long;?>',
		platform: '<?php echo $platform;?>'
		},
		cache: false,
		type: "post"
	})
	.done(function(data){
		$('#ads_analytics_id').val(data);
	});
   }
   
   //-- ads completed --//
   function completeAds(ad_duration) {
	
        //code
	var id = $('#ads_analytics_id').val();	
        $.ajax({
            url: "<?php echo base_url()?>analytics/ads_complete",
            data: {
                id: id,
                watched_time: ad_duration,
                complete: '1',
		pause: 0
                },
                cache: false,
                type: "POST"            
        })
        .done(function(data){
          //  if (data > 0) {
	//	$('#is_complete').val('1');
	   // }
        });
    }
    
     function skipAds(ads_duration){
	var id = $('#ads_analytics_id').val();
	//alert(analytics_id);
        $.ajax({
            url:"<?php echo base_url()?>analytics/ads_skip",
            data: {
	        id: id,
                watched_time: ads_duration,
                skip: '1'
		},
                cache: false,
                type: "POST"        
        })
        .done(function(data){
            
        });
    }
    
/*
var log = document.getElementById("log");
var index = 0;
var fallbacks = [
  "http://second-adserver.com/vastResponse.xml",
  "http://third-adserver.com/vastResponse.xml"
]

jwplayer().onAdError(function(event) {
  var html = log.innerHTML;
  if(index < fallbacks.length) {
    html += "Tag "+index+" was empty, loading fallback tag "+(index+1)+".<br>";
    log.innerHTML = html;
    jwplayer().playAd(fallbacks[index]);
    index++;
  } else { 
    html += "Tag "+index+" was empty, with no more fallbacks available.<br>";
    log.innerHTML = html;
  }
});
*/

var ad_duration=0;


//-- ad start play --//
/*
jwplayer().onAdPlay(function (event) {	
	 
});
*/
//-- play ads ---//
jwplayer().onAdImpression(function (event) {
	//--- create log end---//
	//console.log('im'+this.getPosition());
	//if(this.getPosition() =='0')
	//console.log('ad play');
	//log('Ad load:'+event.tag,'End','<?php echo $user_id;?>','<?php echo $platform;?>');
	//--------------//
	//console.log(this.getPosition());
        //$("#totalTimeElapsed").val(parseInt(this.getPosition()));
	$('#totalTime').val(parseInt(this.getPosition()));
	
	jwplayer().setMute(true);
	console.log('123start');
	window.location.href="<?php echo $uri;?>#123"
	var tag = event.tag;
	//console.log(tag);
	$('#tag').val(tag);
	playAds(tag);
});

jwplayer().onAdTime(function(event) {
  ad_duration = Math.round(event.position);
  var id = "<?php echo $content_id;?>";
  if (ad_duration >= 1.0 && ad_duration < 2 ) {
	jwplayer().setMute(false);
	console.log('123stop_ad');
	//console.log('ad play');
	window.location.href="<?php echo $uri;?>#1234_ad"
  }
  //console.log(/pre/i.test(event.tag));
  //console.log(event.tag);
  var flag =/pre/i.test(event.tag);

 // console.log(ad_duration);
 // if (ad_duration % 2==1 && id==56 && flag==false) {
  if (id==56 && flag==false) {
//	console.log('midroll');
	switch_ad_skip();
  }
 //console.log(ad_duration);
  //console.log("progress","The ad completes in "+remaining+" seconds.");
});
	
//--- advertising analytics ---//
jwplayer().onAdComplete(function(event){
	
	//-- log for stream play start---//
		log('Stream load','Start','<?php echo $user_id;?>','<?php echo $platform;?>');
	//-----------------------------//
	
	  var flag =/pre/i.test(event.tag);
	  if (flag==true) {
		 $('#flagad').val('0');
		  $('#tag').val('');
	  }
	jwplayer().setMute(true);
	console.log('123start_ad');
	window.location.href="<?php echo $uri;?>#123_ad"
	completeAds(ad_duration);
}); 
   
  jwplayer().onAdSkipped(function(event) {
	skipAds(ad_duration);
 });
   
$(document).ready(function(){ 
    /* User Interest Video by adding tags into user interest .  */
    
    /*$.post( "<?php echo base_url(); ?>analytics/user_content_tags", {user_id: "<?php echo $user_id; ?>", content_id: "<?php echo $content_id; ?>"},function( data ) {
            //var foo = JSON.parse(data);
            //alert(data)
    });*/
  // AndroidApp.startVideo();    
});
</script>

</body>
</html>
