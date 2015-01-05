<?php echo $uri = $this->uri->segment(1); ?>
<div class="wrapper row-offcanvas row-offcanvas-left">
	<!-- Right side column. Contains the navbar and content of the page -->
	<aside class="right-side">                
		<!-- Content Header (Page header) -->
		<section class="content-header">
			<h1><?php echo $welcome->loadPo('Category'); ?> <small><?php echo $welcome->loadPo('Control panel'); ?></small></h1>
			<ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>"><i class="fa fa-dashboard"></i><?php echo $welcome->loadPo('Dashboard') ?></a></li>
		<li><a href="<?php echo base_url().$uri; ?>"><i class="fa fa-dashboard"></i><?php echo $welcome->loadPo('Category') ?></a></li>
	    <li class="active"><?php echo $welcome->loadPo('Edit')." ".$welcome->loadPo('Category') ?></li>
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
								<h3 class="box-title"><?php echo $welcome->loadPo('Category').' '.$welcome->loadPo('Edit'); ?></h3>
								<div class="box-tools pull-right">
									<a href="<?php echo base_url().$uri; ?>" class="btn btn-default btn-sm"><?php echo $welcome->loadPo('Back'); ?></a>
								</div>
							</div><!-- /.box-header -->
							<link href="<?php echo base_url();?>assets/css/colorpicker/bootstrap-colorpicker.min.css" rel="stylesheet" type="text/css" />
							<!-- form start -->
							<?php  foreach ($edit as $value){?> 							
							<form action="" id="CategoryCategoryoprForm" method="post" accept-charset="utf-8" enctype="multipart/form-data">
								<input type="hidden" name="catOldFileId" id="catOldFileId" value="<?php echo $value->file_id; ?>" />
								<div class="box-body">
									<div class="row">
										<div class="form-group col-lg-5">
											<div class="input text">
												<label for="Category"><?php echo $welcome->loadPo('Category').' '.$welcome->loadPo('Name'); ?></label>
												<input name="category" class="form-control" placeholder="Category Name" maxlength="255" type="text" value="<?php echo $value->category;?>" id="Category"/>
												<?php echo form_error('category','<span class="text-danger">','</span>'); ?>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="form-group col-lg-5">
											<div class="input select">
												<label for="CategoryParentId"><?php echo $welcome->loadPo('Parent'); ?></label>
												<select name="parent_id" id="parent_id" class="form-control">
													<option value=""  <?php echo set_select('parent', '', TRUE); ?>>--<?php echo $welcome->loadPo('Select');?>--</option>
													<?php
													
													foreach($allParentCategory as $cvalue){													
													if($value->id != $cvalue->parent_id)
													{
														//if($cvalue->parent_id == 0){	
														if($value->category != $cvalue->category){	
													?>
														<option value="<?php echo $cvalue->id;?>" <?php echo ($cvalue->category==$value->parent)?"selected='selected'":''; ?> ><?php  echo ucfirst($cvalue->category);?></option>
													<?php }}}?>
												</select>
												<?php echo form_error('parent_id','<span class="text-danger">','</span>'); ?>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="form-group col-lg-5">
											<label for="Description"><?php echo $welcome->loadPo('Description'); ?></label>
											<textarea name="description" class="form-control" placeholder="Description" id="CategoryDescription"><?php echo $value->description;?></textarea>
											<?php echo form_error('description','<span class="text-danger">','</span>'); ?>
										</div>
									</div>
									<div class="row"> 
										<div class="form-group col-lg-5">
											<label for="categoryColor"><?php echo $welcome->loadPo('Color'); ?></label>
											<div class="input-group my-colorpicker">                                            
												<div class="input text">
													<input name="color" class="form-control" placeholder="<?php echo $welcome->loadPo('Color'); ?>" type="text" value="<?php echo $value->color;?>" id="color"/>
												</div>                                                
												<div class="input-group-addon">
													<i></i>
												</div>
											</div>
										</div>
									</div>
									<div class="row"> 
										<div class="form-group col-lg-5">
											<label for="categoryImage"><?php echo $welcome->loadPo('Image'); ?></label>&nbsp;&nbsp;
											<span class="btn btn-default btn-file btn-sm">
												<?php echo  $welcome->loadPo('Choose Media') ?> <input name="categoryImage"  id="categoryImage"  atr="files" type="file"/>
											</span>
										</div>
									</div>
									<?php if((isset($value->filename)) && ($value->filename !='')){ ?>
									<div class="row">
										<div class="form-group col-lg-5">
											<img src="<?php echo baseurl.CATEGORY_SMALL_PATH.$value->filename; ?>"  />
										</div>
									</div>
									<?php } ?>
									<div class="row">    
										<div class="form-group col-lg-5">
											<label for="Status"><?php echo $welcome->loadPo('Status'); ?></label>&nbsp;&nbsp;
											<input type="checkbox"  name="status"<?php if($value->status == 1){ echo "checked";   }else{ echo ""; }?> />
											
										</div>
									</div>
								</div><!-- /.box-body -->
								<div class="box-footer">
									<button type="submit" name="submit" value="Update" class="btn btn-primary"><?php echo $welcome->loadPo('Update'); ?></button>
									<a href="<?php echo base_url().$uri; ?>" class="btn btn-default"><?php echo $welcome->loadPo('Cancel'); ?></a>            
								</div>
							</form>
							<?php } ?>
							<script src="<?php echo base_url();?>assets/js/plugins/colorpicker/bootstrap-colorpicker.min.js" type="text/javascript"></script>
							<script>
								$(function(){
									//Colorpicker
									$('.my-colorpicker').colorpicker();
								});
							</script>

						</div><!-- /.box -->
					</div><!--/.col (left) -->
				</div>		
			</div>
		</section><!-- /.content -->
    </aside><!-- /.right-side -->
</div><!-- ./wrapper -->
	