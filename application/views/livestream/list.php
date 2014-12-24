<div class="wrapper row-offcanvas row-offcanvas-left">
	<!-- Right side column. Contains the navbar and content of the page -->
    <aside class="right-side">                
		<!-- Content Header (Page header) -->
		<section class="content-header">
            <h1><?php echo $welcome->loadPo('Live Stream List'); ?><small><?php echo $welcome->loadPo('Control panel'); ?></small> </h1>
            <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>"><i class="fa fa-dashboard"></i><?php echo $welcome->loadPo('Dashboard') ?></a></li>
            <li class="active"><?php echo $welcome->loadPo('Live stream') ?></li>
	     <li class="active"><?php echo $welcome->loadPo('List') ?></li>
        </ol>
        </section>		
		<div>
			<div id="msg_div">
				<?php echo $this->session->flashdata('message');?>				
			</div>	
		</div>
		<?php $search = $this->session->userdata('search_form'); ?>
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
									<a href="<?php echo base_url(); ?>livestream?action=add" class="btn btn-success btn-sm"><?php echo $welcome->loadPo('Add'); ?></a>
								</div>
							</div><!-- /.box-header -->
							<!-- form start -->
							<form action="<?php echo base_url()?>livestream/slist" id="searchCategoryForm" method="post" accept-charset="utf-8">
								<div style="display:none;"><input type="hidden" name="_method" value="POST"/></div>                
								<div class="box-body">
									<div class="row">										
										<div class="form-group col-lg-3">
											<div class="input select">
												<label for="searchParentId"><?php echo $welcome->loadPo('Content').' '.$welcome->loadPo('Provider'); ?></label>
												<select name="searchuser" class="form-control" placeholder="Content provider" id="searchuser">
													<option value="">--<?php echo $welcome->loadPo('Select'); ?>--</option>
													<?php foreach($content_provider as $row){?>
														<option value="<?php echo $row->id;?>" <?php if(isset($search['searchuser'])&& $row->id==$search['searchuser']){ echo 'selected="selected"';}?>><?php echo $row->content_provider;?></option>
													<?php } ?>
												</select>
												
											</div>
										</div>
									</div>
								</div><!-- /.box-body -->
								<div class="box-footer">
									<button type="submit" name="search" value="Search" class="btn btn-primary"><?php echo $welcome->loadPo('Search'); ?></button>
								<button type="submit" name="reset" value="Reset"class="btn btn-primary"><?php echo $welcome->loadPo('Reset') ?></button>
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
											<th><?php echo $welcome->loadPo('Content Provider'); ?></th>
											<th><?php echo $welcome->loadPo('Icon'); ?></th>
											<th width=20%><?php echo $welcome->loadPo('Youtube'); ?></th>
											<th width=20%><?php echo $welcome->loadPo('IOS'); ?></th>
                                                                                        <th width=20%><?php echo $welcome->loadPo('Android'); ?></th>
                                                                                        <!--<th><?php echo $welcome->loadPo('Window'); ?></th>-->
                                                                                        <th><?php echo $welcome->loadPo('Status'); ?></th>
											<th><?php echo $welcome->loadPo('Action'); ?></th>
										</tr>
									</thead>
									<tbody>
										<?php 
										if($result)
										{
											foreach($result as $row) { ?>
											<tr id="categ_<?php echo $row->id;?>">
												<td><?php echo $row->content_provider; ?></td>
												<td><img width=50 height=50 src="<?php echo $row->thumbnail_url; ?>" /></td>
                                                                                                <td><?php echo $row->youtube; ?></td>
                                                                                                <td><?php echo $row->ios; ?></td>
                                                                                                <td><?php echo $row->android; ?></td>
                                                                                                <!--<td><?php echo $row->windows; ?></td>-->
												<td>
													<?php if($row->status == 1){?>
														<a href="<?php echo base_url() ?>livestream/changestatus/?id=<?php echo $row->id; ?>&status=0" title="Click To Inactive"><img src="<?php echo base_url();?>assets/img/test-pass-icon.png" alt="Active" /></a>
													<?php }else{?>
														<a href="<?php echo base_url() ?>livestream/changestatus/?id=<?php echo $row->id; ?>&status=1" title="Click To Active"><img src="<?php echo base_url();?>assets/img/test-fail-icon.png" alt="Inactive" /></a>
													<?php }?>
												</td>
												<td>
													<a href="<?php echo base_url(); ?>livestream/?id=<?php echo base64_encode($row->user_id);?>" class="btn btn-info btn-sm"><?php echo $welcome->loadPo('Edit'); ?></a>&nbsp;
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

