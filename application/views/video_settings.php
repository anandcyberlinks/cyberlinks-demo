<div class="wrapper row-offcanvas row-offcanvas-left">
	<!-- Right side column. Contains the navbar and content of the page -->
    <aside class="right-side">                
		<!-- Content Header (Page header) -->
		<section class="content-header">
			<h1><?php echo  $welcome->loadPo('Video') ?><small><?php echo  $welcome->loadPo('Control panel') ?></small></h1>
			<ol class="breadcrumb">
				<li><a href="<?php echo base_url(); ?>"><i class="fa fa-dashboard"></i><?php echo $welcome->loadPo('Dashboard') ?></a></li>
                                <li><a href="<?php echo base_url(); ?>video"><i class="fa fa-play-circle"></i><?php echo $welcome->loadPo('Video') ?></a></li>
                                <li class="active"><?php echo $welcome->loadPo('Video')." ".$welcome->loadPo('Settings') ?></li>
			</ol>
		</section>
		<div>
			<div id="msg_div">	
				<?php if(isset($msg)) { echo $msg;  } ?> 
				<?php echo $this->session->flashdata('message');?>
			</div>	
		</div>
		<!-- Main content -->
		<section class="content">
			<div id="content">
				<div class="row">
					<div class="col-md-12">
						<!-- Custom Tabs -->
						<div class="nav-tabs-custom">
							<ul class="nav nav-tabs">
								<li class="<?=($tab==='Flavors')?'active':''?>"><a href="<?php echo base_url();?>video/setting/Flavors" ><?php echo $welcome->loadPo('Flavors'); ?></a></li>
								<li class="<?=($tab==='Player')?'active':''?>"><a href="<?php echo base_url();?>video/setting/Player" ><?php echo $welcome->loadPo('Player'); ?></a></li>
								<li class="pull-right">&nbsp;</li>
							</ul>
							<div class="tab-content">
								 <link href="<?php echo base_url();?>assets/css/colorpicker/bootstrap-colorpicker.min.css" rel="stylesheet" type="text/css" />
								<!-- Flavors section starts -->
								<?php if($tab == 'Flavors') {?>
								<div class="tab-pane active" id="tab_flavor">
									<div class="box box-solid">
									<form action="<?php echo base_url() ?>video/setting_flavor" id="videoFlavorSetting" method="post" accept-charset="utf-8" >
										<input type="hidden" id="redirect_url" name="redirect_url" value="<?php echo current_full_url(); ?>" />
										<div class="box-body no-padding">
											<table class="table">
												<tbody>
													<tr>
														<th>#</th>
														<th><?php echo $welcome->loadPo('Device Name'); ?></th>
														<th><?php echo $welcome->loadPo('Flavor Name'); ?></th>
														<th><?php echo $welcome->loadPo('Bitrate'); ?></th>
														<th><?php echo $welcome->loadPo('Video Bitrate'); ?></th>
														<th><?php echo $welcome->loadPo('Audio Bitrate'); ?></th>
														<th><?php echo $welcome->loadPo('Resolution'); ?></th>
														<th><?php echo $welcome->loadPo('Frame Rate (FPS)'); ?></th>
														<th><?php echo $welcome->loadPo('Keyframe Rate'); ?></th>
													</tr>
													<?php if($flavorData){
													foreach($flavorData as $value ){?>
													<tr>
														<!--<td><input type="checkbox" class="minimal"  name="status[]" value="<?php echo $value->id;?>"/></td>-->
														<td>
															<?php 
															if (isset($value) && is_array($optionData) && array_key_exists('flavor_' . $value->id, $optionData)) { 
																echo sprintf('<input type="checkbox" id="%d" name="flavors[flavor_%d]" class="minimal" checked="checked" /> ', $value->id, $value->id);
															} else { 
																echo sprintf('<input type="checkbox" id="%d" name="flavors[flavor_%d]" class="minimal"  /> ', $value->id, $value->id);
															}
															?>
														</td>
														<td><?php echo $value->device_name; ?></td>
														<td><?php echo $value->flavor_name; ?></td>											
														<td><?php echo $value->bitrate;?></td>
														<td><?php echo $value->video_bitrate;?></td>
														<td><?php echo $value->audio_bitrate;?></td>
														<td><?php echo $value->width.' * '.$value->height;?></td>
														<td><?php echo $value->frame_rate;?></td>
														<td><?php echo $value->keyframe_rate;?></td>
													</tr>	
													<?php }}else{?>
														<tr >
															<td colspan="10">No Records Found</td>
														</tr>
													<?php } ?>
												</tbody>
										  </table>
										</div><!-- /.box-body -->
										<div class="box-footer">
											<button class="btn btn-primary btn-sm" type="submit" name="submit" value="Save"><?php echo $welcome->loadPo('Save'); ?></button>										
										</div>
									</form>
									</div><!-- /.box -->
								</div>
								<?php } ?>
								<!-- Flavors section ends -->
								
								<!-- Player section starts -->
								<?php if($tab == 'Player') {?>
								<div class="tab-pane active" id="tab_Player">
									<form action="<?php echo base_url() ?>video/setting_player" id="playerSettingForm" method="post" accept-charset="utf-8" enctype="multipart/form-data" accept-charset="utf-8" onsubmit="upload_logo_video();" >
									<input type="hidden" id="redirect_url" name="redirect_url" value="<?php echo current_full_url(); ?>" />
										<div class="box-group" id="accordion">
										<?php  $data_player = @unserialize(strip_slashes($playerData)); ?>
										<input type="hidden" name="logo_imghiddennw" id="logo_imghiddennw" value="<?php echo $data_player['file']; ?>"/>
											<div class="panel box box-solid">
												<div class="box-header">
													<h4 class="box-title">
														<a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
															<?php echo $welcome->loadPo('General')." ".$welcome->loadPo('Settings'); ?>
														</a>
													</h4>
												</div>
												<div id="collapseOne" class="panel-collapse collapse in">
													<div class="box-body">
														<div class="row">
															<div class="form-group col-lg-6">
																<div class="input select">
																	<label for="playerPlayerType"><?php echo $welcome->loadPo('Player')." ".$welcome->loadPo('Type'); ?> </label>
																	<select name="player[player_type]" class="form-control" id="playerPlayerType">
																		<option value=""><?php echo $welcome->loadPo('Select'); ?></option>
																		<option value="html5" <?php if($data_player['player']['player_type'] == 'html5') { echo 'selected="selected"'; } ?>>Html 5</option>
																		<option value="flash" <?php if($data_player['player']['player_type'] == 'flash') { echo 'selected="selected"'; } ?>>Flash</option>
																	</select>
																</div>                                       
															</div>
															<div class="form-group col-lg-6">
																<div class="input select">
																	<label for="playerPlayerSkin"><?php echo $welcome->loadPo('Player')." ".$welcome->loadPo('Skin'); ?></label>
																	<select name="player[player_skin]" class="form-control" id="playerPlayerSkin">
																		<option value=""><?php echo $welcome->loadPo('Select'); ?></option>
																		<option value="beelden" <?php if($data_player['player']['player_skin'] == 'beelden') { echo 'selected="selected"'; } ?> >Beelden</option>
																		<option value="bekle" <?php if($data_player['player']['player_skin'] == 'bekle') { echo 'selected="selected"'; } ?> >Bekle</option>
																		<option value="five" <?php if($data_player['player']['player_skin'] == 'five') { echo 'selected="selected"'; } ?>>Five</option>
																		<option value="glow" <?php if($data_player['player']['player_skin'] == 'glow') { echo 'selected="selected"'; } ?>>Glow</option>
																		<option value="roundster" <?php if($data_player['player']['player_skin'] == 'roundster') { echo 'selected="selected"'; } ?>>Roundster</option>
																		<option value="six" <?php if($data_player['player']['player_skin'] == 'six') { echo 'selected="selected"'; } ?>>Six</option>
																		<option value="stormtrooper" <?php if($data_player['player']['player_skin'] == 'stormtrooper') { echo 'selected="selected"'; } ?>>Stormtrooper</option>
																		<option value="vapor" <?php if($data_player['player']['player_skin'] == 'vapor') { echo 'selected="selected"'; } ?>>Vapor</option>
																	</select>
																</div>                                        
															</div>
														</div>
														<div class="row">
															<div class="form-group col-lg-6">
																<div class="input number"><label for="playerHeight"><?php echo $welcome->loadPo('Height'); ?></label><input name="player[height]" class="form-control" placeholder="Height" type="number" value="<?php if($data_player['player']['height']) { echo $data_player['player']['height']; } else { echo '390'; } ?>" id="playerHeight"/></div>                                       
															</div>
															<div class="form-group col-lg-6">
																<div class="input number"><label for="playerWidth"><?php echo $welcome->loadPo('Width'); ?></label><input name="player[width]" class="form-control" placeholder="Width" type="number" value="<?php if($data_player['player']['height']) { echo $data_player['player']['width']; } else { echo '475'; } ?>" id="playerWidth"/></div>                                        
															</div>
														</div>
														<div class="row">
															<div class="form-group col-lg-6">
																<div class="input select">
																	<label for="playerPlayerAspectration"><?php echo $welcome->loadPo('Aspect Ratio '); ?> </label>
																	<select name="player[player_aspectration]" class="form-control" id="playerPlayerAspectration">
																		<option value=""><?php echo $welcome->loadPo('Select'); ?></option>
																		<option value="4:3" <?php if($data_player['player']['player_aspectration'] == '4:3') { echo 'selected="selected"'; } ?>>4:3</option>
																		<option value="16:9" <?php if($data_player['player']['player_aspectration'] == '16:9') { echo 'selected="selected"'; } ?>>16:9</option>
																		<option value="21:9" <?php if($data_player['player']['player_aspectration'] == '21:9') { echo 'selected="selected"'; } ?>>21:9</option>
																	</select>
																</div>                                        
															</div>
														</div>
														<div class="row">
															<div class="form-group col-lg-2">
																<label><input type="hidden" name="player[related_video]" id="playerRelatedVideo_" value="0"/><input type="checkbox" name="player[related_video]"  class="form-control" value="1" id="playerRelatedVideo" <?php if($data_player['player']['related_video'] == 1) { echo 'checked="checked"'; }?>/>&nbsp;<?php echo $welcome->loadPo('Related')." ".$welcome->loadPo('Video'); ?> </label>
															</div>
															<div class="form-group col-lg-2">
																<label><input type="hidden" name="player[social_sharing]" id="playerSocialSharing_" value="0"/><input type="checkbox" name="player[social_sharing]"  class="form-control" value="1" id="playerSocialSharing" <?php if($data_player['player']['social_sharing'] == 1) { echo 'checked="checked"';  }?>/>&nbsp;&nbsp;<?php echo $welcome->loadPo('Social Sharing'); ?> </label>
															</div>
															<div class="form-group col-lg-2">
																<label><input type="hidden" name="player[repeat_video]" id="playerRepeatVideo_" value="0"/><input type="checkbox" name="player[repeat_video]"  class="form-control" value="1" id="playerRepeatVideo" <?php if($data_player['player']['repeat_video'] == 1) { echo 'checked="checked"'; } ?>/>&nbsp;&nbsp;<?php echo $welcome->loadPo('Repeat')." ".$welcome->loadPo('Video'); ?> </label>
															</div>
															<div class="form-group col-lg-2">
																<label><input type="hidden" name="player[auto_start]" id="playerAutoStart_" value="0"/><input type="checkbox" name="player[auto_start]"  class="form-control" value="1" id="playerAutoStart" <?php if($data_player['player']['auto_start'] == 1) { echo 'checked="checked"'; }?>/>&nbsp;&nbsp;<?php echo $welcome->loadPo('Auto')." ".$welcome->loadPo('Start'); ?> </label>
															</div>
															<div class="form-group col-lg-2">
																<label><input type="hidden" name="player[controls]" id="playerControls_" value="0"/><input type="checkbox" name="player[controls]"  class="form-control" value="1" id="playerControls" <?php if($data_player['player']['controls'] == 1) { echo 'checked="checked"';} ?>/>&nbsp;&nbsp;<?php echo $welcome->loadPo('Controls'); ?></label>
															</div>
															<div class="form-group col-lg-2">
																<label><input type="hidden" name="player[google_analytics]" id="playerGoogleAnalytics_" value="0"/><input type="checkbox" name="player[google_analytics]"  class="form-control" value="1" id="playerGoogleAnalytics" <?php if($data_player['player']['google_analytics'] == 1) { echo 'checked="checked"';}?>/>&nbsp;&nbsp;<?php echo $welcome->loadPo('Google Analytics'); ?> </label>
															</div>
															<div class="form-group col-lg-2">
																<label><input type="hidden" name="player[mute]" id="playerMute_" value="0"/><input type="checkbox" name="player[mute]"  class="form-control" value="1" id="playerMute" <?php if($data_player['player']['mute'] == 1) { echo 'checked="checked"'; } ?>/>&nbsp;&nbsp;<?php echo $welcome->loadPo('Mute'); ?></label>
															</div>
															<div class="form-group col-lg-2">
																<label><input type="hidden" name="player[androidhls]" id="playerAndroidhls_" value="0"/><input type="checkbox" name="player[androidhls]"  class="form-control" value="1" id="playerAndroidhls" <?php if($data_player['player']['androidhls'] == 1) { echo 'checked="checked"'; } ?>/>&nbsp;&nbsp;<?php echo $welcome->loadPo('Androidhls'); ?></label>
															</div>
														</div>
													</div>
												</div>
											</div>
											<div class="panel box box-solid">
												<div class="box-header">
													<h4 class="box-title">
														<a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
															<?php echo $welcome->loadPo('Logo')." ".$welcome->loadPo('Settings'); ?>
														</a>
													</h4>
												</div>
												<div id="collapseTwo" class="panel-collapse collapse">
													<div class="box-body">
														<div class="row">
															<div class="form-group col-lg-6">
																<div class="input select">
																	<label for="playerLogoPosition"><?php echo $welcome->loadPo('Logo')." ".$welcome->loadPo('Position'); ?></label>
																	<select name="player[logo_position]" class="form-control" id="playerLogoPosition">
																		<option value=""><?php echo $welcome->loadPo('Select'); ?></option>
																		<option value="top-right" <?php if($data_player['player']['logo_position'] == 'top-right') { echo 'selected="selected"'; } ?>>Top Right</option>
																		<option value="top-left" <?php if($data_player['player']['logo_position'] == 'top-left') { echo 'selected="selected"'; } ?>>Top Left</option>
																		<option value="bottom-right" <?php if($data_player['player']['logo_position'] == 'bottom-right') { echo 'selected="selected"'; } ?>>Bottom Right</option>
																		<option value="bottom-left" <?php if($data_player['player']['logo_position'] == 'bottom-left') { echo 'selected="selected"'; } ?>>Bottom Left</option>
																	</select>
																</div>                                        
															</div>
															<div class="form-group col-lg-6">
																<div class="input number">
																	<label for="playerLogoMargin"><?php echo $welcome->loadPo('Logo')." ".$welcome->loadPo('Margin'); ?> </label>
																	<input name="player[logo_margin]" class="form-control" placeholder="<?php echo $welcome->loadPo('Logo')." ".$welcome->loadPo('Margin'); ?>" type="number" value="<?php if($data_player['player']['logo_margin']) { echo $data_player['player']['logo_margin']; } else { echo "5"; } ?>" id="playerLogoMargin"/>
																</div>                                        
															</div>
														</div>
														<div class="row">
															<div class="form-group col-lg-12">
																<label><input type="hidden" name="player[logo_hide]" id="playerLogoHide_" value="0"/><input type="checkbox" name="player[logo_hide]"  class="form-control" value="1" id="playerLogoHide" checked="checked"/>&nbsp;&nbsp;<?php echo $welcome->loadPo('Logo')." ".$welcome->loadPo('Hide'); ?></label>
															</div>
														</div>
														<div class="row">
															<div class="form-group col-lg-12">																
																	<?php if($data_player['file'] != "") { ?>
																		<img src="<?php echo  base_url(); ?>assets/upload/logo/<?php echo $data_player['file']; ?>" id="logoimg" width="150" height="150"/> 
																	<?php } else { } ?>
															</div>
														</div>
														<div class="row">
															<div class="form-group col-lg-12">
																<span class="btn btn-default btn-file btn-sm">
																	<?php echo  $welcome->loadPo('Choose Logo'); ?> <input name="logo_image"  id="playerLogoLink"  type="file" accept="image/*"/>
																</span> 																
																<span class="text-red">(png)</span>
															</div>
														</div>
													</div>
												</div>
											</div>
											<div class="panel box box-solid">
												<div class="box-header">
													<h4 class="box-title">
														<a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
															<?php echo $welcome->loadPo('Caption')." ".$welcome->loadPo('Settings'); ?> 
														</a>
													</h4>
												</div>
												<div id="collapseThree" class="panel-collapse collapse">
													<div class="box-body">
														<div class="row">
															<div class="form-group col-lg-6">
																<label for="exampleInputEmail1"><?php echo $welcome->loadPo('Caption')." ".$welcome->loadPo('Color'); ?></label>
																<div class="input-group my-colorpicker">                                            
																	<div class="input text">
																		<input name="player[caption_color]" class="form-control" placeholder="<?php echo $welcome->loadPo('Caption')." ".$welcome->loadPo('Color'); ?>" type="text" value="#14ca42" id="playerCaptionColor"/>
																	</div>                                                
																	<div class="input-group-addon">
																		<i></i>
																	</div>
																</div>
															</div>
															<div class="form-group col-lg-6">
																<div class="input number">
																	<label for="playerCaptionFontsize"><?php echo $welcome->loadPo('Caption')." ".$welcome->loadPo('Font')." ".$welcome->loadPo('Size'); ?></label>
																	<input name="player[caption_fontsize]" class="form-control" placeholder="<?php echo $welcome->loadPo('Caption')." ".$welcome->loadPo('Font')." ".$welcome->loadPo('Size'); ?>" type="number" value="16" id="playerCaptionFontsize"/>
																</div>                                       
															</div>
															<div class="form-group col-lg-6">
																<div class="input select">
																	<label for="playerCaptionFontfamily"><?php echo $welcome->loadPo('Caption')." ".$welcome->loadPo('Font')." ".$welcome->loadPo('Family'); ?></label>
																	<select name="player[caption_fontfamily]" class="form-control" id="playerCaptionFontfamily">
																		<option value=""><?php echo $welcome->loadPo('Select'); ?></option>
																		<option value="Arial,Helvetica,sans-serif" <?php if($data_player['player']['caption_fontfamily'] == 'Arial,Helvetica,sans-serif') { echo 'selected="selected"'; } ?>>Arial</option>
																		<option value="Arial Black,Gadget,sans-serif" <?php if($data_player['player']['caption_fontfamily'] == 'Arial Black,Gadget,sans-serif') { echo 'selected="selected"'; } ?>>Arial Black</option>
																		<option value="Comic Sans MS,cursive" <?php if($data_player['player']['caption_fontfamily'] == 'Comic Sans MS,cursive') { echo 'selected="selected"'; } ?>>Comic Sans MS</option>
																		<option value="Courier New,Courier,monospace" <?php if($data_player['player']['caption_fontfamily'] == 'Courier New,Courier,monospace') { echo 'selected="selected"'; } ?>>Courier New</option>
																		<option value="Georgia,serif" <?php if($data_player['player']['caption_fontfamily'] == 'Georgia,serif') { echo 'selected="selected"'; } ?>>Georgia</option>
																		<option value="Lucida Console,Monaco,monospace" <?php if($data_player['player']['caption_fontfamily'] == 'Lucida Console,Monaco,monospace') { echo 'selected="selected"'; } ?>>Lucida Console</option>
																		<option value="Tahoma,Geneva,sans-serif" <?php if($data_player['player']['caption_fontfamily'] == 'Tahoma,Geneva,sans-serif') { echo 'selected="selected"'; } ?>>Tahoma</option>
																		<option value="Verdana,Geneva,sans-serif" <?php if($data_player['player']['caption_fontfamily'] == 'Verdana,Geneva,sans-serif') { echo 'selected="selected"'; } ?>>Verdana</option>
																	</select>
																</div>                                        
															</div>
															<div class="form-group col-lg-6">
																<div class="input select">
																	<label for="playerCaptionFontopacity"><?php echo $welcome->loadPo('Caption')." ".$welcome->loadPo('Font')." ".$welcome->loadPo('Opacity'); ?></label>
																	<select name="player[caption_fontopacity]" class="form-control" id="playerCaptionFontopacity">
																		<option value=""><?php echo $welcome->loadPo('Select'); ?></option>
																		<?php for($i=1; $i<=100; $i++) { ?>
																			<option value="<?php echo $i; ?>" <?php if($data_player['player']['caption_fontopacity'] == $i) { echo 'selected="selected"'; } ?>><?php echo $i; ?></option>
																		<?php } ?>
																	</select>
																</div>                                        
															</div>
															<div class="form-group col-lg-6">
																<label for="exampleInputEmail1"><?php echo $welcome->loadPo('Caption')." ".$welcome->loadPo('Background')." ".$welcome->loadPo('Color'); ?></label>
																<div class="input-group my-colorpicker">                                            
																	<div class="input text"><input name="player[caption_backgroundcolor]" class="form-control" placeholder="<?php echo $welcome->loadPo('Caption')." ".$welcome->loadPo('Background')." ".$welcome->loadPo('Color'); ?>" type="text" value="<?php echo $data_player['player']['caption_backgroundcolor'];?>" id="playerCaptionBackgroundcolor"/>
																	</div>                                                
																	<div class="input-group-addon">
																		<i></i>
																	</div>
																</div>
															</div>
															<div class="form-group col-lg-6">
																<div class="input select">
																	<label for="playerCaptionBackgroundopacity"><?php echo $welcome->loadPo('Caption')." ".$welcome->loadPo('Background')." ".$welcome->loadPo('Opacity'); ?></label>
																	<select name="player[caption_backgroundopacity]" class="form-control" id="playerCaptionBackgroundopacity">
																		<option value=""><?php echo $welcome->loadPo('Select'); ?></option>
																		<?php for($i=1; $i<=100; $i++) { ?>
																			<option value="<?php echo $i; ?>" <?php if($data_player['player']['caption_backgroundopacity'] == $i) { echo 'selected="selected"'; } ?>><?php echo $i; ?></option>
																		<?php } ?>
																	</select>
																</div>                                        
															</div>
															<div class="form-group col-lg-6">
																<label for="exampleInputEmail1"><?php echo $welcome->loadPo('Caption')." ".$welcome->loadPo('Window')." ".$welcome->loadPo('Color'); ?></label>
																<div class="input-group my-colorpicker">                                            
																	<div class="input text"><input name="player[caption_windowcolor]" class="form-control" placeholder="<?php echo $welcome->loadPo('Caption')." ".$welcome->loadPo('Window')." ".$welcome->loadPo('Color'); ?>" type="text" value="<?php echo $data_player['player']['caption_windowcolor'];?>" id="playerCaptionWindowcolor"/></div>                                                <div class="input-group-addon">
																		<i></i>
																	</div>
																</div>
															</div>
															<div class="form-group col-lg-6">
																<div class="input select">
																	<label for="playerCaptionWindowopacity"><?php echo $welcome->loadPo('Caption')." ".$welcome->loadPo('Window')." ".$welcome->loadPo('Opacity'); ?></label>
																	<select name="player[caption_windowopacity]" class="form-control" id="playerCaptionWindowopacity">
																		<option value=""><?php echo $welcome->loadPo('Select'); ?></option>
																		<?php for($i=1; $i<=100; $i++) { ?>
																				<option value="<?php echo $i; ?>" <?php if($data_player['player']['caption_windowopacity'] == $i) { echo 'selected="selected"'; } ?>><?php echo $i; ?></option>
																		<?php } ?>
																	</select>
																</div>                                        
															</div>
														</div>
													</div>
												</div>
											</div>
											<div class="panel box box-solid">
												<div class="box-header">
													<h4 class="box-title">
														<a data-toggle="collapse" data-parent="#accordion" href="#collapseFour">
															<?php echo $welcome->loadPo('Right Click')." ".$welcome->loadPo('Settings'); ?> 
														</a>
													</h4>
												</div>
												<div id="collapseFour" class="panel-collapse collapse">
													<div class="box-body">
														<div class="row">
															<div class="form-group col-lg-12">
																<div class="input text">
																	<label for="playerAboutText"><?php echo $welcome->loadPo('About Text'); ?></label>
																	<input name="player[about_text]" class="form-control" placeholder="<?php echo $welcome->loadPo('About Text'); ?>" type="text" value="<?php echo $data_player['player']['about_text']; ?>" id="playerAboutText"/>
																</div>                                        
															</div>
															<div class="form-group col-lg-12">
																<div class="input text">
																	<label for="playerAboutLink"><?php echo $welcome->loadPo('About Link'); ?></label>
																	<input name="player[about_link]" class="form-control" placeholder="<?php echo $welcome->loadPo('About Link'); ?>" type="text" value="<?php echo $data_player['player']['about_link']; ?>" id="playerAboutLink"/>
																</div>                                        
															</div>
														</div>
													</div>
												</div>
										
											</div>
										</div>
										<div class="box-footer">
											<button class="btn btn-primary btn-sm" type="submit" name="submit" value="Save"><?php echo $welcome->loadPo('Save'); ?></button>
											<button class="btn btn-default btn-sm" type="submit" name="submit" value="Set as default"><?php echo $welcome->loadPo('Set as default'); ?></button>
										</div>
									</form>                    
									<script src="<?php echo base_url();?>assets/js/plugins/colorpicker/bootstrap-colorpicker.min.js" type="text/javascript"></script>
									<script>
										$(function(){
											//Colorpicker
											$('.my-colorpicker').colorpicker();
										});

									</script>
								</div>
								<?php } ?>
								<!-- Player section ends -->
							</div><!-- /.tab-content -->
						</div><!-- nav-tabs-custom -->
					</div><!-- /.col -->
				</div> <!-- /.row -->
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

