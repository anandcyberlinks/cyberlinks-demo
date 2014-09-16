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
								<li class="pull-right">&nbsp;</li>
							</ul>
							<div class="tab-content">
								<div class="tab-pane active" id="tab_2G">
									<div class="box box-solid">
										<div class="box-body no-padding" id="player_2G">
										
										</div><!-- /.box-body -->
										<div class="box-footer">
											<a class="btn btn-primary btn-sm" href="<?php echo base_url(); ?>video"><?php echo $welcome->loadPo('Back'); ?></a>
										</div>
									</div><!-- /.box -->
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
        file: "http://54.255.176.172:1935/live/smil:mystream.smil/playlist.m3u8",
        height: 400,
        width: 600
    });
</script>