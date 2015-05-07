<!--div class="wrapper row-offcanvas row-offcanvas-left"-->
	<!-- Right side column. Contains the navbar and content of the page -->
    <aside class="content-wrapper">                
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
                            <!-- form start -->
                                        <form action="<?php echo base_url(); ?>genre/index" id="searchCategoryForm" method="post" accept-charset="utf-8">
                                            <div class="row">
                                                    <!-- left column -->
                                                    <div class="col-md-12">
                                                            <!-- general form elements -->
                                                            <div class="box box-primary collapsed-box">
                                                                    <div class="box-header">
                                    <!-- tools box -->
                                    <div class="pull-right box-tools">
                                        <button class="btn btn-danger btn-sm" data-widget='collapse' data-toggle="tooltip" title="Collapse"><i class="fa fa-plus"></i></button>
                                    </div><!-- /. tools -->
                                    <h3 class="box-title">Search Genre</h3>
                                </div><!-- /.box-header -->

                                                                            <div style="display:none;"><input type="hidden" name="_method" value="POST"/></div>                
                                                                            <div class="box-body" style="display:none;">
                                                                                    <div class="row">
                                                                                            <div class="form-group col-lg-3">
                                                                                                    <div class="input text">
                                                                                                            <label for="searchCategoryName"><?php echo $welcome->loadPo('Genre').' '.$welcome->loadPo('Name'); ?></label>
                                                                                                            <input name="genre_name" class="form-control" placeholder="<?php echo $welcome->loadPo('Genre').' '.$welcome->loadPo('Name');; ?> " type="text" id="searchCategoryName" value="<?php echo (isset($search_data['genre_name']))? $search_data['genre_name']:'';  ?>"/>
                                                                                                    </div>
                                                                                            </div>

                                                                                    </div>
                                                                            </div><!-- /.box-body -->
                                                                            <div class="box-footer" style="display:none;">
                                                                                    <button type="submit" name="submit" value="Search" class="btn btn-primary"><?php echo $welcome->loadPo('Search'); ?></button>
                                                                            </div> 
                                                            </div><!-- /.box -->
                                                    </div><!--/.col (left) -->
                                            </div>
                                        </form>                            

				<div class="row">
					<div class="col-xs-12">
						<div class="box">
							<div class="box-body table-responsive">
								<table id="example1" class="table table-bordered table-striped">
									<thead>
										<tr>
											<th><a href="<?php echo base_url();?>genre/index/genre_name/<?php echo (!empty($show_c))?$show_c:'desc';?>"><?php echo $welcome->loadPo('Genre').' '.$welcome->loadPo('Name'); ?></a></th>
											<th><?php echo $welcome->loadPo('Action'); ?></th>
											
										</tr>
									</thead>
									<tbody>
										<?php 
										if($category)
										{
											foreach($category as $cat) { ?>
											<tr>
												<td><?php echo $cat->genre_name;?></td>
												<td>
													<a href="<?php echo base_url(); ?>genre/addgenre?action=<?php echo base64_encode($cat->id);?>" class="btn btn-info btn-sm"><?php echo $welcome->loadPo('Edit'); ?></a>&nbsp;
													
													<a class="confirm" onclick="return delete_genre(<?php echo $cat->id;?>);" href="" ><button class="btn btn-danger btn-sm" data-toggle="modal" data-target=".bs-example-modal-sm" ><?php echo $welcome->loadPo('Delete'); ?></button></a>
																									
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
<!--/div--><!-- ./wrapper -->

<script>
	function delete_genre(id)
	{
		bootbox.confirm("<?php echo $welcome->loadPo('Are you sure you want to delete').' '.$welcome->loadPo('Genre'); ?>", function(confirmed) 
		{
			if (confirmed) 
			{
				location.href = '<?php echo base_url();?>genre/deleteGenre?id='+id ;
			}
		})
	}
</script> 
