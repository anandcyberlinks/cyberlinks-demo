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
                                <li class="active"><?php echo $welcome->loadPo('Live Streaming') ?></li>
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
			<form action="" method="post" id="registerId" enctype='multipart/form-data'>
			    <div class="box-body">
				<div class="form-group">
                                    <div class="row">
					<div class="col-md-9">
                                            <label for="Name"><?php echo $welcome->loadPo('Youtube Url'); ?></label>
					    <input class="form-control" type="text" value="<?php echo $result->youtube;?>" name="youtube" placeholder="Youtube URL" />
					</div
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
					    <input class="form-control" type="text" value="<?php echo $result->windows;?>" name="windows" placeholder="Web URL" />
					</div>					
				    </div>
                                    <div class="row">
					<div class="col-md-9">
                                            <label for="Name"><?php echo $welcome->loadPo('Web Url'); ?></label>
					    <input class="form-control" type="text" value="<?php echo $result->web;?>" name="web" placeholder="Livestrem URL" />
					</div>					
				    </div>
                                    <div class="row"> 
					<div class="col-md-9">
					<label for="Image"><?php echo $welcome->loadPo('Image'); ?></label>&nbsp;&nbsp;
					<span class="btn btn-default btn-file btn-sm">
					<?php echo  $welcome->loadPo('Choose Media') ?> <input name="chanelImage"  id="chanelImage"  atr="files" type="file"/>
					</span>
					</div>
				    </div>
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