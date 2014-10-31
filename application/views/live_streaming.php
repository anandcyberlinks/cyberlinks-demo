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
			<h1>Video<small>Control panel</small></h1>
			<ol class="breadcrumb">
				<li><a href="<?php echo base_url(); ?>"><i class="fa fa-dashboard"></i><?php echo $welcome->loadPo('Dashboard') ?></a></li>
                                <li><a href="<?php echo base_url(); ?>video"><i class="fa fa-play-circle"></i><?php echo $welcome->loadPo('Video') ?></a></li>
                                <li class="active"><?php echo $welcome->loadPo('Live Streaming') ?></li>
			</ol>
		</section>
		<div>
                    <div id="msg_div">	
			<?php if(isset($msg)) { echo $msg;  } ?> 
			<?php echo $this->session->flashdata('message');?>
                    </div>	
		</div>
		<!-- Main content -->
		<section class="content">
			<div id="content">
				<div class="row">
					<div class="col-md-12">
						<!-- Custom Tabs -->
						<div class="nav-tabs-custom">
							<ul class="nav nav-tabs">
								<li class="active"><a href="javascript:void(0)" ><?php echo $welcome->loadPo('Live Stream'); ?></a></li>
							</ul>
							<div class="tab-content">
                                                            <div class="row">
                                                                <div class="col-lg-12">
                                                                    <form action="" method="post" id="registerId">
                                                                        <div class="col-lg-9">
                                                                            <input class="form-control" type="text" value="<?php if(count($url)!='0'){ echo $url[0]->value; } ?>" name="url" placeholder="Livestrem URL" />
                                                                        </div>
                                                                        <div class="col-lg-3">
                                                                            <?php if(count($url)!='0'){ ?>
                                                                            <a onclick="return delete_url('<?php echo base_url().'video/deleteLive'; ?>');" href="" ><button class="btn btn-danger btn-sm" data-toggle="modal" data-target=".bs-example-modal-sm" >Delete</button></a>
                                                                            <?php } ?>
                                                                            <input name="save" type="submit" value="Save" class="btn btn-success btn-sm">
                                                                        </div>
                                                                    </form>
                                                                    <div class="clearfix"><br></div>
                                                                <?php if(count($url)!='0'){ ?>
                                                                <div class="col-lg-12">
                                                                    <div class="tab-pane active" id="tab_2G">
									<div class="box box-solid">
										<div class="box-body no-padding" id="player_2G">
										
										</div><!-- /.box-body -->
									</div><!-- /.box -->
                                                                    </div>
								</div>
                                                                </div>
                                                            </div>
							</div><!-- /.tab-content -->
						</div><!-- nav-tabs-custom -->
					</div><!-- /.col -->
				</div> <!-- /.row -->
			</div>
		</section><!-- /.content -->
    </aside><!-- /.right-side -->
</div><!-- ./wrapper -->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jwplayer.js" ></script>
<script type="text/javascript">jwplayer.key = "BC9ahgShNRQbE4HRU9gujKmpZItJYh5j/+ltVg==";</script>
<script>
    jwplayer("player_2G").setup({
        file: "<?php echo $url[0]->value;?>",
        height: 400,
        width: 750
    });
</script>
                                                                <?php }  else {
      

     echo 'No Livestream URL Please Add';
                                                                } ?>

