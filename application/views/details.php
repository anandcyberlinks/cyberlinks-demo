<?php echo $result->video_path; exit; ?>


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
</head>
<body style='background:#000'>
        <div id="myElement" style='width:100%;height:100%'></div>
       
<script type="text/javascript" src="<?php echo base_url(); ?>./assets/js/jwplayer.js" ></script>
<script type="text/javascript">jwplayer.key = "BC9ahgShNRQbE4HRU9gujKmpZItJYh5j/+ltVg==";</script>
<script src="<?php echo base_url() ?>assets/js/jquery-1.10.2.js"></script>
<?php if(count($result)>0){
	$content_id = $result->content_id;
	$video_path = $result->video_path;
	$thumbnail_path = $result->thumbnail_path;
}else{
	$content_id = '';
	$video_path = '';
	$thumbnail_path = '';
}
?>
<script>
	
	function playVideo(){    		
		player.bind("finish", function() {
		    jwplayer().play(true);
		});
	}

    function autoplay() {
	jwplayer().play(true); //-- auto play for mobile	
    }
    function addview(){
        var id = "<?php echo $content_id;?>";
                    $.ajax({
                    url: "<?php echo base_url() ?>details/addview?id=" + id,
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
        file: "<?php echo $video_path;?>",
        image: "<?php echo base_url().THUMB_LARGE_PATH. $thumbnail_path;?>",       
        width: "100%",
 aspectratio: "16:9",
 //stretching: "exactfit",
//autostart: 1,
        logo: {
        file: "<?php echo base_url()?>assets/img/logo.jpg",        
        },
        advertising: {
	//admessage: 'Ad: your video resumes in XX seconds...',		
	client: "vast",
	skipoffset: 5,
	schedule: {
       <?php
       
       $i = 1;
       foreach ($scheduleBreaks as $row) {
	   $offset = ($row->offset_hrs * 3600) + ($row->offset_minutes * 60) + ($row->offset_seconds);
	   ?>
		adbreak<?php echo $i; ?>: {
		offset: "<?php echo $offset; ?>",
		'skipoffset':5,
		tag: "<?php echo base_url() . $row->relative_path; ?>"                     
		},
	   <?php $i++;
       } ?>                    
	}
	}
        //skin: "myCoolSkin/roundster.xml",       
    });
        
	autoplay();
	
    //-- get count for video views ---//
     var duration;  
      var pos=0;
           jwplayer().onBuffer(function () {
            duration = this.getDuration();              
           // alert(duration);
            }       
    );

    jwplayer().onPause(function () {
            
            if(pos >0){
                pos = parseInt(this.getPosition())-pos1;
                pos1 = pos1+pos;
            }else{
                pos = parseInt(this.getPosition());
                pos1=pos;
            }
            //alert(pos);
              //ga('send', 'event', 'IOSVideo', 'Pause', 'IOS video test' ,pos);
            }
    );

    jwplayer().onPlay(function () {
            if(duration==-1){            
            duration=this.getDuration();
            //ga('send', 'event', 'IOSVideo', 'Play', 'IOS video test' ,pos);
            //-- send view count in database --//            
                   addview();
            }
        }
    
    );
    
    jwplayer().onComplete(function () { 
    if(pos >0){
        pos = parseInt(this.getPosition())-pos;
    }else{
        pos = parseInt(this.getPosition());
    }
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

$(document).ready(function(){    
    AndroidApp.startVideo();    
});
</script>

</body>
</html>
