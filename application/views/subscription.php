	<?php $sort = $sort_by = $this->uri->segment(4);
	if($sort == 'DESC'){
		$sort = 'ASC';
		$icon = "<i class=\"fa fa-fw fa-sort-numeric-desc\"></i>";
	}else{
		$sort = 'DESC';
		$icon = "<i class=\"fa fa-fw fa-sort-numeric-asc\"></i>";
	}
	?>
	<div class="wrapper row-offcanvas row-offcanvas-left">
	<!-- Right side column. Contains the navbar and content of the page -->
    <aside class="right-side">                
		<!-- Content Header (Page header) -->
		<section class="content-header">
            <h1><?php echo $welcome->loadPo('Subscription Duration'); ?><small><?php echo $welcome->loadPo('Control panel'); ?></small> </h1>
            <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>"><i class="fa fa-dashboard"></i><?php echo $welcome->loadPo('Dashboard') ?></a></li>
            <li class="active"><?php echo $welcome->loadPo('Subscription Duration') ?></li>
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
									<a href="<?php echo base_url(); ?>subscription/addSubscription" class="btn btn-success btn-sm"><?php echo $welcome->loadPo('Subscription').' '.$welcome->loadPo('Add'); ?></a>
								</div>
							</div><!-- /.box-header -->
							<!-- form start -->
							<form action="" method="post" accept-charset="utf-8">
								<div style="display:none;"><input type="hidden" name="_method" value="POST"/></div>                
								<div class="box-body">
									<div class="row">
										<div class="form-group col-lg-3">
											<div class="input text">
												<label for="searchSubsriptionName"><?php echo $welcome->loadPo('Subscription Duration'); ?></label>
												<input name="title" class="form-control" placeholder="<?php echo $welcome->loadPo('Subscription Duration'); ?> " type="text" value="<?php echo (isset($search_data['title']))? $search_data['title']:'';  ?>"/>
											</div>
										</div>
									</div>
								</div><!-- /.box-body -->
								<div class="box-footer">
									<button type="submit" name="submit" value="Search" class="btn btn-primary"><?php echo $welcome->loadPo('Search'); ?></button>
									<button type="submit" name="reset" value="Reset" class="btn btn-default"><?php echo $welcome->loadPo('Reset'); ?></button>

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
											<th><?php echo $welcome->loadPo('Duration Name'); ?></th>
											<th><?php echo $welcome->loadPo('Status'); ?></th>
											<th><a href="<?php echo base_url(); ?>subscription/index/days/<?php echo $sort; ?>"><?php echo $icon; ?><?php echo $welcome->loadPo('days'); ?></a></th>
											<th><?php echo $welcome->loadPo('Action'); ?></th>
										</tr>
									</thead>
									<tbody>
										<?php 
										if($result)
										{
											foreach($result as $subData) { ?>
											<tr id="categ_<?php echo $subData->id;?>">
												<td><?php echo $subData->name; ?></td>
												<td>
													<?php if($subData->status == '1'){?>
														<a href="<?php echo base_url(); ?>subscription/changeStatus/<?php echo $subData->id; ?>/0"><img src="/mobiletv/assets/img/test-pass-icon.png" alt="Active" /></a>
													<?php }else{?>
														<a href="<?php echo base_url(); ?>subscription/changeStatus/<?php echo $subData->id; ?>/1"><img src="/mobiletv/assets/img/test-fail-icon.png" alt="Inactive" /></a>
													<?php }?>
												</td>
												<td><?php echo $subData->days; ?></td>
												<td>
													<a href="<?php echo base_url(); ?>subscription/addSubscription?action=<?php echo base64_encode($subData->id);?>" class="btn btn-info btn-sm"><?php echo $welcome->loadPo('Edit'); ?></a>&nbsp;
													<a class="confirm_delete_subs" href="<?php echo base_url();?>subscription/deleteSubscription?action=<?php echo base64_encode($subData->id);?>" ><button class="btn btn-danger btn-sm" data-toggle="modal" data-target=".bs-example-modal-sm" ><?php echo $welcome->loadPo('Delete'); ?></button></a>
												</td>
											</tr>
										<?php }}else{?>
											<tr >
												<td colspan="10"><?php echo $welcome->loadPo('No Records Found'); ?></td>
											</tr>
										<?php } ?>
									</tbody>
								</table>
								<!-- Pagination end -->
							</div><!-- /.box-body -->
						</div><!-- /.box -->
					</div>
				</div>		
			</div>
		</section><!-- /.content -->
    </aside><!-- /.right-side -->
</div><!-- ./wrapper -->
