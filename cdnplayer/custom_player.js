var analytics = document.createElement("script"),
    impd = document.createElement("script"),
    anly = "analytics",
    tt = "j";
tt += "_global";
var bb = "multitvsolution.com/",
    f = "multitvfinal/",
    aa = "assets/",
    h = "http://";
var url = $('#player').text();
var width = $('#player').attr('width');
var height = $('#player').attr('height');
alert(width);
alert(height);
var domain = document.domain;
$(document).ready(function() {     
     $.ajax({
      url: h + bb + f+"api/web/cp/token/"+apikey,
      cache: 1,
      dataType: 'json'
    }).done(function(t){        
 	e = $.parseJSON(JSON.stringify(t));
 	$.each(e, function(t, a) {
          content_id = a.channel_id,content_provider=a.id,zone_id=a.zone_id
        });	
        playstream(url);
    }).error(function(){
          $('#player').css("color", "red").text('Your api key is invalid.');
	});     
    tt += "_p", tt += ".js", impd.src = h + bb + f + aa + "js/" + tt, document.head.appendChild(impd);
    analytics.src = h + bb + f + aa + "js/" + anly + ".js", document.head.appendChild(analytics);
   function playstream(url){
    $.ajax({
        url: h + bb + f+"api/ads/campaign/zone/"+zone_id,
        cache: 1,
        dataType: "json"
    }).done(function(t) {
        var a = "rtmp://multitvsolution.com:1935/live/global",
            e = $.parseJSON(JSON.stringify(t));
        $.each(e, function(t, a) {
            vast_file = a.vast_file, cue_points = a.cue_points
        });
        var i = '<script type="text/javascript">';
        i += 'jwplayer("player").setup({ ', i += 'primary: "html5",', i += "autostart: 1,", i += 'width: "'+width+'",', i += 'height: "'+height+'",', i += 'file: "' + a + '",', i += "advertising: {", i += 'client: "vast",', i += 'skipoffset: 10,', i += 'admessage: " ",', i += 'offset: "pre",', i += 'tag: "' + vast_file + '"', i += "}", i += "});", i += "</script>",
        $("#player").html(i);
    })
   }
});