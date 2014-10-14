<div class="wrapper row-offcanvas row-offcanvas-left">
	<!-- Right side column. Contains the navbar and content of the page -->
    <aside class="right-side">                
		<!-- Content Header (Page header) -->
		<section class="content-header">
            <h1><?php echo $welcome->loadPo('Genre'); ?><small><?php echo $welcome->loadPo('Control panel'); ?></small>
	    <a class="btn btn-success" href="<?php echo base_url()?>genre/addgenre"><i class="fa fa-fw fa-plus-square"></i><?php echo $welcome->loadPo('Add Genre') ?></a>
	    </h1>
            <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>"><i class="fa fa-dashboard"></i><?php echo $welcome->loadPo('Dashboard') ?></a></li>
            <li class="active"><?php echo $welcome->loadPo('Genre') ?></li>
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
								
							</div><!-- /.box-header -->
							<!-- form start -->
							<form action="<?php echo base_url(); ?>genre/searchGenre" id="searchCategoryForm" method="post" accept-charset="utf-8">
								<div style="display:none;"><input type="hidden" name="_method" value="POST"/></div>                
								<div class="box-body">
									<div class="row">
										<div class="form-group col-lg-3">
											<div class="input text">
												<label for="searchCategoryName"><?php echo $welcome->loadPo('Genre').' '.$welcome->loadPo('Name'); ?></label>
												<input name="genre_name" class="form-control" placeholder="<?php echo $welcome->loadPo('Genre').' '.$welcome->loadPo('Name');; ?> " type="text" id="searchCategoryName" value="<?php echo (isset($search_data['genre_name']))? $search_data['genre_name']:'';  ?>"/>
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
											<th><a href="<?php echo base_url();?>genre/index/genre_name/<?php echo (!empty($show_c))?$show_c:'desc';?>"><?php echo $welcome->loadPo('Genre').' '.$welcome->loadPo('Name'); ?></a></th>
											
											
										</tr>
									</thead>
									<tbody>
										<?php 
										if($category)
										{
											foreach($category as $cat) { ?>
											<tr>
												<td><?php echo $cat->genre_name;?></td>
												
												
											</tr>
										<?php }}else{?>
											<tr >
												<td colspan="10"><?php echo $welcome->loadPo('No Records Found'); ?></td>
											</tr>
										<?php } ?>
									</tbody>
								</table>
								<!-- Pagination start --->
								<?php  if ($this->pagination->total_rows == '0') {
                                        echo "<tr><td colspan=\"7\"><h4>".$welcome->loadPo('No Record Found')."</td></tr></h4>"; }else { ?>
						</table>
							
								 	<div class="row pull-left">
										<div class="dataTables_info" id="example2_info"><br>
											<?php
												$param = $this->pagination->cur_page * $this->pagination->per_page;
												if ($param > $this->pagination->total_rows) {
													$param = $this->pagination->total_rows;
												}
												if ($this->pagination->cur_page == '0') {
													$param = $this->pagination->total_rows;
												}
												$off = $this->pagination->cur_page;
												if ($this->pagination->cur_page > '1') {
													$off = (($this->pagination->cur_page * '10') - 9);
												}
												echo "&nbsp;&nbsp;Showing <b>" . $off . "-" . $param . "</b> of <b>" . $this->pagination->total_rows . "</b> total results";
											}
											?>
										</div>
									</div>	
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
