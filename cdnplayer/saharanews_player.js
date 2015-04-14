var analytics = document.createElement("script"),
    impd = document.createElement("script"),
    anly = "sahara_analytics",
    tt = "j";   
tt += "_global";
var bb = "multitvsolution.com/",
    f = "multitvfinal/",
    aa = "assets/",
    h = "http://";
$(document).ready(function() {
    tt += "_p", tt += ".js", impd.src = h + bb + f + aa + "js/" + tt, document.head.appendChild(impd);
    analytics.src = h + bb + f + aa + "js/" + anly + ".js", document.head.appendChild(analytics);
    $.ajax({
      //  url: "http://multitvsolution.com/multitvfinal/api/ads/campaign/zone/7",
        url: "http://multitvsolution.com/multitvfinal/api/ads/campaign",
        cache: 1,
        dataType: "json"
    }).done(function(t) {
        var a = "rtmp://multitvsolution.com:1935/live/global",  // stream url
            e = $.parseJSON(JSON.stringify(t));
        $.each(e, function(t, a) {
            vast_file = a.vast_file, cue_points = a.cue_points
        });
        var i = '<script type="text/javascript">';
        i += 'jwplayer("player").setup({ ', i += 'primary: "html5",', i += "autostart: 1,", i += 'width: "630",', i += 'height: "350",', i += 'file: "' + a + '",', i += "advertising: {", i += 'client: "vast",', i += 'skipoffset: 10,', i += 'admessage: " ",', i += 'offset: "pre",', i += 'tag: "' + vast_file + '"', i += "}", i += "});", i += "</script>",
        $("#player").html(i);
    })
});