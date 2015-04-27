<!DOCTYPE html>
<html lang="en">
	<script>  
  
</script>
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
       <pre id="log"></pre>
       
<script type="text/javascript" src="<?php echo base_url(); ?>./assets/js/jwplayer.js" ></script>

<!--<script type="text/javascript" src="http://p.jwpcdn.com/6/12/jwplayer.js"></script>-->

<script type="text/javascript">jwplayer.key = "BC9ahgShNRQbE4HRU9gujKmpZItJYh5j/+ltVg==";</script>
<script src="<?php echo base_url() ?>assets/js/jquery-1.10.2.js"></script>

<script>
		
    jwplayer("myElement").setup({
       //flashplayer: "assets/player.swf",
        primary: "html5",		
		file: "http://54.179.170.143:1935/vod/_definst_/video/54db58726e46f_3g.mp4/playlist.m3u8",
       // file: "<?php echo $video_path;?>",
	    //image: "<?php echo base_url().THUMB_LARGE_PATH. $thumbnail_path;?>",       
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
	skipoffset: 5,
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
		//tag: "<?php echo $row['vast_file']?>/<?php echo $user_id?>/<?php echo $content_provider;?>/<?php echo $row['advertiser']?>/<?php echo ($offset==0 ? 'pre':'mid')?>"
		//tag: "http://multitvsolution.com/vast/file/7b3c1d70a56c25f462cf9ac4c29faab7.xml?27/15/11/44/0/87/60/pre"
		//tag: "http://54.179.170.143/multitvfinal/assets/upload/ads/vast/d53be859b9314be0885eda3794321e05.xml"
		},
	   <?php $i++;
       } }
      } ?>    
	}	
}   
    }); 
</script>
</body>
</html>
