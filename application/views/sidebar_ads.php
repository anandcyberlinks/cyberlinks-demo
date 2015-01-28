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
           <!-- <li class="<?= ($this->uri->segment(1) == 'ads_analytics') ? 'active' : '' ?>">
                <a href="<?php echo base_url() ?>ads_analytics/report">
                    <i class="fa fa-dashboard"></i> <span><?php echo $welcome->loadPo('Ads Analytics'); ?></span></a>
            </li>-->
            <li class="treeview <?= (($this->uri->segment(2) == 'videoUploadSrc') || ($this->uri->segment(2) == 'bulkupload') || ($this->uri->segment(2) == 'video_status') || ($this->uri->segment(2) == 'video_settings') || ($this->uri->segment(2) == 'debug') || ($this->uri->segment(1) . "/" . $this->uri->segment(2) == 'video/index') ) ? 'active' : '' ?>">
                <a href="#">
                    <i class="fa fa-video-camera"></i>
                    <span><?php echo $welcome->loadPo('Ads'); ?></span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li class="<?= (($this->uri->segment(1) == 'ads') && ($this->uri->segment(2) == 'index')) ? 'active' : '' ?>"><a href="<?php echo base_url() ?>ads/index"><i class="fa fa-angle-double-right"></i> <?php echo $welcome->loadPo('Ads') . " " . $welcome->loadPo('List'); ?></a></li>
                    <li class="<?= ($this->uri->segment(2) == 'videoUploadSrc' || $this->uri->segment(2) == 'upload_other') ? 'active' : '' ?>"><a href="<?php echo base_url() ?>ads/videoUploadSrc/Upload"><i class="fa fa-angle-double-right"></i> <?php echo $welcome->loadPo('Ads') . " " . $welcome->loadPo('Upload'); ?> </a></li>
                    <li class="<?= ($this->uri->segment(2) == 'bulkupload' || $this->uri->segment(2) == 'ftp') ? 'active' : '' ?>"><a href="<?php echo base_url() ?>ads/bulkupload/csv"><i class="fa fa-angle-double-right"></i>  <?php echo $welcome->loadPo('Ads') . " " . $welcome->loadPo('Bulk') . " " . $welcome->loadPo('Upload'); ?></a></li>
                    <li class="<?= ($this->uri->segment(2) == 'video_status') ? 'active' : '' ?>"><a href="<?php echo base_url() ?>ads/video_status"><i class="fa fa-angle-double-right"></i> <?php echo $welcome->loadPo('Ads') . " " . $welcome->loadPo('Status'); ?></a></li>                  
                </ul>
            </li>
                                            
            <li class="treeview <?= (($this->uri->segment(1) == 'category') || ($this->uri->segment(1) == 'transcode') || ($this->uri->segment(1) == 'genre') || ($this->uri->segment(1) == 'dform') || ($this->uri->segment(1) == 'package') || ($this->uri->segment(1) == 'subscription') || ($this->uri->segment(1) == 'youtubevideo')) ? 'active' : '' ?>">
                <a href="#">
                    <i class="fa fa-laptop"></i>
                    <span><?php echo $welcome->loadPo('Utility'); ?></span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li class="<?= ($this->uri->segment(1) === 'category') ? 'active' : '' ?>"><a href="<?php echo base_url() ?>category"><i class="fa fa-angle-double-right"></i> <?php echo $welcome->loadPo('Category'); ?></a></li>                                    
                </ul>
            </li>
            
        </ul>
    </section>
</aside>