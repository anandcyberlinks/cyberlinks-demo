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
            <h1><?php echo $welcome->loadPo('Packages'); ?><small><?php echo $welcome->loadPo('Control panel'); ?></small> <a href="<?php echo base_url() ?>package/creatpackage" class="btn btn-success btn-sm">Add New Package</a></h1>
			
            <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>"><i class="fa fa-dashboard"></i><?php echo $welcome->loadPo('Dashboard') ?></a></li>
            <li class="active"><?php echo $welcome->loadPo('Package') ?></li>
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
							</div>
				<div class="row">
					<div class="col-xs-12">
						<div class="box">
							<div class="box-body table-responsive">
								<table id="example1" class="table table-bordered table-striped">
									<thead>
										<tr>
											<th><?php echo $welcome->loadPo('Package Name'); ?></th>
											<th><?php echo $welcome->loadPo('Status'); ?></th>
											<th><?php echo $welcome->loadPo('Type'); ?></th>
											<th><?php echo $welcome->loadPo('Manage Price'); ?></th>
											<th><?php echo $welcome->loadPo('Manage Video'); ?></th>
											<th><?php echo $welcome->loadPo('Action'); ?></th>
										</tr>
									</thead>
									<tbody>
										<?php 
										if($result){
											foreach($result as $subData) { ?>
											<tr id="categ_<?php echo $subData->id;?>">
												<td><?php echo $subData->name; ?></td>
												<td>
													<?php if($subData->status == '1'){?>
														<a href="<?php echo base_url(); ?>package/changeStatus/<?php echo $subData->id; ?>/0"><img src="/mobiletv/assets/img/test-pass-icon.png" alt="Active" /></a>
													<?php }else{?>
														<a href="<?php echo base_url(); ?>package/changeStatus/<?php echo $subData->id; ?>/1"><img src="/mobiletv/assets/img/test-fail-icon.png" alt="Inactive" /></a>
													<?php }?>
												</td>
												<td><?php echo ucfirst($subData->package_type); ?></td>

												<td><a href="#" link="<?php echo base_url()?>package/price/<?php echo $subData->id; ?>?type=package" class="price">Manage Price</a></td>
												<td>
													<a href="#" class="manaegvideo" link="<?php echo base_url() ?>package/video_detail/<?php echo $subData->id; ?>">Manage Videos, </a>Total <?php echo $welcome->countVideo($subData->id); ?>
												</td>
												<td><a class="btn btn-danger btn-sm" onclick="delete_pack(<?php echo $subData->id;?>)"><?php echo $welcome->loadPo('Delete'); ?></a></td>
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
