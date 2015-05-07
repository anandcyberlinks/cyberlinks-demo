<!--div class="wrapper row-offcanvas row-offcanvas-left"-->           
	<!-- Right side column. Contains the navbar and content of the page -->
	<aside class="content-wrapper">                
		<!-- Content Header (Page header) -->
		<section class="content-header">
			<h1><?php echo  $welcome->loadPo('Comment Section') ?></h1>
			 <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>"><i class="fa fa-dashboard"></i><?php echo $welcome->loadPo('Dashboard') ?></a></li>
            <li class="active"><?php echo $welcome->loadPo('Comment') ?></li>
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
							<!-- form start -->
							<form role="form" action="<?php echo base_url(); ?>comments/index" method="POST">
						<div class="box-body">
							<div class="row">
								<div class="form-group col-lg-4">
									<label for=""><?php echo  $welcome->loadPo('Comment') ?></label>
									<input type="text" name="comment" class="form-control" value="<?php echo (isset($search_data['comment']))? $search_data['comment']:'';  ?>"   placeholder="<?php echo  $welcome->loadPo('Enter Comment') ?>">								
								</div>	
								<div class="form-group col-lg-4">
									<label for="url"><?php echo  $welcome->loadPo('Content Title') ?></label>
									<input type="text" class="form-control"  name="content_title" value="<?php echo (isset($search_data['content_title']))? $search_data['content_title']:'';  ?>" placeholder="<?php echo  $welcome->loadPo('Enter Content Title') ?>">
								</div>
							</div>
							<div class="row">
								<div class="form-group col-lg-4">
									<label for="url"><?php echo  $welcome->loadPo('User Name') ?></label>
									<input type="text" class="form-control"  name="user" value="<?php echo (isset($search_data['user']))? $search_data['user']:'';  ?>" placeholder="<?php echo  $welcome->loadPo('Enter User Name') ?>">
								</div>
								<div class="form-group col-lg-4">
									<label for="url"><?php echo  $welcome->loadPo('IP') ?></label>
									<input type="text" class="form-control"  name="user_ip" value="<?php echo (isset($search_data['user_ip']))? $search_data['user_ip']:'';  ?>" placeholder="<?php echo  $welcome->loadPo('Enter User IP') ?>">
								</div>												
							</div>
						</div><!-- /.box-body -->
						<div class="box-footer">
							<button type="submit" class="btn btn-primary" name="search" value="Search">
								Search
							</button>
						</div>
					</form>    
							
						</div><!-- /.box -->
					</div><!--/.col (left) -->
				</div>
				<div class="row">
					<div class="col-xs-12">
						<div class="box">
							<div class="box-body table-responsive">
								<table id="example1" class="table table-bordered table-striped ">
						<thead>
							<tr>
								<th width="16%"><?php echo  $welcome->loadPo('Comment') ?></th>
								<th width="15%"><?php echo  $welcome->loadPo('Content Title') ?></th>
								<th width="5%"><?php echo  $welcome->loadPo('Category') ?></th>
								<th width="5%"><?php echo  $welcome->loadPo('User') ?></th>
								<th width="10%"><?php echo  $welcome->loadPo('Created Date') ?></th>
								<th width="10%"><?php echo  $welcome->loadPo('Updated Date') ?></th>
								<th width="10%"><?php echo  $welcome->loadPo('IP') ?></th>
								<th width="5%"><?php echo  $welcome->loadPo('Approved') ?></th>
								<th width="5%"><?php echo  $welcome->loadPo('Status') ?></th>
								<th width="15%"><?php echo  $welcome->loadPo('Action') ?></th>
							</tr>
						</thead>
						<tbody>										
							<?php foreach($comments as $value){ ?>
							<tr>
								<td><?php echo $value->comment ;?></td>
								<td><?php echo $value->title ;?></td>
								<td><?php echo $value->category ;?></td>
								<td><?php echo $value->username; ?></td>
								<td><?php echo date('M d,Y', strtotime($value->created_date)) ?></td>
								<td><?php echo date('M d,Y', strtotime($value->updated_date))?></td>
								<td><?php echo $value->user_ip; ?></td>
								<td>
									<span id='approve_<?php echo $value->id;?>' <?php if($value->approved!="YES"){echo "style='display:none;'";} ?>>
									<button class="btn btn-success btn-sm" onClick='return comment_approved_status(<?php echo $value->id;?>,"<?php echo base_url();?>comments/setApprovedStatus","NO");'><i class="fa fa-fw fa-check"></i></button>
									</span>
									
									<span id='block_<?php echo $value->id;?>' <?php if($value->approved!="NO"){echo "style='display:none;'"; }?>>
									<button class="btn btn-danger btn-sm" onClick='return comment_approved_status(<?php echo $value->id;?>,"<?php echo base_url();?>comments/setApprovedStatus","YES");'><i class="fa fa-fw fa-times"></i></button>
									</span>
								</td>
								<td>
									<span id='active_<?php echo $value->id;?>' <?php if($value->status!="active"){echo "style='display:none;'";} ?>>
									<button class="btn btn-success btn-sm" onClick='return comment_status(<?php echo $value->id;?>,"<?php echo base_url();?>comments/setStatus","inactive");'>&nbsp;&nbsp;Active&nbsp;&nbsp;</button>
									</span>
									
									<span id='dactive_<?php echo $value->id;?>' <?php if($value->status!="inactive"){echo "style='display:none;'"; }?>>
									<button class="btn btn-info btn-sm" onClick='return comment_status(<?php echo $value->id;?>,"<?php echo base_url();?>comments/setStatus","active");'>Inactive</button>
									</span>
								</td >
								<td>																					
									<a name="edit" id="<?php echo $value->id;?>" href="<?php echo base_url();?>comments/editComment/<?php echo $value->id;?>" title="Edit Flavor" data-toggle="modal">
										<button class="btn btn-info btn-sm">&nbsp;&nbsp;&nbsp;<?php echo  $welcome->loadPo('Edit') ?>&nbsp;&nbsp;</button>
									</a>									
									<a class="confirm" onclick="return delete_comment(<?php echo $value->id;?>,'<?php echo base_url().'comments/deleteComment' ?>');" href="" ><button class="btn btn-danger btn-sm" data-toggle="modal" data-target=".bs-example-modal-sm" ><?php echo  $welcome->loadPo('Delete') ?></button></a>
									
								</td>
							</tr>                                           
							
							<?php
							}
							?>
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
												<ul class="pagination"><li><?php  echo  $welcome->loadPo($links); ?></li></ul> 
											</div>
										</div>
									</div>
							</div>		
								<!-- Pagination end -->
							 </div><!-- /.box-body -->
						</div><!-- /.box -->
					</div>
				</div>
			
		</section><!-- /.content -->
    </aside><!-- /.right-side -->
<!--/div--><!-- ./wrapper -->

