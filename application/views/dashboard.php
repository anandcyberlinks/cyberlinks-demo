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
            <div class="col-lg-2 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3>
                            <?php echo $videos[0]->total_video ?>
                        </h3>
                        <p>
                           <?php echo $welcome->loadPo('Total Videos'); ?> 
                        </p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-video-camera"></i>
                    </div>
                    <a href="<?php echo base_url() ?>video" class="small-box-footer">
                        <?php echo $welcome->loadPo('More info'); ?> <i class="fa fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div><!-- ./col -->
            <div class="col-lg-2 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-light-blue">
                    <div class="inner">
                        <h3>
                            <?php echo $videos[0]->youtube_video ?>
                        </h3>
                        <p>
                           <?php echo $welcome->loadPo('Youtube Videos'); ?> 
                        </p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-youtube-play"></i>
                    </div>
                    <a href="<?php echo base_url() ?>video" class="small-box-footer">
                        <?php echo $welcome->loadPo('More info'); ?> <i class="fa fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            <div class="col-lg-2 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-fuchsia">
                    <div class="inner">
                        <h3>
                            <?php echo $videos[0]->total_jobs ?>
                        </h3>
                        <p>
                           <?php echo $welcome->loadPo('Total Jobs'); ?> 
                        </p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-forward"></i>
                    </div>
                    <a href="<?php echo base_url() ?>video" class="small-box-footer">
                        <?php echo $welcome->loadPo('More info'); ?> <i class="fa fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            <div class="col-lg-2 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-red">
                    <div class="inner">
                        <h3>
                           <?php echo $videos[0]->completed_jobs ?>
                        </h3>
                        <p>
                            <?php echo $welcome->loadPo('Completed Jobs'); ?>
                        </p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-thumbs-up"></i>
                    </div>
                    <a href="<?php echo base_url() ?>video/video_status" class="small-box-footer">
                        <?php echo $welcome->loadPo('More info'); ?> <i class="fa fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div><!-- ./col -->
            <div class="col-lg-2 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3>
                            <?php echo $videos[0]->inprocess_jobs ?>
                        </h3>
                        <p>
                            <?php echo $welcome->loadPo('In-process Jobs'); ?>
                        </p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-clock-o"></i>
                    </div>
                    <a href="<?php echo base_url() ?>video/video_status" class="small-box-footer">
                        <?php echo $welcome->loadPo('More info'); ?> <i class="fa fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div><!-- ./col -->
            <div class="col-lg-2 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-purple">
                    <div class="inner">
                        <h3>
                            <?php echo $videos[0]->pending_jobs ?>
                        </h3>
                        <p>
                            <?php echo $welcome->loadPo('Pending Jobs'); ?>
                        </p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-thumbs-down"></i>
                    </div>
                    <a href="#" class="small-box-footer">
                        <?php echo $welcome->loadPo('More info'); ?> <i class="fa fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div><!-- ./col -->
        </div><!-- /.row -->

        <!-- top row -->
        <div class="row">
            <div class="col-xs-6">
<!-- Custom tabs (Charts with tabs)-->
                            <div class="nav-tabs-custom">
                                <!-- Tabs within a box -->
                                <ul class="nav nav-tabs pull-right">
                                    <li class="active"><a href="#revenue-chart" data-toggle="tab">Area</a></li>
                                    <li><a href="#sales-chart" data-toggle="tab">Donut</a></li>
                                    <li class="pull-left header"><i class="fa fa-inbox"></i> Users Video</li>
                                </ul>
                                <div class="tab-content no-padding">
                                    <!-- Morris chart - Sales -->
                                    <div class="chart tab-pane active" id="revenue-chart" style="position: relative; height: 300px;"></div>
                                    <div class="chart tab-pane" id="sales-chart" style="position: relative; height: 300px;"></div>
                                </div>
                            </div><!-- /.nav-tabs-custom -->
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