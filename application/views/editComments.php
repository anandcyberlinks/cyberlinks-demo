<!-- Right side column. Contains the navbar and content of the page -->
<aside class="right-side">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>Update Comment</h1>
		<ol class="breadcrumb">
				<li><a href="<?php echo base_url(); ?>"><i class="fa fa-dashboard"></i><?php echo $welcome->loadPo('Dashboard') ?></a></li>
                                <li><a href="<?php echo base_url(); ?>comments"><i class="glyphicon glyphicon-comment"></i><?php echo $welcome->loadPo('Comment') ?></a></li>
                                <li class="active"><?php echo $welcome->loadPo('Update Comment') ?></li>
			</ol>
	</section>
	
	<section class="content">                   					
		<div class="col-xs-12">
			<div class="box">											
					<div class="box-header">	
						<h3 class="box-title">Update Comment</h3>
					</div>				
					<?php  foreach ($edit as $value){?> 
					<form enctype="multipart/form-data" method="post" action="">
					<div class="box-body">
						<div class="row">
							<div class="form-group col-lg-5">
								<label for="exampleInputEmail1">Comment</label>
								<!--<input name="comment" class="form-control" id="comment" type="text" value="<?php echo $value->comment;?>">-->
								<textarea name="comment" class="form-control" id="comment" rows="5"><?php echo $value->comment;?></textarea>
								<?php echo form_error('comment','<span class="text-danger">','</span>'); ?>
							</div>							
						</div>						
					</div><!-- /.box-body -->
					<div class="box-footer" align="center">
						<button type="submit" name="Submit" value="Update" class="btn btn-primary">Submit</button>
						<a class="btn btn-primary" href="<?php echo base_url();?>comments">Cancel</a>
					</div>
				</form> 
				<?php  } ?> 
			</div>
		</div>
	</section>
</aside><!-- /.right-side -->
</div><!-- ./wrapper -->

<script src="<?php echo base_url();?>assets/js/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js" type="text/javascript"></script>