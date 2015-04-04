var id=0;
var duration;  
var pos=0;
h = "http://";
var ad_duration =0;
var ad_id =0;
bb="multitvsolution.com/";
var f = "multitvfinal/";
function play() {
	if(id ==0){
		$.ajax({
			url: h+bb+f+"api/analytics/play",
			data: {		
		       // user_id:'',
		        content_id:'92',
		        content_provider:'59',
		        play: '1'			
		       },
		       cache: false,
		       type: "POST",
		       dataType: 'json'	
		})
		.done(function(data){
			//$('#analytics_id').val(data);	
			//$.cookie("a_id", data);
			id = data;
		});
	}
    }
    
    function pause(duration){	

	if(id >0){
		//alert(analytics_id);
		$.ajax({
		    url: h+bb+f+"api/analytics/pause",
		    data: {
			id: id,
		        watched_time: duration,
		        pause: '1'
			},
		        cache: false,
		        type: "POST", 
			dataType: 'jsonp'           
		})
		.done(function(data){
		    
		});
	}
    }

	function playAds(tag) {
	$.ajax({
		url: h+bb+f+"api/analytics/playads",
		data:{
		tag:tag,
		broadcaster:'59',		
                play: '1',		
		},
		cache: false,
		type: "post",
		dataType: 'json',
		success: function(data,textStatus,jqXHR){
		alert(ad_id);
		}
	})
	/*.done(function(data){
alert(ad_id);
		ad_id = data;
	});*/
   }

//-- ads completed --//
   function completeAds(ad_duration) {
        $.ajax({
            url: h+bb+f+"api/analytics/ads_complete",
            data: {
                id: ad_id,
                watched_time: ad_duration,
                complete: '1',
		pause: 0
                },
                cache: false,
                type: "POST",
		dataType: 'jsonp'        
        })
        .done(function(data){
          //  if (data > 0) {
	//	$('#is_complete').val('1');
	   // }
        });
    }
    
jwplayer().onPause(function () {         
            if(pos >0){
                pos = parseInt(this.getPosition())-pos1;
                pos1 = pos1+pos;
            }else{
                pos = parseInt(this.getPosition());
                pos1=pos;
            }
            pause(pos1);           
            }
    );

    jwplayer().onPlay(function (){    
		play();  
        });
  
jwplayer().onAdPlay(function (event) {	
	 //console.log(event.tag);
	var tag = event.tag;
	playAds(tag);
});

jwplayer().onAdPause(function (event) {
	pauseAds(ad_duration);
});

jwplayer().onAdTime(function(event) {
  ad_duration = Math.round(event.position);
  //if (ad_duration >= 1.0 && ad_duration < 2 ) {
	//jwplayer().setMute(false);
	//console.log('123stop_ad');	
	//window.location.href="<?php echo $uri;?>#1234_ad"
  //}
//  var flag =/pre/i.test(event.tag);
/*
  if (id==56 && flag==false) {
	switch_ad_skip();
  }*/
});

//--- advertising analytics ---//
jwplayer().onAdComplete(function(event){			
	  /*var flag =/pre/i.test(event.tag);
	  if (flag==true) {
		 $('#flagad').val('0');
		  $('#tag').val('');
	  }
	jwplayer().setMute(true);
	console.log('123start_ad');
	window.location.href="<?php echo $uri;?>#123_ad"
	*/
	console.log(ad_duration);
	completeAds(ad_duration);
}); 
   /*
  jwplayer().onAdSkipped(function(event) {
	skipAds(ad_duration);
 });*/
