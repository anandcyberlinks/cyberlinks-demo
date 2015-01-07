<?php $uri = $this->uri->segment(1); ?>
<div class="wrapper row-offcanvas row-offcanvas-left">
	<!-- Right side column. Contains the navbar and content of the page -->
    <aside class="right-side">                
		<!-- Content Header (Page header) -->
		<section class="content-header">
            <h1><?php echo $welcome->loadPo('Channel Category'); ?><small><?php echo $welcome->loadPo('Control panel'); ?></small> </h1>
            <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>"><i class="fa fa-dashboard"></i><?php echo $welcome->loadPo('Dashboard') ?></a></li>
            <li class="active"><?php echo $welcome->loadPo($this->uri->segment(1)) ?></li>
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
									<a href="<?php echo base_url().$uri; ?>/addCategory" class="btn btn-success btn-sm"><?php echo $welcome->loadPo('Category').' '.$welcome->loadPo('Add'); ?></a>
								</div>
							</div><!-- /.box-header -->
							<!-- form start -->
							<form action="<?php echo base_url().$uri; ?>/index" id="searchCategoryForm" method="post" accept-charset="utf-8">
								<div style="display:none;"><input type="hidden" name="_method" value="POST"/></div>                
								<div class="box-body">
									<div class="row">
										<div class="form-group col-lg-3">
											<div class="input text">
												<label for="searchCategoryName"><?php echo $welcome->loadPo('Category').' '.$welcome->loadPo('Name'); ?></label>
												<input name="category_name" class="form-control" placeholder="<?php echo $welcome->loadPo('Category').' '.$welcome->loadPo('Name');; ?> " type="text" id="searchCategoryName" value="<?php echo (isset($search_data['category_name']))? $search_data['category_name']:'';  ?>"/>
											</div>
										</div>
										<div class="form-group col-lg-3">
											<div class="input select">
												<label for="searchParentId"><?php echo $welcome->loadPo('Parent').' '.$welcome->loadPo('Category'); ?></label>
												<select name="parent_id" class="form-control" placeholder="Parent Category" id="searchParentId">
													<option value="">--<?php echo $welcome->loadPo('Select'); ?>--</option>
													<?php foreach($allParentCategory as $cat){?>
														<option value="<?php echo $cat->id;?>" <?php if(isset($search_data['parent_id'])&& $cat->id==$search_data['parent_id']){ echo 'selected="selected"';}?>><?php echo $cat->category;?></option>
													<?php } ?>
												</select>
												
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
                                                            <?php if($uri=='ch_category') { 
                                                                    $classID = 'table-draggable2';
                                                            }  else {
                                                                    $classID = 'example1';
                                                            }
                                                            ?>
<!--								<table id="example1" class="table table-bordered table-striped">-->
<table  id="<?php echo $classID; ?>" class="table table-bordered table-striped">
									<thead>
										<tr>
                                                                                        <?php if($uri=='ch_category') { ?>
                                                                                            <th><?php echo $welcome->loadPo('Index') ?></th>
                                                                                        <?php } ?>
											<th><a href="<?php echo base_url().$uri;?>/index/category/<?php echo (!empty($show_c))?$show_c:'desc';?>"><?php echo $welcome->loadPo('Category').' '.$welcome->loadPo('Name'); ?></a></th>
											<th><?php echo $welcome->loadPo('Description'); ?></th>
											<th><?php echo $welcome->loadPo('Range'); ?></th>
											<th><a href="<?php echo base_url().$uri;?>/index/parent/<?php echo (!empty($show_p))?$show_p:'desc';?>"><?php echo $welcome->loadPo('Parent'); ?></a></th>
											<th><a href="<?php echo base_url().$uri;?>/index/status/<?php echo (!empty($show_s))?$show_s:'desc';?>"><?php echo $welcome->loadPo('Status'); ?></a></th>
											<th><?php echo $welcome->loadPo('Action'); ?></th>
										</tr>
									</thead>
									<tbody>
										<?php 
										if($category)
										{
											$i=1;
                                                                                        foreach($category as $cat) { ?>
											<tr id="categ_<?php echo $cat->id;?>">
                                                                                                <?php if($uri=='ch_category') { ?>
                                                                                                    <td  id="<?php echo $cat->id; ?>" class="index"><?php echo $cat->index > 0 ? $cat->index : $i++ ; ?></td>
                                                                                                <?php } ?>
												<td><?php echo $cat->category; ?></td>
												<td><?php echo $cat->description; ?></td>
												<td><?php echo $cat->range_from.' - '.$cat->range_to; ?></td>
												<td><?php if($cat->parent){ echo $cat->parent; }else{ echo '--';}  ?></td>
												<td>
													<?php if($cat->status == 1){?>
														<img src="<?php echo base_url();?>assets/img/test-pass-icon.png" alt="Active" />
													<?php }else{?>
														<img src="<?php echo base_url();?>assets/img/test-fail-icon.png" alt="Inactive" />
													<?php }?>
												</td>
												<td>
													<a href="<?php echo base_url().$uri; ?>/addCategory?action=<?php echo base64_encode($cat->id);?>" class="btn btn-info btn-sm"><?php echo $welcome->loadPo('Edit'); ?></a>&nbsp;
													
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
								<!-- Pagination start --->
                                                                <?php if($uri=='category') { ?>
								<div class="row pull-right">
									<div class="col-xs-12">
										<div class="dataTables_paginate paging_bootstrap">
											<ul class="pagination"><li><?php echo $links ?></li></ul> 
										</div>
									</div>
								</div>
                                                                <?php } ?>
								<!-- Pagination end -->
							</div><!-- /.box-body -->
						</div><!-- /.box -->
					</div>
				</div>		
			</div>
		</section><!-- /.content -->
    </aside><!-- /.right-side -->
</div><!-- ./wrapper -->

<script type="text/javascript">
        $(function(){
            $('.link').click(function(){
                var elem = $(this);
                $.ajax({
                    type: "GET",
                    url: elem.attr('links'),
                    dataType:"json",  
                    success: function(data) {
                        if(data.success){
                               elem.closest("tr").fadeOut('3000');
                               $('#msg_div').html(data.message);
                        }
                    }
                });
                return false;
            });
    
            updateIndex = function(e, ui) {
                var updateString = '';
                $('td.index', ui.item.parent()).each(function (i) {
                    $(this).html(i + 1);
                    updateString += '&' + $(this).attr('id') + '=' + (i + 1);
                });
                
                $.ajax({
                    type: "POST",
                    url: "<?=base_url().'ch_category/updateindex'?>",
                    data: updateString,
                    success: function(data) {
                        
                    }
                }); 
                
            };
            
            $("#table-draggable2 tbody" ).sortable({
                stop: updateIndex
            }).disableSelection();
            
        });
</script>

<script>
	function delete_category(id)
	{
		bootbox.confirm("<?php echo $welcome->loadPo('Are you sure you want to delete').' '.$welcome->loadPo('Category'); ?>", function(confirmed) 
		{
			if (confirmed) 
			{
				location.href = '<?php echo base_url().$uri;?>/deleteCategory?id='+id ;
			}
		})
	}
</script> 
