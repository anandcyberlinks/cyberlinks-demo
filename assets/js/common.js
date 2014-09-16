$(function(){
    
    $('#status').html('');	
	$("#time_div").hide();
	$("#time_div").hide();
	$('#durationDiv').hide();


	/*	Start Date and Time in Scheduling */
	
  
	$(".timepicker").timepicker({
		minuteStep: 1,
		showInputs: false,
		disableFocus: true
	});
	/*	End Date and Time in Scheduling */
	
	/*	Start Radio Hide/Show */
	//$("#id_radio1").prop("checked", true);
	$('#id_radio1').click(function () {
		   $('#div2').hide();
	});
	$('#id_radio2').click(function () {
		  $('#div2').show();
	});
	/*	End Radio Hide/Show */

	/* tagit function for keywork in video basic */
	
	$("#myTags").tagit();
	
	/* function to crop thumbnail image(ry) */
	
	$('#cropbox').Jcrop({
      aspectRatio: 1,
      onSelect: updateCoords
    }); 
	
	/* function to upload thumbnail image using ajax(ry) */
	
	$('a.thumb_upload').click(function(e) {
		$('#status').html('');	
		var content_id = $(this).attr('content_id');
		var str = '<input type="hidden" name="content_id" id="content_id" value="'+content_id+'" \/>';		
		$('#myModal1 #prevElement1').html(str);
	});		
	
	$('#thumbImgUpload').click(function(e) {
		var thumb_image = $('#thumb_img').val();
		if(thumb_image == '')
		{
			$('#status').html('Please Select a file to upload.');
			return false;
		} 
	});


	$('a.thumb_crop').click(function(e) {
		var content_id = $(this).attr('content_id');	
		var str = '<input type="hidden" name="content_id" id="content_id" value="'+content_id+'" \/>';	
		$('#myModal2 #prevElement2').html(str);		
	});	

	
	$('a.thumb_grab').click(function(e) {
	 	var file_path = $(this).attr('data-img-url');
		var str = '<script type="text/javascript">';
		str += 'jwplayer("prevElement3").setup({ ';
		str += 'primary: "html5",';
		str += 'file: ' + '"' + file_path + '",';
		str += 'width: ' + '"' + 600 + '",';
		str += 'height: ' + '"' + 280 + '",';
		str += 'events: ';
		str += '{'; 
		str += 'onPause : function() {';
		str += 'time = jwplayer("prevElement3").getPosition();';
		str += '}';
		str += '}';
		str += '});';
		str += '<\/script>';
		$('#myModal3 #prevElement3').html(str);
	});
  	 setInterval(function() {
		//var z = (jwplayer("prevElement3").getPosition());
	//	secondsToTime(z);
	}, 600);
 	$('a.confirm_delete').click(function(e) {
		e.preventDefault();
		var location = $(this).attr('href');
		bootbox.confirm('Are you sure you want to Delete Image', function(confirmed) 
		{
			if (confirmed) 
			{
				window.location.replace(location);
			}
			
		});
	});	
	


	/* other functions */
	
    $('#daterange').daterangepicker({timePicker: true, timePickerIncrement: 30, format: 'MM/DD/YYYY h:mm A'});
	
	$("#datepickerend").datepicker("option", "showAnim", 'drop'); 
	
	$('a.prev_video').click(function(e) {
		var file_path = $(this).attr('data-img-url')
		var str = '<script type="text/javascript">';
		str += 'jwplayer("prevElement").setup({ ';
		str += 'primary: "html5",';
        str += 'width: 600,';
        str += 'height: 600/1.5,';
		str += 'file: ' + '"' + file_path + '"';
		str += '});';
		str += '<\/script>';
		$('#myModal #prevElement').html(str);
	});
	
	$('a.jsplayerVideo').click(function(e) {
		var file_path = $(this).attr('data-img-url')
		var str = '<script type="text/javascript">';
		str += 'jwplayer("jsplayerV").setup({ ';
		str += 'primary: "html5",';
        str += 'width: 600,';
        str += 'height: 600/1.5,';
		str += 'file: ' + '"' + file_path + '"';
		str += '});';
		str += '<\/script>';
		$('#playerModel #jsplayerV').html(str);
	});
	
	
	/* 6_8_14*/	

});

