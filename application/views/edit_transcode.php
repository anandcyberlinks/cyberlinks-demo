<div class="wrapper row-offcanvas row-offcanvas-left">
	<!-- Right side column. Contains the navbar and content of the page -->
    <aside class="right-side">                
		<!-- Content Header (Page header) -->
		<section class="content-header">
			<h1>
			<?php echo $welcome->loadPo('Transcode'); ?><small><?php echo $welcome->loadPo('Control panel'); ?></small></h1>
			<ol class="breadcrumb">
				<li><a href="<?php echo base_url();?>"><i class="fa fa-dashboard"></i><?php echo $welcome->loadPo('Home'); ?> </a></li>
			</ol>
		</section>
		<!-- Main content -->
		<section class="content">                
			<div id="content">
				<div class="row">
					<!-- left column -->
					<div class="col-md-12">
						<!-- general form elements -->
						<div class="box box-primary">
							<div class="box-header">
								<h3 class="box-title"><?php echo $welcome->loadPo('Edit').' '.$welcome->loadPo('Transcode'); ?></h3>
								<div class="box-tools pull-right">
									<a href="<?php echo base_url(); ?>transcode" class="btn btn-default btn-sm"><?php echo $welcome->loadPo('Back'); ?></a>
								</div>
							</div><!-- /.box-header -->
							<!-- form start -->
							<?php foreach($edit as $value){?>
							<form action="" id="TranscodeTranscodeoprForm" method="post" accept-charset="utf-8">
								<div style="display:none;"><input type="hidden" name="_method" value="POST"/></div>
								<div class="box-body">
									<div class="row">
										<div class="form-group col-lg-3">
											<div class="input text">
												<label for="TranscodeFlavorName"><?php echo $welcome->loadPo('Flavor').' '.$welcome->loadPo('Name'); ?> </label>
												<input name="flavor_name" class="form-control" placeholder="<?php echo $welcome->loadPo('Flavor').' '.$welcome->loadPo('Name'); ?> " maxlength="255" type="text" id="TranscodeFlavorName" value="<?php echo $value->flavor_name; ?>" />
												<?php echo form_error('flavor_name','<span class="text-danger">','</span>'); ?>
											</div>
										</div>
										<div class="form-group col-lg-3">
											<div class="input select">
												<label for="TranscodeDeviceName"><?php echo $welcome->loadPo('Device'); ?></label>
												<select name="device_name" class="form-control" placeholder="<?php echo $welcome->loadPo('Device'); ?>" id="TranscodeDeviceName">
													<option value="">--<?php echo $welcome->loadPo('Select Device Name'); ?>--</option>
													
														<option value="iphone" <?php if($value->device_name == 'iphone'){ echo 'selected="selected"';} ?> >Iphone</option>
														<option value="andriod" <?php if($value->device_name == 'andriod'){ echo 'selected="selected"';} ?>>Andriod</option>
														<option value="windows" <?php if($value->device_name == 'windows'){ echo 'selected="selected"';} ?>>Windows</option>
														<option value="blackberry" <?php if($value->device_name == 'blackberry'){ echo 'selected="selected"';} ?>>Blackberry</option>
																								
												</select>
												<?php echo form_error('device_name','<span class="text-danger">','</span>'); ?>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="form-group col-lg-3">
											<div class="input text">
												<label for="TranscodeBitrateType"><?php echo $welcome->loadPo('Bitrate').' '.$welcome->loadPo('Type'); ?> </label>
												<input name="bitrate_type" class="form-control" placeholder="<?php echo $welcome->loadPo('Bitrate').' '.$welcome->loadPo('Type'); ?> " maxlength="50" type="text" id="TranscodeBitrateType" value="<?php echo $value->bitrate_type; ?>"/>
												<?php echo form_error('bitrate_type','<span class="text-danger">','</span>'); ?>
											</div>
										</div>
										<div class="form-group col-lg-3">
											<div class="input number">
												<label for="TranscodeBitrate"><?php echo $welcome->loadPo('Bitrate'); ?></label>
												<input name="bitrate" class="form-control" placeholder="<?php echo $welcome->loadPo('Bitrate'); ?> " type="number" id="TranscodeBitrate" value="<?php echo $value->bitrate; ?>"/>
												<?php echo form_error('bitrate','<span class="text-danger">','</span>'); ?>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="form-group col-lg-3">
											<label for="code"><?php echo $welcome->loadPo('Video').' '.$welcome->loadPo('Bitrate'); ?> </label>&nbsp;<a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="The average encoding rate for the video, in kilobits per second (kbps). For VBR-encoded videos, the encoding rate at any point may vary considerably from the average rate. Higher bitrates require both higher bandwidth and more CPU power from the viewer's device. The bitrate must be between 96 and 5000 kbps.">(?)</a>
											<div class="input number">
												<input name="video_bitrate" class="form-control" placeholder="<?php echo $welcome->loadPo('Video').' '.$welcome->loadPo('Bitrate'); ?> " type="number" id="TranscodeVideoBitrate" value="<?php echo $value->video_bitrate; ?>"/>
												<?php echo form_error('video_bitrate','<span class="text-danger">','</span>'); ?>
											</div>
										</div>
										<div class="form-group col-lg-3">
											<label for="code"><?php echo $welcome->loadPo('Audio').' '.$welcome->loadPo('Bitrate'); ?></label>&nbsp;<a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="The average encoding rate for the audio, in kilobits per second (kbps). Video Cloud supports audio bitrates from 8 to 320 kbps.">(?)</a>
											<div class="input number">
												<input name="audio_bitrate" class="form-control" placeholder="<?php echo $welcome->loadPo('Audio').' '.$welcome->loadPo('Bitrate'); ?> " type="number" id="TranscodeAudioBitrate" value="<?php echo $value->audio_bitrate; ?>"/>
												<?php echo form_error('audio_bitrate','<span class="text-danger">','</span>'); ?>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="form-group col-lg-3">
											<div class="input text">
												<label for="TranscodeWidth"><?php echo $welcome->loadPo('Width'); ?></label>
												<input name="width" class="form-control" placeholder="<?php echo $welcome->loadPo('Width'); ?>" maxlength="50" type="text" id="TranscodeWidth" value="<?php echo $value->width; ?>"/>
												<?php echo form_error('width','<span class="text-danger">','</span>'); ?>
											</div>
										</div>
										<div class="form-group col-lg-3">
											<div class="input text">
												<label for="TranscodeHeight"><?php echo $welcome->loadPo('Height'); ?></label>
												<input name="height" class="form-control" placeholder="<?php echo $welcome->loadPo('Height'); ?>" maxlength="50" type="text" id="TranscodeHeight" value="<?php echo $value->height; ?>"/>
												<?php echo form_error('height','<span class="text-danger">','</span>'); ?>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="form-group col-lg-3">
											<div class="input number">
												<label for="TranscodeFrameRate"><?php echo $welcome->loadPo('Frame Rate (FPS)'); ?></label>
												<input name="frame_rate" class="form-control" placeholder="<?php echo $welcome->loadPo('Frame Rate (FPS)'); ?>" type="number" id="TranscodeFrameRate" value="<?php echo $value->frame_rate; ?>"/>
												<?php echo form_error('frame_rate','<span class="text-danger">','</span>'); ?>
											</div>
										</div>
										<div class="form-group col-lg-3">
											<div class="input number">
												<label for="TranscodeKeyframeRate"><?php echo $welcome->loadPo('Keyframe Rate'); ?></label>
												<input name="keyframe_rate" class="form-control" placeholder="<?php echo $welcome->loadPo('Keyframe Rate'); ?>" type="number" id="TranscodeKeyframeRate" value="<?php echo $value->keyframe_rate; ?>"/>
												<?php echo form_error('keyframe_rate','<span class="text-danger">','</span>'); ?>
											</div>
										</div>
									</div>
								</div><!-- /.box-body -->
								<div class="box-footer">
									<button type="submit" name="submit" value="Update" class="btn btn-primary"><?php echo $welcome->loadPo('Update'); ?></button>
									<a href="<?php echo base_url(); ?>category" class="btn btn-default"><?php echo $welcome->loadPo('Cancel'); ?></a>  
								</div>
							</form>
							<?php }?>
						</div><!-- /.box -->
					</div><!--/.col (left) -->
				</div>
			</div>
		</section><!-- /.content -->
    </aside><!-- /.right-side -->
</div><!-- ./wrapper -->
