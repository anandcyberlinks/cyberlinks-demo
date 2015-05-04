<html>
    <head>
        <title>Google Cloud Messaging (GCM) in PHP</title>
		<style>
			div#formdiv, p#status{
			text-align: center;
			background-color: #FFFFCC;
			border: 2px solid #FFCC00;
			padding: 10px;
			}
			textarea{
			border: 2px solid #FFCC00;
			margin-bottom: 10px;			
			text-align: center;
			padding: 10px;
			font-size: 25px;
			font-weight: bold;
			}
			input{
			background-color: #FFCC00;
			border: 5px solid #fff;
			padding: 10px;
			cursor: pointer;
			color: #fff;
			font-weight: bold;
			}
			 
		</style>
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<script>
		$(function(){
			$("textarea").val("");
		});
		function checkTextAreaLen(){
			var msgLength = $.trim($("textarea").val()).length;
			if(msgLength == 0){
				alert("Please enter message before hitting submit button");
				return false;
			}else{
				return true;
			}
		}
		</script>
    </head>
	<body>
		<div id="formdiv">
		<h1>Google Cloud Messaging (GCM) in PHP</h1>	
		<form method="post" action="push_notification/index/?push=true" onsubmit="return checkTextAreaLen()">					                                                      
				<textarea rows="5" name="message" cols="45" placeholder="Message to send via GCM"></textarea> <br/>
				Registration Id:  <input type='text' value='APA91bEhseDdisO82zobPpvXbgplkSYd4PdH1dBpi2C-vX_PcdCIdSsCWZ26Ha3Whmb-uIRihFRaMhiGSsEg-PJTQ6CbTcRvsh88_Uhqm3JqM8xZdfHvWo7YD06LrnOlGH2BgTKpMbjJjV-c44X1BkizPukQo79GmA' name='regId'>
                <input type="submit"  value="Send Push Notification through GCM" />
		</form>
		</div>
		<p id="status">
		<?php echo $pushStatus; ?>
		</p>        
    </body>
</html>