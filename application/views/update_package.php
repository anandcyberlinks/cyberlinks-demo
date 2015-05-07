<style>
    .error
    {
      color: red;  
    }
</style>
<!--div class="wrapper row-offcanvas row-offcanvas-left"-->
	<!-- Right side column. Contains the navbar and content of the page -->
    <aside class="content-wrapper">                
		<!-- Content Header (Page header) -->
		<section class="content-header">
			<h1><?php echo $welcome->loadPo('Subscription Duration'); ?><small><?php echo $welcome->loadPo('Add New Duration'); ?></small></h1>
			<ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>"><i class="fa fa-dashboard"></i><?php echo $welcome->loadPo('Dashboard') ?></a></li>
            <li><a href="<?php echo base_url() ?>role"><i class="fa fa-laptop"></i><?php echo $welcome->loadPo('Subscription Duration') ?></a></li>
            <li class="active"><?php echo $welcome->loadPo('Add New') ?></li>
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
								<h3 class="box-title"><?php echo $welcome->loadPo('Edit').' '.$welcome->loadPo('Subscription Duration'); ?></h3>
								<div class="box-tools pull-right">
									<a href="<?php echo base_url(); ?>subscription" class="btn btn-default btn-sm"><?php echo $welcome->loadPo('Back'); ?></a>
								</div>
							</div><!-- /.box-header -->
							<!-- form start -->
							<form action="" id="registerId" method="post" accept-charset="utf-8">
							    <input type="hidden" name="id" value="<?php echo $edit['0']->id; ?>">
								<div class="box-body">
									<div class="row">
										<div class="form-group col-lg-5">
											<div class="input text">
												<label><?php echo $welcome->loadPo('Package Duration').' '.$welcome->loadPo('Name'); ?></label>
												<input name="name" class="form-control" placeholder="<?php echo $welcome->loadPo('Duration').' '.$welcome->loadPo('name'); ?>" type="text" value="<?php echo $edit['0']->name; ?>"/>
												<?php echo form_error('name','<span class="text-danger">','</span>'); ?>
												<span class="error"><?php if (isset($valid)){ echo $valid; } ?></span>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="form-group col-lg-5">
											<div class="input text">
												<label><?php echo $welcome->loadPo('Duration'); ?></label>
												<input name="days" class="form-control" placeholder="<?php echo $welcome->loadPo('Days'); ?>" type="text" value="<?php echo $edit['0']->days; ?>" />
												<?php echo form_error('days','<span class="text-danger">','</span>'); ?>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="form-group col-lg-5">
											<div class="input select">
												<label><?php echo $welcome->loadPo('Status');?> </label>
												<select name="status" class="form-control">
													<option value="">--Select--</option>
													<option value="1" <?php if($edit['0']->status == '1'){
																echo 'selected';}?> >Active</option>
													<option value="0" <?php if($edit['0']->status == '0'){
																echo 'selected';}?>>inactive</option>
												</select>
											</div>
										</div>
									</div>
									
								</div><!-- /.box-body -->
								<div class="box-footer">
									<button type="submit" name="submit" value="Update" class="btn btn-primary"><?php echo $welcome->loadPo('Submit'); ?></button>
									<a href="<?php echo base_url(); ?>subscription" class="btn btn-default"><?php echo $welcome->loadPo('Cancel'); ?></a>
								</div>
							</form>
						</div><!-- /.box -->
					</div><!--/.col (left) -->
				</div>
			</div>
		</section><!-- /.content -->
    </aside><!-- /.right-side -->
<!--/div--><!-- ./wrapper -->
