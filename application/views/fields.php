
<div class="wrapper row-offcanvas row-offcanvas-left">
	<!-- Right side column. Contains the navbar and content of the page -->
    <aside class="right-side">                
		<!-- Content Header (Page header) -->
		<section class="content-header">
            <h1><?php echo $welcome->loadPo('Fields'); ?><small><?php echo $welcome->loadPo('Control panel'); ?></small> </h1>
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
					<!-- left column -->
					<div class="col-md-12">
						<!-- general form elements -->
						<div class="box box-primary">
							<div class="box-header">
								<h3 class="box-title"><?php echo $form_name[0]->form_name; ?></h3>
								<div class="box-tools pull-right">
									<a href="<?php echo base_url(); ?>dform/addFields?id=<?php echo $_GET['id']; ?>" class="btn btn-success btn-sm"><?php echo $welcome->loadPo('Fields').' '.$welcome->loadPo('Add'); ?></a>
								</div>
							</div><!-- /.box-header -->
       
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
											<th><a href="<?php echo base_url();?>category/index/category/<?php echo (!empty($show_c))?$show_c:'desc';?>"><?php echo $welcome->loadPo('Field').' '.$welcome->loadPo('Name'); ?></a></th>
											<th><?php echo $welcome->loadPo('Field Title'); ?></th>
											<th><?php echo $welcome->loadPo('Field Type'); ?></th>
											<th><?php echo $welcome->loadPo('Status'); ?></th>
											<th><?php echo $welcome->loadPo('Action'); ?></th>
										</tr>
									</thead>
									<tbody>
										<?php 
										if($field)
										{
											foreach($field as $cat) { ?>
											<tr id="categ_<?php echo $cat->id;?>">
												<td><?php echo $cat->field_name; ?></td>
												<td><?php echo $cat->field_title; ?></td>
												<td><?php echo $cat->field_type; ?></td>
												<td>
													<?php if($cat->status == 1){?>
														<img src="<?php echo base_url();?>assets/img/test-pass-icon.png" alt="Active" />
													<?php }else{?>
														<img src="<?php echo base_url();?>assets/img/test-fail-icon.png" alt="Inactive" />
													<?php }?>
												</td>
												<td>
													<a href="<?php echo base_url(); ?>dform/addFields?action=<?php echo base64_encode($cat->id);?>&id=<?php echo $_GET['id'] ?>" class="btn btn-info btn-sm"><?php echo $welcome->loadPo('Edit'); ?></a>&nbsp;
													<a class="confirm" onclick="return delete_field(<?php echo $cat->id; ?>, '<?php echo base_url() . 'dform/deletefield' ?>', '<?php echo current_full_url(); ?>');" href="" ><button class="btn btn-danger btn-sm" data-toggle="modal" data-target=".bs-example-modal-sm" ><?php echo $welcome->loadPo('Delete'); ?></button></a>
																									
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
</div><!-- ./wrapper -->

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
