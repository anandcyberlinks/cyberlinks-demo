<?php $tab = $this->uri->segment(3); ?>
<div class="wrapper row-offcanvas row-offcanvas-left">        
	<!-- Right side column. Contains the navbar and content of the page -->
	<aside class="right-side">                
		<!-- Content Header (Page header) -->
		<section class="content-header">
			<h1><?php echo  $welcome->loadPo('Ads') ?><small><?php echo  $welcome->loadPo('Control panel') ?></small></h1>
			<ol class="breadcrumb">
				<li><a href="<?php echo base_url(); ?>"><i class="fa fa-dashboard"></i><?php echo $welcome->loadPo('Dashboard') ?></a></li>
                                <li><a href="<?php echo base_url(); ?>ads"><i class="fa fa-play-circle"></i><?php echo $welcome->loadPo('Ads') ?></a></li>
                                <li class="active"><?php echo $welcome->loadPo('Upload Video') ?></li>
			</ol>
		</section>
		<!-- error messag div -->
		<div id="msg_div">
			<?php echo $this->session->flashdata('message');?>
		</div>	
       <!-- Main content -->
        <section class="content">
			<div id="content">
				<div class="row">
					<!-- left column -->
					<div class="col-md-12">
						<!-- Custom Tabs Starts -->
						<div class="nav-tabs-custom">
							<ul class="nav nav-tabs">
								<li class="<?=($tab==='Upload')?'active':''?>" ><a href="<?php echo base_url();?>video/videoUploadSrc/Upload"><?php echo  $welcome->loadPo('Upload') ?></a></li>
								<!--<li class="<?=($tab==='Youtube')?'active':''?>" ><a href="<?php echo base_url();?>video/videoUploadSrc/Youtube"><?php echo  $welcome->loadPo('Youtube') ?></a></li>-->

								<?php /* ?><li class="<?=($tab==='Other')?'active':''?>" ><a href="<?php echo base_url();?>video/videoUploadSrc/Other"><?php echo  $welcome->loadPo('Other')." ".$welcome->loadPo('Source'); ?></a></li><?php */ ?>
							</ul>
							<div class="tab-content">
								<!-- simple upload section starts -->
								<?php if($tab == 'Upload') {?>
								<div class="tab-pane active" id="tab_upload">
									<div class="box box-solid">
										<div class="box-header">
											<h3 class="box-title"><?php echo  $welcome->loadPo('Upload')." ".$welcome->loadPo('Video') ?></h3>
											<div class="box-tools pull-right"></div>
										</div>
										<!-- form start -->
										<form action="<?php echo base_url() ?>ads/Upload" id="videoUploadForm" class="filse" enctype="multipart/form-data" method="post" accept-charset="utf-8">
											<div style="display:none;">
											<input type="hidden" id="redirect_url" name="redirect_url" value="<?php echo current_full_url(); ?>" />	
											<input type="hidden" name="_method" value="POST"/></div>
											<div class="box-body">
												<div class="form-group">
													<span class="btn btn-default btn-file btn-sm">
														<?php echo  $welcome->loadPo('Choose Media') ?> <input name="video_file"  id="video_file"  atr="files"type="file"/>
													</span>
												</div>
												<div id="status_video_file" style="color:red;"  class="callout-danger" ></div>
											<div class="box-body" id="displayfile" >
											</div>
											</div>
											<div class="box-footer">
												<a class="confirm"  href=""  ><button id="load" class="btn btn-primary btn-sm" data-toggle="modal" data-target=".bs-example-modal-sm" ><?php echo  $welcome->loadPo('Upload') ?></button></a>
												<a href="<?php echo  base_url(); ?>ads" class="btn btn-default btn-sm"><?php echo  $welcome->loadPo('Cancel') ?></a>                
											</div>
										</form>
									</div>
								</div>
								<?php } ?>								
								<!-- simple upload section ends -->
								
								<!-- youtube upload section starts -->
								<?php //if($tab == 'Youtube') {?>
								<?php /*<div class="tab-pane active"">
									<div class="box-header">
										<h3 class="box-title"><?php echo  $welcome->loadPo('Youtube')." ".$welcome->loadPo('URL') ?></h3>
									</div>
									<!-- form start -->
									<form action="<?php echo base_url() ?>video/youtube" enctype="multipart/form-data" method="post" accept-charset="utf-8">
									<input type="hidden" name="redirect_url" value="<?php echo current_full_url(); ?>" />	
									<input class="form-control" name="url" ></br>
										<input type="submit" name="submit" value="Submit" class="btn btn-success">
									</form>
								</div> */?>
								<?php //} ?>
								<!-- youtube upload section ends -->
								
								
								<!-- upload from other source section starts -->
								<?php if($tab == 'Other') { ?>
								<div class="tab-pane active" id="tab_other">
									<div class="box box-solid">
										<div class="box-header">
											<h3 class="box-title"><?php echo  $welcome->loadPo('Upload')." ".$welcome->loadPo('Other')." ".$welcome->loadPo('Video') ?></h3>
											<div class="box-tools pull-right"></div>
										</div>
										<!-- form start -->
										<form action="<?php echo base_url(); ?>ads/upload_other" id="videoSrcOther" enctype="multipart/form-data" method="post" accept-charset="utf-8" >
											<div style="display:none;">
											<input type="hidden" id="redirect_url" name="redirect_url" value="<?php echo current_full_url(); ?>" />	
											<input type="hidden" name="_method" value="POST"/></div>
											<div class="box-body">
												<div class="row">
													<div class="form-group col-lg-5">
														<div class="input text">
															<label for=""><?php echo  $welcome->loadPo('Source')." ".$welcome->loadPo('Url'); ?></label>
															<input type="text" name="source_url" id="source_url" class="form-control" value="<?php echo set_value('source_url'); ?>" placeholder="<?php echo  $welcome->loadPo('Source')." ".$welcome->loadPo('Url'); ?>">
															<?php echo form_error('source_url','<span class="text-danger">','</span>'); ?>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group col-lg-5">
														<div class="input text">
															<label for=""><?php echo  $welcome->loadPo('Content')." ".$welcome->loadPo('Provider'); ?></label>
															<select name="content_provider" id="content_provider" class="form-control">
																<option value=""  <?php echo set_select('parent', '', TRUE); ?>>--<?php echo $welcome->loadPo('Select');?>--</option>
																<option value="Youtube" <?php if( set_value('content_provider') == 'Youtube' ) {  echo 'selected="selected"'; } ?>><?php echo $welcome->loadPo('Youtube');?></option>
															</select>	
															<?php echo form_error('content_provider','<span class="text-danger">','</span>'); ?>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group col-lg-5">
														<div class="input text">
															<label for=""><?php echo  $welcome->loadPo('Title'); ?></label>
															<input type="text" name="title" id="title" class="form-control" value="<?php echo set_value('title'); ?>" placeholder="<?php echo $welcome->loadPo('Title');?>">
															<?php echo form_error('title','<span class="text-danger">','</span>'); ?>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group col-lg-5">
														<div class="input text">
															<label for=""><?php echo  $welcome->loadPo('Description'); ?></label>
															<input type="text" name="description" id="description" class="form-control" value="<?php echo set_value('description'); ?>" placeholder="<?php echo  $welcome->loadPo('Description'); ?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group col-lg-5">
														<div class="input select">
															<label for=""><?php echo  $welcome->loadPo('Category'); ?></label>
															<select name="category" id="category" class="form-control">
																<option value=""  <?php echo set_select('parent', '', TRUE); ?>>--<?php echo $welcome->loadPo('Select');?>--</option>
																<?php foreach($allCategory as $cat){ ?>
																	<option value="<?php echo $cat->id;?>" <?php if(set_value('category') == $cat->id ) {  echo 'selected="selected"'; } ?>><?php echo $cat->category;?></option>
																<?php } ?>
															</select>
															<?php echo form_error('category','<span class="text-danger">','</span>'); ?>
														</div>
													</div>
												</div>


												<div class="box-footer">
													<button class="btn btn-primary btn-sm" id="validatesrc"  type="submit" name="submit" value="Submit" onclick="validatesrc_url();" ><?php echo  $welcome->loadPo('Submit') ?></button>
													<a href="<?php echo  base_url(); ?>ads" class="btn btn-default btn-sm"><?php echo  $welcome->loadPo('Cancel') ?></a>                
												</div>
											</div>
										</form>
									</div>
								</div>
								<?php } ?>
								<!-- upload from other source section ends -->
						</div>
						<!-- Custom Tabs Ends -->
					</div>
				</div>
			</div>
		</section><!-- /.content -->
	</aside><!-- /.right-side -->
