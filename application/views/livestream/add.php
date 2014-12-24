<style>
    .error{
        color: red;
    }
</style>
<div class="wrapper row-offcanvas row-offcanvas-left">
	<!-- Right side column. Contains the navbar and content of the page -->
    <aside class="right-side">                
		<!-- Content Header (Page header) -->
		<section class="content-header">
			<h1>Live Stream<small>Control panel</small></h1>
			<ol class="breadcrumb">
				<li><a href="<?php echo base_url(); ?>"><i class="fa fa-dashboard"></i><?php echo $welcome->loadPo('Dashboard') ?></a></li>                                
                               <?php if($this->role_id ==2 ){?>
			        <li><a href="<?php echo base_url()?>livestream/slist"><?php echo $welcome->loadPo('Live Streaming') ?></a></li>
				<?php }?>
				 <li class="active"><?php echo $welcome->loadPo('Add/Edit') ?></li>
			</ol>
		</section>
		<div>
                    <div id="msg_div">	
			<?php if(isset($msg)) { echo $msg;  } ?> 
			<?php echo $this->session->flashdata('message');?>
                    </div>	
		</div>
		<section class="content">
		    <div class="box box-primary">
			<div class="box-header">
			    <h3 class="box-title">Live Stream</h3>
			</div><!-- /.box-header -->
			<!-- form start -->
			<form action="" method="post" id="frmstream" enctype='multipart/form-data'>
			    <div class="box-body">
				<div class="form-group">
				    <div class="row">
					<div class="col-md-9">
                                            <label for="Name"><?php echo $welcome->loadPo('Content provider'); ?></label>
					    <?php if($this->role_id ==2 && $_GET['action']=='add'){?>
						    <select name="userid" class="form-control" placeholder="User" id="userid" title="please select content provider" required>
						    <option value="">--<?php echo $welcome->loadPo('Select'); ?>--</option>
						    <?php foreach($content_provider as $row){?>
						    <option value="<?php echo $row->id;?>"><?php echo $row->content_provider;?></option>
						    <?php } ?>
						</select>
					    <?php }else{?>
						<input class="form-control" type="text" disabled=disabled value="<?php echo $result->content_provider;?>" placeholder="Youtube URL" />
					    <?php }?>
					</div>
				    </div>
                                    <div class="row">
					<div class="col-md-9">
                                            <label for="Name"><?php echo $welcome->loadPo('Youtube Url'); ?></label>
					    <input class="form-control" type="text" value="<?php echo $result->youtube;?>" name="youtube" placeholder="Youtube URL" />
					</div>
				    </div>
				    <div class="row">
					<div class="col-md-9">
                                            <label for="Name"><?php echo $welcome->loadPo('IOS Url'); ?></label>
					    <input class="form-control" type="text" value="<?php echo $result->ios;?>" name="ios" placeholder="IOS URL" />
					</div>
				    </div>
                                    <div class="row">
					<div class="col-md-9">
                                            <label for="Name"><?php echo $welcome->loadPo('Android Url'); ?></label>
					    <input class="form-control" type="text" value="<?php echo $result->android;?>" name="android" placeholder="Android URL" />
					</div>					
				    </div>
                                    <div class="row">
					<div class="col-md-9">
                                            <label for="Name"><?php echo $welcome->loadPo('Windows Url'); ?></label>
					    <input class="form-control" type="text" value="<?php echo $result->windows;?>" name="windows" placeholder="Window URL" />
					</div>					
				    </div>
                                    <div class="row">
					<div class="col-md-9">
                                            <label for="Name"><?php echo $welcome->loadPo('Web Url'); ?></label>
					    <input class="form-control" type="text" value="<?php echo $result->web;?>" name="web" placeholder="Web URL" />
					</div>					
				    </div>
				    <br/>
                                    <div class="row"> 
					<div class="col-md-9">
					    <label for="Image"><?php echo $welcome->loadPo('Image'); ?></label>
					    <span class="btn btn-default btn-file btn-sm">
						<?php echo  $welcome->loadPo('Choose Media') ?>
						<input name="chanelImage"  id="chanelImage" atr="files" type="file" />
					    </span>
					    <span><img width=100 height=100 src="<?php echo $result->thumbnail_url;?>" /></span>
					</div>
				    </div>
				    <br/>
                                    <div class="row">
                                        <div class="col-md-6">					   
					    <input name="save" type="submit" value="Save" class="btn btn-success btn-sm">
					</div>
                                    </div>
				</div>
			    </div><!-- /.box-body -->
			</form>
		    </div>
		    
		    <?php if($result->web){ ?>
		    <div class="box box-success">
			<div class="box-body">
			    <iframe width="100%" height="400px" src='<?=base_url().'livestream/rendervideo/?path='.base64_encode($result->web) ?>' allowfullscreen="" frameborder="0"></iframe>
			</div><!-- /.box-body -->
		    </div>
		    <?php } ?>
		</section>
    </aside><!-- /.right-side -->
</div><!-- ./wrapper -->

<script>
  $("#frmstream").validate();
	
</script>