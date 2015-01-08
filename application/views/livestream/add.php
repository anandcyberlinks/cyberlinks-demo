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
			<h1>Youtube Channels<small>Control panel</small></h1>
			<ol class="breadcrumb">
				<li><a href="<?php echo base_url(); ?>"><i class="fa fa-dashboard"></i><?php echo $welcome->loadPo('Dashboard') ?></a></li>                                
                               
			        <li><a href="<?php echo base_url()?>webtv"><?php echo $welcome->loadPo('Web Tv') ?></a></li>
				
				 <li class="active"><?php echo $channel->name; ?></li>
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
			   
			</div><!-- /.box-header -->
			<!-- form start -->
			<form action="" method="post" id="frmstream" enctype='multipart/form-data'>
			    <div class="box-body">
				<div class="form-group">
                                    <div class="row">
					<div class="col-md-9">
                                            <label for="Name"><?php echo $welcome->loadPo('Youtube Url'); ?></label>
					    <input class="form-control" type="text" value="<?=isset($result->youtube) ? $result->youtube : ''?>" name="youtube" placeholder="Youtube URL" />
					</div>
				    </div>
				    <div class="row">
					<div class="col-md-9">
                                            <label for="Name"><?php echo $welcome->loadPo('IOS Url'); ?></label>
					    <input class="form-control" type="text" value="<?=isset($result->ios) ? $result->ios : ''?>" name="ios" placeholder="IOS URL" />
					</div>
				    </div>
                                    <div class="row">
					<div class="col-md-9">
                                            <label for="Name"><?php echo $welcome->loadPo('Android Url'); ?></label>
					    <input class="form-control" type="text" value="<?=isset($result->android) ? $result->android : ''?>" name="android" placeholder="Android URL" />
					</div>					
				    </div>
                                    <div class="row">
					<div class="col-md-9">
                                            <label for="Name"><?php echo $welcome->loadPo('Windows Url'); ?></label>
					    <input class="form-control" type="text" value="<?=isset($result->windows) ? $result->windows : ''?>" name="windows" placeholder="Window URL" />
					</div>					
				    </div>
                                    <div class="row">
					<div class="col-md-9">
                                            <label for="Name"><?php echo $welcome->loadPo('Web Url'); ?></label>
					    <input class="form-control" type="text" value="<?=isset($result->web) ? $result->web : ''?>" name="web" placeholder="Web URL" />
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
					    <span>
                                                <?php 
                                                if(isset($result->thumbnail_url) && $result->thumbnail_url != ''){
                                                    echo sprintf('<img width=100 height=100 src="%s" />',$result->thumbnail_url);
                                                }
                                                ?>
                                            </span>
					</div>
				    </div>
				    <br/>
                                    <div class="row">
                                        <div class="col-md-6">		
                                            
					    <input name="save" type="submit" value="Save" class="btn btn-success">
                                            <a class="btn btn-warning" href="<?=base_url() ?>webtv"><i class="fa fa-mail-reply"></i>&nbsp;Back</a>
					</div>
                                    </div>
				</div>
			    </div><!-- /.box-body -->
			</form>
		    </div>
		    
		    <?php if(isset($result->web) && $result->web!=''){ ?>
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