</div><!-- ./wrapper -->
	
<!-- Model player  -->
<div class="modal fade" id="playerModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel">Modal title</h4>
			</div>
			<div class="modal-body"><div align="center" id="jsplayer"></div></div>
		</div>
	</div>
</div>
<script type="text/javascript">	
// Variable to store your files
   var files;
// uplaod events
    $('#video_file').on('change', prepareUpload);
 // Grab the files and set them to our variable
    function prepareUpload(event)
    {
		$('#status_video_file').html('');
        files = event.target.files;
		var fileName = this.files[0].name;
        var fileSize = this.files[0].size;
        var fileType = this.files[0].type;
		var size= fileSize/1048576 ;
		var fsize = size.toFixed(2);  
	 	if (!fileType.match(/(?:mp4|x-msvideo|avi|wmv|mp4|mpeg|mpg|flv)$/)) 
		{
			// inputed file path is not an image of one of the above types
			var row_data1 = "";
			row_data1 +='<section class="content"><div class="col-xs-12"><div class="alert alert-danger alert-dismissable"><i class="fa fa-check"></i>only avi,wmp,mp4,mpeg,mpg,flv video file is allow!</div><div></section>';
			$('#msg_div').html(row_data1).fadeTo(3000, 500).slideUp(3000);
			$('#displayfile').hide();
			$('#video_file').val('');
			return false;
		}
			var row_data = "";
			row_data +='<table class="table table-bordered"><tr class="unread"><th class="small-col"><?php echo  $welcome->loadPo('Filename') ?></th><th class="small-col"><?php echo  $welcome->loadPo('FileSize') ?></th><th class="small-col"><?php echo  $welcome->loadPo('File Type') ?></th><th class="small-col"><?php echo  $welcome->loadPo('Progress') ?></th></tr><tr class="unread"><td class="small-col">'+fileName+'</td><td class="small-col">'+fsize+' MB</td><td class="small-col">'+fileType+'</td><td class="small-col" id="size"  width="300"><div class="progress progress-striped "><div style="width: '+0+'%" class="progress-bar progress-bar-primary" id="progressbar"></div></div></td></tr></table>';	
		 	$('#displayfile').html(row_data).show();
    }
	
	
	$('#load').on('click', uploadFiles);
 // Catch the form submit and upload the files
    function uploadFiles(event)
    {	
	var videoFileName = $('#video_file').val();
		if(videoFileName == "")
		{
			$('#status_video_file').html('Please Select a file to upload.');
			return false;
		} else {
			var data = new FormData();
			$.each(files, function(key, value)
			{
				data.append(key, value);
			});
			 $.ajax({
					xhr: function()
					  {
						var xhr = new window.XMLHttpRequest();
						//Upload progress
						xhr.upload.addEventListener("progress", function(evt){
						  if (evt.lengthComputable) {
							var percentComplete = evt.loaded / evt.total*100;
							//Do something with upload progress
							var row_data = "";
							row_data +='<div class="progress progress-striped"><div style="width: '+percentComplete+'%" class="progress-bar progress-bar-primary"></div></div></div>';
							document.getElementById('size').innerHTML = row_data;
							//console.log(percentComplete);
						  }
						}, false);
						//Download progress
						xhr.addEventListener("progress", function(evt){
						  if (evt.lengthComputable) {
							var percentComplete = evt.loaded / evt.total*100;
							//Do something with download progress
							//console.log(percentComplete);
						  }
						}, false);
						return xhr;
					  }, 
						url: "<?php echo base_url() ?>ads/upload",
						type: 'POST',
						data: data,
						cache: false,
						dataType: 'json',
						processData: false, // Don't process the files
						contentType: false, // Set content type to false as jQuery will tell the server its a query string request
						success: function(data, textStatus, jqXHR)
						{
						if(data.flag == 0)
							{	
								$('#mgs_div').html(data.message).fadeTo(3000, 500).slideUp(3000);
								$('#displayfile').hide();
							}
							else
							{
								$('#msg_div').html(data.message).fadeTo(3000, 500).slideUp(3000);
								$('#displayfile').hide();
								location.href="<?php echo base_url() ?>ads/videoOpr/Basic?action="+data.id ;
							}
					
						}
					});
		}
    }
	
</script>
  