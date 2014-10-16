
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
								<h3 class="box-title"><?php echo $welcome->loadPo('Search'); ?></h3>
								<div class="box-tools pull-right">
									<a href="<?php echo base_url(); ?>dform/addform" class="btn btn-success btn-sm"><?php echo $welcome->loadPo('Add').' '.$welcome->loadPo('New'); ?></a>
								</div>
							</div><!-- /.box-header -->
							<!-- form start -->
							<form action="<?php echo base_url(); ?>category/index" id="searchCategoryForm" method="post" accept-charset="utf-8">
								<div style="display:none;"><input type="hidden" name="_method" value="POST"/></div>                
								<div class="box-body">
									<div class="row">
										<div class="form-group col-lg-3">
											<div class="input text">
												<label for="searchCategoryName"><?php echo $welcome->loadPo('Fields').' '.$welcome->loadPo('Name'); ?></label>
												<input name="category_name" class="form-control" placeholder="<?php echo $welcome->loadPo('Fields').' '.$welcome->loadPo('Name');; ?> " type="text" id="searchCategoryName" value="<?php echo (isset($search_data['category_name']))? $search_data['category_name']:'';  ?>"/>
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
													
													<a class="confirm" onclick="return delete_category(<?php echo $cat->id;?>);" href="" ><button class="btn btn-danger btn-sm" data-toggle="modal" data-target=".bs-example-modal-sm" ><?php echo $welcome->loadPo('Delete'); ?></button></a>
																									
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
