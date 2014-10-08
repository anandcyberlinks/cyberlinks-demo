<div class="wrapper row-offcanvas row-offcanvas-left">
	<!-- Right side column. Contains the navbar and content of the page -->
    <aside class="right-side">                
		<!-- Content Header (Page header) -->
		<section class="content-header">
			<h1><?php echo $welcome->loadPo('Category'); ?><small><?php echo $welcome->loadPo('Control panel'); ?></small></h1>
			 <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>"><i class="fa fa-dashboard"></i><?php echo $welcome->loadPo('Dashboard') ?></a></li>
            <li><a href="<?php echo base_url() ?>role"><i class="fa fa-laptop"></i><?php echo $welcome->loadPo('Category') ?></a></li>
            <li class="active"><?php echo $welcome->loadPo('Add Category') ?></li>
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
								<h3 class="box-title"><?php echo $welcome->loadPo('Category').' '.$welcome->loadPo('Add'); ?></h3>
								<div class="box-tools pull-right">
									<a href="<?php echo base_url(); ?>category" class="btn btn-default btn-sm"><?php echo $welcome->loadPo('Back'); ?></a>
								</div>
							</div><!-- /.box-header -->
							<!-- form start -->
							<form action="" id="CategoryForm" method="post" accept-charset="utf-8">
								<div style="display:none;"><input type="hidden" name="_method" value="POST"/></div>
								<input type="hidden" name="data[Category][id]" id="CategoryId"/>
								<div class="box-body">
									<div class="row">
										<div class="form-group col-lg-5">
											<div class="input text">
												<label for="Category"><?php echo $welcome->loadPo('Category').' '.$welcome->loadPo('Name'); ?></label>
												<input name="category" class="form-control" placeholder="<?php echo $welcome->loadPo('Category').' '.$welcome->loadPo('Name'); ?>" maxlength="255" type="text" id="Category" value="<?php echo set_value('category'); ?>" onblur="category_check(this.value);" />
												<?php echo form_error('category','<span class="text-danger">','</span>'); ?>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="form-group col-lg-5">
											<div class="input select">
												<label for="searchParentId"><?php echo $welcome->loadPo('Parent').' '.$welcome->loadPo('Category'); echo  set_value('parent_id');?> </label>
												<select name="parent_id" class="form-control" placeholder="Parent Category" id="searchParentId">
													<option value="">--<?php echo $welcome->loadPo('Select'); ?>--</option>
													<?php foreach($allParentCategory as $cat){?>
                                                            <option value="<?php echo $cat->id;?>" <?php if($cat->id == set_value('parent_id')){ echo 'selected="selected"';}?>><?php echo $cat->category;?></option>
													<?php } ?>
												</select>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="form-group col-lg-5">
											<label for="Description"><?php echo $welcome->loadPo('Description'); ?></label>
											<textarea name="description" class="form-control" placeholder="<?php echo $welcome->loadPo('Description'); ?>" id="Description"><?php if(isset($_POST['description'])){echo $_POST['description'] ;} ?></textarea>
											<?php echo form_error('description','<span class="text-danger">','</span>'); ?>
										</div>
									</div>
									<div class="row">    
										<div class="form-group col-lg-5">
											<label for="Status"><?php echo $welcome->loadPo('Status'); ?>
											<input type="hidden" name="status" id="CategoryStatus_" value="0"/>
											<span align="left"><input type="checkbox" name="status" value="1"/></span></label>
											
										</div>
									</div>
								</div><!-- /.box-body -->
								<div class="box-footer">
									<button type="submit" name="submit" value="Submit" class="btn btn-primary"><?php echo $welcome->loadPo('Submit'); ?></button>
									<a href="<?php echo base_url(); ?>category" class="btn btn-default"><?php echo $welcome->loadPo('Cancel'); ?></a>
								</div>
							</form>
						</div><!-- /.box -->
					</div><!--/.col (left) -->
				</div>
			</div>
		</section><!-- /.content -->
    </aside><!-- /.right-side -->
</div><!-- ./wrapper -->
