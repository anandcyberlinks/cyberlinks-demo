<aside class="right-side">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> 
            <?php echo $welcome->loadPo('Dashboard'); ?>
            <small><?php echo $welcome->loadPo('Control panel'); ?></small>
        </h1>
        <ol class="breadcrumb">
            <i class="fa fa-dashboard"></i>
            <li class="active"><?php echo $welcome->loadPo('Dashboard') ?></li>
        </ol>
    </section>
	<div>
			<div id="msg_div">
				<?php echo $this->session->flashdata('message');?>
			</div>	
		</div>

    <!-- Main content -->
    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3>
                            <?php if(isset($totalvideos)) { echo $totalvideos; } else { echo '0'; } ?>
                        </h3>
                        <p>
                           <?php echo $welcome->loadPo('Total Videos'); ?> 
                        </p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-bag"></i>
                    </div>
                    <a href="<?php echo base_url() ?>video" class="small-box-footer">
                        <?php echo $welcome->loadPo('More info'); ?> <i class="fa fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div><!-- ./col -->
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3>
                           <?php if(isset($transcodedvideos)) { echo $transcodedvideos; } else { echo '0'; } ?>
                        </h3>
                        <p>
                            <?php echo $welcome->loadPo('Total Transcode Jobs'); ?>
                        </p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-stats-bars"></i>
                    </div>
                    <a href="<?php echo base_url() ?>video/video_status" class="small-box-footer">
                        <?php echo $welcome->loadPo('More info'); ?> <i class="fa fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div><!-- ./col -->
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3>
                            <?php if(isset($pendingvideos)) { echo $pendingvideos; } else { echo '0'; } ?>
                        </h3>
                        <p>
                            <?php echo $welcome->loadPo('In-process Videos'); ?>
                        </p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person-add"></i>
                    </div>
                    <a href="<?php echo base_url() ?>video/video_status" class="small-box-footer">
                        <?php echo $welcome->loadPo('More info'); ?> <i class="fa fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div><!-- ./col -->
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-red">
                    <div class="inner">
                        <h3>
                            <?php if((isset($transcodedvideos)) && (isset($totalvideos))) { echo  round(($transcodedvideos/$totalvideos)*100, 2); } else { echo '0'; } ?>%
                        </h3>
                        <p>
                            <?php echo $welcome->loadPo('Convert Rate'); ?>
                        </p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                    </div>
                    <a href="#" class="small-box-footer">
                        <?php echo $welcome->loadPo('More info'); ?> <i class="fa fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div><!-- ./col -->
        </div><!-- /.row -->

        <!-- top row -->
        <div class="row">
            <div class="col-xs-12 connectedSortable">

            </div><!-- /.col -->
        </div>
        <!-- /.row -->

        <!-- Main row -->
        <div class="row">
            <!-- Left col -->
            <!-- right col (We are only adding the ID to make the widgets sortable)-->
        </div><!-- /.row (main row) -->
    </section><!-- /.content -->
</aside><!-- /.right-side -->