/*  function to crop thumbnail image(ry) */

	function updateCoords(c)
	{
		var orig_width= $('#orig_width').val();  // original width of image
		var orig_height= $('#orig_height').val(); // original height of image
		var width_ratio = (orig_width/560).toFixed(2);  // ratio with displayed image with width
		var height_ratio = (orig_height/350).toFixed(2);   // ratio with displayed image with height
		var crop_x = c.x;
		var crop_y = c.y;
		var crop_w = c.w;
		var crop_h = c.h;	
		var mod_x = Math.round(width_ratio*crop_x);
		var mod_y = Math.round(height_ratio*crop_y);
		var mod_w = Math.round(width_ratio*crop_w);
		var mod_h = Math.round(height_ratio*crop_h); 
		$('#x').val(mod_x);
		$('#y').val(mod_y);
		$('#w').val(mod_w);
		$('#h').val(mod_h); 

	};

    function checkCoords()
    {
		if (parseInt($('#w').val())) return true;
		alert('Please select a crop region then press submit.');
		return false;
    };

	
	function secondsToTime(secs)
	{
		secs = Math.floor(secs);
		var hours = Math.floor(secs / (60 * 60));

		var divisor_for_minutes = secs % (60 * 60);
		var minutes = Math.floor(divisor_for_minutes / 60);

		var divisor_for_seconds = divisor_for_minutes % 60;
		var seconds = Math.ceil(divisor_for_seconds);
		if(seconds)
		{
			$("#thumbgrabHours").val(hours) ;
			$("#thumbgrabMinutes").val(minutes) ;
			$("#thumbgrabSeconds").val(seconds) ;
		}		
	}

	
	/* other functions */
	
	function form_submit(id,fla,con)
		{
		//alert(id+fla+con);

		//var conid = document.getElementById("useid").value ;
		var str= 'vid='+id+'&fid='+fla+'&conid='+con;
		//alert(str);
		 $.ajax({
			url: '<?php echo base_url();?>admin/videos/save_flavor_select',
			type: 'post',
			dataType: 'json',
			data: str,
			success: function(data) {
			
						if(data==1)
						{
							var ht = '<div class="alert alert-success alert-dismissable"><i class="fa fa-check"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>The video transcode in flavor request send successfully.</div>'
							jQuery("#msg_div").html(ht);
							var hts= '<span class="label label-primary">In progress</span>'
							jQuery("#statu_change_"+fla).html(hts);
							$("#btn_"+fla).attr("disabled", "disabled");
						}
						if(data==0)
						{
							var ht = '<div class="alert alert-success alert-dismissable"><i class="fa fa-check"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>The video transcode in flavor request already sent successfully.</div>'
							jQuery("#msg_div").html(ht);
						}
					   
				}
		});
		}
  	function date_check()
	{
		var startDate = $('#datepickerstart').val();
		var endDate = $('#datepickerend').val();
		if(startDate !="" && endDate!="")
		{
			var regExp = /(\d{1,2})\/(\d{1,2})\/(\d{2,4})/;
			if(parseInt(endDate.replace(regExp, "$3$2$1")) < parseInt(startDate.replace(regExp, "$3$2$1"))){
				
				bootbox.alert("End date should be greater then to start date");
				return false ;
			}
		}
	 	return true ;
	}
	
 	$('td a.prev_video').click(function(e) {
 	 	var file_path = $(this).attr('data-img-url')
		var str = '<script type="text/javascript">';
		str += 'jwplayer("prevElement").setup({ ';
		str += 'primary: "html5",';
		str += 'width: "850",';
		str += 'height: "850",';
		str += 'file: ' + '"' + file_path + '"';
		str += '});';
		str += '<\/script>';
		$('#myModal #prevElement').html(str);
	});
	
    function stopvideo()
    {
        jwplayer('prevElement').stop();
    }
 
    function delete_video(id,url,curl)
	{ 
	  bootbox.confirm("Are you sure you want to Delete video", function(confirmed) 
							{
								if (confirmed) 
								{
									location.href = url+'?id='+id+'&curl='+curl ;
								}
							})
	}
	
	function delete_comment(id)
	{ 
	  bootbox.confirm("Are you sure you want to Delete video", function(confirmed) 
							{
								if (confirmed) 
								{
									location.href = url+'?id='+id ;
								}
							})
	}
	function delete_role(id)
	{ 
	  bootbox.confirm("Are you sure you want to Delete video", function(confirmed) 
							{
								if (confirmed) 
								{
									location.href = 'role/deleterole?id='+id ;
								}
							})
	}
	
    function delete_video1(id)
	{ 
	  bootbox.confirm("Are you sure you want to Delete video", function(confirmed) 
							{
								if (confirmed) 
								{
									location.href = '/mobiletv/video/deletevideo?id='+id ;
								}
							})
	}
	
	function delete_user(id)
	{ 
	  bootbox.confirm("Are you sure you want to Delete video", function(confirmed) 
							{
								if (confirmed) 
								{
									location.href = 'user/DeleteUser?id='+id ;
								}
							})
	}

	/* start date and end date  search video page */

	$(document).ready(function(){
     $("#datepickerstart").datepicker({
			dateFormat: 'dd/mm/yy',
			numberOfMonths: 1,
                        maxDate: 0,
			onSelect: function(selected) {
			$("#datepickerend").datepicker("option","minDate",$('#datepickerstart').val());
        }
    });
    $("#datepickerend").datepicker({ 
		dateFormat: 'dd/mm/yy',
		numberOfMonths: 1,
                maxDate: 0,
		onSelect: function(selected) {
		$("#datepickerstart").datepicker("option","maxDate", $('#datepickerstart').val());
        }
    });  
});	



	/* bulk upload by CSV file only */

 	// Variable to store your files
   var files;
	// uplaod events
    $('#csv_file').on('change', prepareUpload);
	// Grab the files and set them to our variable
	
    function prepareUpload(event)
    {
		$('#status_csv_file').html('');
	    files = event.target.files;
		var fileName = this.files[0].name;
        var fileSize = this.files[0].size;
        var fileType = this.files[0].type;
	 	var size= fileSize/1048576 ;
		var fsize = size.toFixed(2);  
		if (!fileType.match(/(?:csv|vnd.ms-excel|csv|CSV)$/)) 
		{
			// inputed file path is not an image of one of the above types
			var row_data1 = "";
			row_data1 +='<section class="content"><div class="col-xs-12"><div class="alert alert-danger alert-dismissable"><i class="fa fa-check"></i>only CSV file is allow!</div><div></section>';
			$('#msgftp').html(row_data1).fadeTo(3000, 500).slideUp(3000);
			$('#displayfile').hide();
			return false;
		}
	}
	
	
	$('#uploadcsv').click(function(e) {
		var fileName = $('#csv_file').val();
		if(fileName == "")
		{
			$('#status_csv_file').html('Please Select a file to upload.');
			return false;
		} else {
			$('#displayfile').html('<img src="assets/img/loader.gif"> loading...');
            return true; // Valid file type
		}		
	});	

	
	/* function to validate url starts */
	
	function validatesrc_url() {
		var srcurl = $('#source_url').val(); 
		var regYoutube = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#\&\?]*).*/;
		var regVimeo = /^.*(vimeo\.com\/)((channels\/[A-z]+\/)|(groups\/[A-z]+\/videos\/))?([0-9]+)/;
		var regDailymotion = /^.+dailymotion.com\/(video|hub)\/([^_]+)[^#]*(#video=([^_&]+))?/;
		var regMetacafe = /^.*(metacafe\.com)(\/watch\/)(\d+)(.*)/i;
		var regexp = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;
		   if(regYoutube.test(srcurl)) {
				return true;
			}else if (regMetacafe.test(srcurl)) { 
				return true;
			}else if(regDailymotion.test(srcurl)){ 
				return true;
			}else if(regVimeo.test(srcurl)) { 
				return true;
			}else if(regexp.test(srcurl)) { 
				return true;
			}else{ 
				alert('Please enter a valid url.');
				$('#source_url').focus(); 
				return false;
			}
	}
	
	/* function to validate url ends */

											
	/* functions to check png image in video setting section starts */
	
	function upload_logo_video()
	{ 
		var LogoLinkUrl = $('#playerLogoLink').val();
		if(LogoLinkUrl != "")
		{
				var Extension = LogoLinkUrl.substring(LogoLinkUrl.lastIndexOf('.') + 1).toLowerCase();
                if (Extension == "png")
                {					
                    return true; // Valid file type
                }
                else {
					alert('Please upload png image only.');
					$('#playerLogoLink').focus();
                    return false; // Not valid file type
                }	
		} 
	}

	
	/* functions to check png image in video setting section starts */

	
	
	


