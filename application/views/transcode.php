<!--div class="wrapper row-offcanvas row-offcanvas-left"-->
	<aside class="content-wrapper">                
		<!-- Content Header (Page header) -->
		<section class="content-header">
            <h1><?php echo $welcome->loadPo('Transcode'); ?><small><?php echo $welcome->loadPo('Control panel'); ?></small></h1>
			 <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>"><i class="fa fa-dashboard"></i><?php echo $welcome->loadPo('Dashboard') ?></a></li>
            <li class="active"><?php echo $welcome->loadPo('Transcode') ?></li>
        </ol>
		</section>
		<div>
			<div id="msg_div">
				<?php echo $this->session->flashdata('message');?>
			</div>	
		</div>
	
		<!-- Main content -->
		<section class="content">
			<div id="content">
				<div class="row">
					<!-- left column -->
					<div class="col-md-12">
						<!-- general form elements -->
						<div class="box box-primary">
							<div class="box-header">
								<h3 class="box-title"><?php echo $welcome->loadPo('Search'); ?></h3>
								<div class="box-tools pull-right">
									<a href="<?php echo base_url(); ?>transcode/addTranscode" class="btn btn-success btn-sm"><?php echo $welcome->loadPo('Transcode').' '.$welcome->loadPo('Add'); ?></a>
								</div>
							</div><!-- /.box-header -->
							<!-- form start -->
							<form action="<?php echo base_url(); ?>transcode/index" id="searchTranscodeForm" method="post" accept-charset="utf-8">
								<div style="display:none;"><input type="hidden" name="_method" value="POST"/></div>
								<div class="box-body">
									<div class="row">
										<div class="form-group col-lg-3">
											<div class="input text">
												<label for="searchFlavorName"><?php echo $welcome->loadPo('Flavor').' '.$welcome->loadPo('Name'); ?></label>
												<input name="flavor_name" class="form-control" placeholder="<?php echo $welcome->loadPo('Flavor').' '.$welcome->loadPo('Name'); ?>" type="text" id="searchFlavorName" value="<?php echo (isset($search_data['flavor_name']))? $search_data['flavor_name']:'';  ?>"/>
											</div>
										</div>
										<div class="form-group col-lg-3">
											<div class="input select">
												<label for="searchDeviceName"><?php echo $welcome->loadPo('Device'); ?></label>
												<select name="device_name" class="form-control" placeholder="<?php echo $welcome->loadPo('Device'); ?>" id="searchDeviceName">
													<option value="">--<?php echo $welcome->loadPo('Select'); ?>--</option>
													<?php foreach($allTranscode as $val){
														if($val->device_name){
													?>
														<option value="<?php echo $val->device_name; ?>" <?php if(isset($search_data['device_name'])&& $val->device_name==$search_data['device_name']){ echo 'selected="selected"';}?>><?php echo $val->device_name;?></option>
													<?php }} ?>
												</select>
											</div>
										</div>
									</div>
								</div><!-- /.box-body -->
								<div class="box-footer">
									<button type="submit" name="submit" value="Search" class="btn btn-primary"><?php echo $welcome->loadPo('Search'); ?></button>
								</div>
							</form>        
						</div><!-- /.box -->
					</div><!--/.col (left) -->
				</div>
				<div class="row">
					<div class="col-xs-12">
						<div class="box">
							<div class="box-body table-responsive">
								<table id="example1" class="table table-bordered table-striped">
									<thead>
										<tr>
											<th><a href="<?php echo base_url();?>transcode/index/device_name/<?php echo (!empty($show_d))?$show_d:'desc';?>"><?php echo $welcome->loadPo('Device').' '.$welcome->loadPo('Name'); ?></a></th>
											<th><a href="<?php echo base_url();?>transcode/index/flavor_name/<?php echo (!empty($show_f))?$show_f:'desc';?>"><?php echo $welcome->loadPo('Flavor').' '.$welcome->loadPo('Name'); ?></a></th>
											<th><a href="<?php echo base_url();?>transcode/index/bitrate/<?php echo (!empty($show_b))?$show_b:'desc';?>"><?php echo $welcome->loadPo('Bitrate'); ?></a></th>
											<th><?php echo $welcome->loadPo('Video').' '.$welcome->loadPo('Bitrate'); ?></th>
											<th><?php echo $welcome->loadPo('Audio').' '.$welcome->loadPo('Bitrate'); ?></th>
											<th><?php echo $welcome->loadPo('Width'); ?></th>
											<th><?php echo $welcome->loadPo('Height'); ?></th>
											<th><?php echo $welcome->loadPo('Frame Rate (FPS)'); ?></th>
											<th><?php echo $welcome->loadPo('Keyframe Rate'); ?></th>
											<th><?php echo $welcome->loadPo('Action'); ?></th>
										</tr>
									</thead>
									<tbody>
										<?php 
										if($transcode)
										{
											foreach($transcode as $value){?>
											<tr id="trans_<?php echo $value->id;?>">
												<td><?php echo $value->device_name; ?></td>
												<td><?php echo $value->flavor_name; ?></td>											
												<td><?php echo $value->bitrate;?></td>
												<td><?php echo $value->video_bitrate;?></td>
												<td><?php echo $value->audio_bitrate;?></td>
												<td><?php echo $value->width;?></td>
												<td><?php echo $value->height;?></td>
												<td><?php echo $value->frame_rate;?></td>
												<td><?php echo $value->keyframe_rate;?></td>
												<td>
													<a href="<?php echo base_url(); ?>transcode/addTranscode?action=<?php echo base64_encode($value->id);?>" class="btn btn-info btn-sm"><?php echo $welcome->loadPo('Edit'); ?></a>&nbsp;
													
													<a class="confirm" onclick="return delete_transcode(<?php echo $value->id;?>);" href="" ><button class="btn btn-danger btn-sm" data-toggle="modal" data-target=".bs-example-modal-sm" ><?php echo $welcome->loadPo('Delete'); ?></button></a>
													
												</td>
											</tr>
											<?php }}else{?>
												<tr >
													<td colspan="10"><?php echo $welcome->loadPo('No Records Found'); ?></td>
												</tr>
											<?php } ?>
									</tbody>
								</table>  
								<!-- Pagination start --->
								<div class="row pull-right">
									<div class="col-xs-12">
										<div class="dataTables_paginate paging_bootstrap">
											<ul class="pagination"><li><?php echo $links ?></li></ul> 
										</div>
									</div>
								</div>
								<!-- Pagination end -->
                            </div><!-- /.box-body -->
						</div><!-- /.box -->
					</div>
				</div>
			</div>
		</section><!-- /.content -->
    </aside><!-- /.right-side -->
<!--/div--><!-- ./wrapper -->

<!-- Modal -->
<div class="modal fade" id="confirm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="myModalLabel">Delete Transcode</h4>
			</div>
			<div class="modal-body">
				Are you sure you want to delete Transcode?
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">cancel</button>
				<input type="hidden" name="modid" id="modid">
				<input type="hidden" name="modpage" id="modpage">
				<button type="button" class="btn btn-primary" onClick="return del_page();">Delete</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal -->
</div>
<script>
	function delete_transcode(id)
	{
		bootbox.confirm("<?php echo $welcome->loadPo('Are you sure you want to delete Transcode?'); ?>", function(confirmed) 
		{
			if (confirmed) 
			{
				location.href = '<?php echo base_url();?>transcode/deleteTranscode?id='+id ;
			}
		})
	}
</script> 
