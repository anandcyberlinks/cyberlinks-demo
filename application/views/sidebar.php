<?php $s = $this->session->all_userdata(); ?>
<aside class="left-side sidebar-">                
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image"></div>
            <div class="pull-left info">
                <p><?php echo $welcome->loadPo('Hello'); ?>, <?php echo $s[0]->first_name ?></p>
                <small><i class="fa fa-circle text-success"></i> Online</small>
            </div>
        </div>
        <!-- search form -->
        <!-- /.search form -->
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
            <li class="<?= ($this->uri->segment(1) == 'layout') ? 'active' : '' ?>">
                <a href="<?php echo base_url() ?>layout/dashboard">
                    <i class="fa fa-dashboard"></i> <span><?php echo $welcome->loadPo('Dashboard'); ?></span></a>
            </li>
             <li class="<?= ($this->uri->segment(1) == 'analytics') ? 'active' : '' ?>">
                <a href="<?php echo base_url() ?>analytics/report">
                    <i class="fa fa-dashboard"></i> <span><?php echo $welcome->loadPo('Analytics'); ?></span></a>
            </li>
            <li class="treeview <?= (($this->uri->segment(2) == 'videoUploadSrc') || ($this->uri->segment(2) == 'bulkupload') || ($this->uri->segment(2) == 'video_status') || ($this->uri->segment(2) == 'video_settings') || ($this->uri->segment(2) == 'debug') || ($this->uri->segment(1) . "/" . $this->uri->segment(2) == 'video/index') ) ? 'active' : '' ?>">
                <a href="#">
                    <i class="fa fa-video-camera"></i>
                    <span><?php echo $welcome->loadPo('Video'); ?></span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li class="<?= (($this->uri->segment(1) == 'video') && ($this->uri->segment(2) == 'index')) ? 'active' : '' ?>"><a href="<?php echo base_url() ?>video/index"><i class="fa fa-angle-double-right"></i> <?php echo $welcome->loadPo('Video') . " " . $welcome->loadPo('List'); ?></a></li>
                    <li class="<?= ($this->uri->segment(2) == 'videoUploadSrc' || $this->uri->segment(2) == 'upload_other') ? 'active' : '' ?>"><a href="<?php echo base_url() ?>video/videoUploadSrc/Upload"><i class="fa fa-angle-double-right"></i> <?php echo $welcome->loadPo('Video') . " " . $welcome->loadPo('Upload'); ?> </a></li>
                    <li class="<?= ($this->uri->segment(2) == 'bulkupload' || $this->uri->segment(2) == 'ftp') ? 'active' : '' ?>"><a href="<?php echo base_url() ?>video/bulkupload/csv"><i class="fa fa-angle-double-right"></i>  <?php echo $welcome->loadPo('Video') . " " . $welcome->loadPo('Bulk') . " " . $welcome->loadPo('Upload'); ?></a></li>
                    <li class="<?= ($this->uri->segment(2) == 'video_status') ? 'active' : '' ?>"><a href="<?php echo base_url() ?>video/video_status"><i class="fa fa-angle-double-right"></i> <?php echo $welcome->loadPo('Video') . " " . $welcome->loadPo('Status'); ?></a></li>                   
                    <?php /* <li class="<?=($this->uri->segment(2)=='setting')?'active':''?>"><a href="<?php echo base_url() ?>video/setting"><i class="fa fa-angle-double-right"></i> <?php echo $welcome->loadPo('Video')." ".$welcome->loadPo('Settings'); ?> </a></li>     */ ?>
                    <li class="<?= ($this->uri->segment(2) == 'debug') ? 'active' : '' ?>"><a href="<?php echo base_url() ?>video/debug"><i class="fa fa-angle-double-right"></i> <?php echo $welcome->loadPo('Debug'); ?></a></li>
                </ul>
            </li>
            <li class="<?= ($this->uri->segment(1) == 'livestream') ? 'active' : '' ?>">
                <!--
		<a href="<?php echo base_url() ?>video/live_streaming" >
                    <i class="fa fa-fw fa-film"></i> <span><?php echo $welcome->loadPo('Live Stream'); ?></span>
                </a>
		-->
                 <?php if($s[0]->role == 'Superadmin'){?>
		<a href="<?php echo base_url() ?>livestream/slist" >
                <?php }else{?>
                <a href="<?php echo base_url() ?>livestream/" >
                <?php }?>
                    <i class="fa fa-fw fa-film"></i> <span><?php echo $welcome->loadPo('Live Stream'); ?></span>
                </a>
            </li>
            
            <li class="<?= ($this->uri->segment(1) == 'webtv') ? 'active' : '' ?>">
                <a href="<?php echo base_url() ?>webtv" >
                    <i class="fa fa-fw fa-film"></i> <span><?php echo $welcome->loadPo('WebTV'); ?></span>
                </a>
            </li>
                      <li class="<?= ($this->uri->segment(1) == 'event') ? 'active' : '' ?>">
                      <a href="<?php echo base_url() ?>event">
                      <i class="fa fa-fw fa-laptop"></i> <span><?php echo $welcome->loadPo('Events'); ?></span>
                      </a>
                      </li>
                    
            <li class="treeview <?= (($this->uri->segment(1) == 'category') || ($this->uri->segment(1) == 'ch_category') || ($this->uri->segment(1) == 'transcode') || ($this->uri->segment(1) == 'genre') || ($this->uri->segment(1) == 'dform') || ($this->uri->segment(1) == 'package') || ($this->uri->segment(1) == 'subscription') || ($this->uri->segment(1) == 'youtubevideo')) ? 'active' : '' ?>">
                <a href="#">
                    <i class="fa fa-laptop"></i>
                    <span><?php echo $welcome->loadPo('Utility'); ?></span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li class="<?= ($this->uri->segment(1) === 'category') ? 'active' : '' ?>"><a href="<?php echo base_url() ?>category"><i class="fa fa-angle-double-right"></i> <?php echo $welcome->loadPo('Category'); ?></a></li>
                    <li class="<?= ($this->uri->segment(1) === 'ch_category') ? 'active' : '' ?>"><a href="<?php echo base_url() ?>ch_category"><i class="fa fa-angle-double-right"></i> <?php echo $welcome->loadPo('Channel Category'); ?></a></li>
                    <!--<li class="<?= ($this->uri->segment(1) === 'transcode') ? 'active' : '' ?>"><a href="<?php echo base_url() ?>transcode"><i class="fa fa-angle-double-right"></i> <?php echo $welcome->loadPo('Transcode'); ?></a></li>         -->
                    <?php if ($s[0]->role == 'Superadmin') { ?>
                        <li class="<?= ($this->uri->segment(1) === 'genre') ? 'active' : '' ?>"><a href="<?php echo base_url() ?>genre"><i class="fa fa-angle-double-right"></i> <?php echo $welcome->loadPo('Genre'); ?></a></li>
                    <?php } ?>
                    <?php /* <li class="<?=($this->uri->segment(1)==='youtubevideo')?'active':''?>"><a href="<?php echo base_url() ?>youtubevideo"><i class="fa fa-angle-double-right"></i> <?php echo $welcome->loadPo('Youtube'); ?></a></li> */ ?>
                    <li class="<?= ($this->uri->segment(1) === 'dform') ? 'active' : '' ?>"><a href="<?php echo base_url() ?>dform"><i class="fa fa-angle-double-right"></i> <?php echo $welcome->loadPo('Advance Fields'); ?></a></li>
                    <?php /* <li class="<?= ($this->uri->segment(1) === 'subscription') ? 'active' : '' ?>"><a href="<?php echo base_url() ?>subscription"><i class="fa fa-angle-double-right"></i> <?php echo $welcome->loadPo('Subscription'); ?></a></li> */ ?>
                    <li class="<?= ($this->uri->segment(1) === 'package') ? 'active' : '' ?>"><a href="<?php echo base_url() ?>package"><i class="fa fa-angle-double-right"></i> <?php echo $welcome->loadPo('Package'); ?></a></li>
                </ul>
            </li>
            <li class="<?=($this->uri->segment(1) == 'pages') ? 'active' : '' ?>">
                <a href="<?php echo base_url() ?>pages">
                    <i class="fa fa-file-text-o"></i> <span><?php echo $welcome->loadPo('Pages'); ?></span>
                </a>
            </li>
            <?php
            $s = $this->session->all_userdata();
            if ($s[0]->role == 'Admin' || $s[0]->role == 'Superadmin') {
                ?>
                <li class="treeview <?= (($this->uri->segment(1) == 'user') || ($this->uri->segment(1) == 'role')) ? 'active' : '' ?>">
                    <a href="#">
                        <i class="fa fa-users"></i>
                        <span><?php echo $welcome->loadPo('User'); ?></span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li class="<?= ($this->uri->segment(1) === 'user') ? 'active' : '' ?>"><a href="<?php echo base_url() ?>user"><i class="fa fa-angle-double-right"></i> <?php echo $welcome->loadPo('User List'); ?></a></li>
                        <li class="<?= ($this->uri->segment(1) === 'role') ? 'active' : '' ?>"><a href="<?php echo base_url() ?>role"><i class="fa fa-angle-double-right"></i> <?php echo $welcome->loadPo('User Role'); ?></a></li>
                    </ul>
                </li>
                <li class="<?= ($this->uri->segment(1) == 'device') ? 'active' : '' ?>">
                    <a href="<?php echo base_url() ?>device">
                        <i class="fa fa-fw fa-mobile"></i> <span><?php echo $welcome->loadPo('Device'); ?></span>
                    </a>
                </li>
            <?php } ?>
            <li class="<?= ($this->uri->segment(1) == 'apilist') ? 'active' : '' ?>">
                <a href="<?php echo base_url() ?>apilist">
                    <i class="fa fa-fw fa-list-alt"></i> <span><?php echo $welcome->loadPo('API'); ?></span>
                </a>
            </li>
            <li class="<?= ($this->uri->segment(1) == 'comments') ? 'active' : '' ?>">
                <a href="<?php echo base_url() ?>comments">
                    <i class="glyphicon glyphicon-comment"></i>
                    <span><?php echo $welcome->loadPo('Comments'); ?></span>
                </a>
            </li>
        </ul>
    </section>
</aside>