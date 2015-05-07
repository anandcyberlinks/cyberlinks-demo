<!--div class="wrapper row-offcanvas row-offcanvas-left"-->
	<!-- Right side column. Contains the navbar and content of the page -->
    <aside class="content-wrapper">                
		<!-- Content Header (Page header) -->
		<section class="content-header">
            <h1><?php echo $welcome->loadPo('Fields'); ?><small><?php echo $welcome->loadPo('Control panel'); ?></small> 
			<a href="<?php echo base_url(); ?>dform/addform" class="btn btn-success btn-sm"><?php echo $welcome->loadPo('Add').' '.$welcome->loadPo('New'); ?></a></h1>
            <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>"><i class="fa fa-dashboard"></i><?php echo $welcome->loadPo('Dashboard') ?></a></li>
            <li class="active"><?php echo $welcome->loadPo('Fields') ?></li>
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
					<div class="col-xs-12">
						<div class="box">
							<div class="box-body table-responsive">
								<table id="example1" class="table table-bordered table-striped">
									<thead>
										<tr>
											<th><?php echo $welcome->loadPo('Form Name'); ?></th>
											<th><?php echo $welcome->loadPo('Status'); ?></th>
											<th><?php echo $welcome->loadPo('Manage Fileds'); ?></th>
											<th><?php echo $welcome->loadPo('Action'); ?></th>
										</tr>
									</thead>
									<tbody>
										<?php 
										if($forms){
											foreach($forms as $cat) { ?>
											<tr id="categ_<?php echo $cat->id;?>">
												<td><?php echo $cat->form_name; ?></td>
												<td>
													<?php if($cat->status == 1){?>
														<img src="<?php echo base_url();?>assets/img/test-pass-icon.png" alt="Active" />
													<?php }else{?>
														<img src="<?php echo base_url();?>assets/img/test-fail-icon.png" alt="Inactive" />
													<?php }?>
												</td>
												<td><a href="<?php echo base_url(); ?>dform/field/?id=<?php echo $cat->id?>" ><?php echo $welcome->loadPo('Manage_Field'); ?></a></td>

												<td>
													<a href="<?php echo base_url(); ?>dform/addform?action=<?php echo base64_encode($cat->id);?>" class="btn btn-info btn-sm"><?php echo $welcome->loadPo('Edit'); ?></a>&nbsp;
													
													<a class="confirm" onclick="return delete_form(<?php echo $cat->id; ?>, '<?php echo base_url() . 'dform/deleteform' ?>', '<?php echo current_full_url(); ?>');" href="" ><button class="btn btn-danger btn-sm" data-toggle="modal" data-target=".bs-example-modal-sm" ><?php echo $welcome->loadPo('Delete'); ?></button></a>
																									
												</td>
											</tr>
										<?php }}else{?>
											<tr >
												<td colspan="10"><?php echo $welcome->loadPo('No Records Found'); ?></td>
											</tr>
										<?php } ?>
									</tbody>
								</table>
							</div><!-- /.box-body -->
						</div><!-- /.box -->
					</div>
				</div>		
			</div>
		</section><!-- /.content -->
    </aside><!-- /.right-side -->
<!--/div--><!-- ./wrapper -->

<script>
	function delete_category(id)
	{
		bootbox.confirm("<?php echo $welcome->loadPo('Are you sure you want to delete').' '.$welcome->loadPo('Category'); ?>", function(confirmed) 
		{
			if (confirmed) 
			{
				location.href = '<?php echo base_url();?>category/deleteCategory?id='+id ;
			}
		})
	}
</script> 
