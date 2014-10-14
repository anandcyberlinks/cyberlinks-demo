<?php $s = $this->session->all_userdata(); ?>
<aside class="left-side sidebar-offcanvas">                
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
            </div>
            <div class="pull-left info">
                <p><?php echo $welcome->loadPo('Hello'); ?>, <?php echo $s[0]->first_name ?></p>
                <small><i class="fa fa-circle text-success"></i> Online</small>
            </div>
        </div>
        <!-- search form -->

        <!-- /.search form -->
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
            <!---
            <li class="<?=($this->uri->segment(1)=='layout')?'active':''?>">
                <a href="<?php echo base_url() ?>layout">
                    <i class="fa fa-dashboard"></i> <span><?php echo $welcome->loadPo('Dashboard'); ?></span>
                </a>
            </li>
            --->
            
            <li class="treeview <?=(($this->uri->segment(1)=='video') || ($this->uri->segment(1)=='video_settings'))?'active':''?>">
                <a href="#">
                    <i class="fa fa-play-circle"></i>
                    <span><?php echo $welcome->loadPo('Video'); ?></span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li class="<?=(($this->uri->segment(1)=='video') && ($this->uri->segment(2)==''))?'active':''?>"><a href="<?php echo base_url() ?>video"><i class="fa fa-angle-double-right"></i> <?php echo $welcome->loadPo('Search'); ?></a></li>
                    <li class="<?=($this->uri->segment(2)=='videoUploadSrc' || $this->uri->segment(2)=='upload_other')?'active':''?>"><a href="<?php echo base_url() ?>video/videoUploadSrc/Upload"><i class="fa fa-angle-double-right"></i> <?php echo $welcome->loadPo('Upload')." ".$welcome->loadPo('Video'); ?> </a></li>
                    <li class="<?=($this->uri->segment(2)=='bulkupload' || $this->uri->segment(2)=='ftp')?'active':''?>"><a href="<?php echo base_url() ?>video/bulkupload/csv"><i class="fa fa-angle-double-right"></i>  <?php echo $welcome->loadPo('Bulk')." ".$welcome->loadPo('Upload')." ".$welcome->loadPo('Video'); ?></a></li>
                    <li class="<?=($this->uri->segment(2)=='video_status')?'active':''?>"><a href="<?php echo base_url() ?>video/video_status"><i class="fa fa-angle-double-right"></i> <?php echo $welcome->loadPo('Video')." ".$welcome->loadPo('Status'); ?></a></li>                   
                  <?php /**  <li class="<?=($this->uri->segment(2)=='setting')?'active':''?>"><a href="<?php echo base_url() ?>video/setting"><i class="fa fa-angle-double-right"></i> <?php echo $welcome->loadPo('Video')." ".$welcome->loadPo('Settings'); ?> </a></li>  */ ?>                 
					<li class="<?=($this->uri->segment(2)=='live_streaming')?'active':''?>"><a href="<?php echo base_url() ?>video/live_streaming"><i class="fa fa-angle-double-right"></i> <?php echo $welcome->loadPo('Live Streaming'); ?> </a></li>			  
				</ul>
            </li>
			<li class="treeview <?=(($this->uri->segment(1)=='category') || ($this->uri->segment(1)=='genre') || ($this->uri->segment(1)=='youtubevideo'))?'active':''?>">
                <a href="#">
                    <i class="fa fa-laptop"></i>
                    <span><?php echo $welcome->loadPo('Utility'); ?></span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li class="<?=($this->uri->segment(1)==='category')?'active':''?>"><a href="<?php echo base_url() ?>category"><i class="fa fa-angle-double-right"></i> <?php echo $welcome->loadPo('Category'); ?></a></li>
                    <!--<li class="<?=($this->uri->segment(1)==='transcode')?'active':''?>"><a href="<?php echo base_url() ?>transcode"><i class="fa fa-angle-double-right"></i> <?php echo $welcome->loadPo('Transcode'); ?></a></li>         -->           
		    <li class="<?=($this->uri->segment(1)==='youtubevideo')?'active':''?>"><a href="<?php echo base_url() ?>youtubevideo"><i class="fa fa-angle-double-right"></i> <?php echo $welcome->loadPo('Youtube'); ?></a></li>
		    <li class="<?=($this->uri->segment(1)==='genre')?'active':''?>"><a href="<?php echo base_url() ?>genre"><i class="fa fa-angle-double-right"></i> <?php echo $welcome->loadPo('Genre'); ?></a></li>
		</ul>
            </li>
	    <li class="<?=($this->uri->segment(1)=='pages')?'active':''?>">
                <a href="<?php echo base_url() ?>pages">
                    <i class="glyphicon glyphicon-th-list"></i> <span><?php echo $welcome->loadPo('CMS Pages'); ?></span>
                </a>
            </li>
            <?php $s = $this->session->all_userdata();
            if($s[0]->role == 'Superadmin' || $s[0]->role == 'Admin'){
                ?>
            
            <li class="<?=($this->uri->segment(1)=='user')?'active':''?>">
                <a href="<?php echo base_url() ?>user">
                    <i class="fa fa-user"></i> <span><?php echo $welcome->loadPo('Users'); ?></span>
                </a>
            </li>
            <?php } ?>
             <?php if($s[0]->role == 'Admin') {
                ?>
            <li class="<?=($this->uri->segment(1)=='role')?'active':''?>">
                <a href="<?php echo base_url() ?>role">
                    <i class="fa fa-fw fa-minus-circle"></i> <span><?php echo $welcome->loadPo('User Role'); ?></span>
                </a>
            </li>
            <?php } ?>
	    <?php if($s[0]->role == 'Admin') {
                ?>
            <li class="<?=($this->uri->segment(1)=='apilist')?'active':''?>">
                <a href="<?php echo base_url() ?>apilist">
                    <i class="fa fa-fw fa-list-alt"></i> <span><?php echo $welcome->loadPo('API'); ?></span>
                </a>
            </li>
            <?php } ?>
	    <?php if($s[0]->role == 'Admin') {
                ?>
            <li class="<?=($this->uri->segment(1)=='device')?'active':''?>">
                <a href="<?php echo base_url() ?>device">
                    <i class="fa fa-fw fa-list-alt"></i> <span><?php echo $welcome->loadPo('Device'); ?></span>
                </a>
            </li>
            <?php } ?>

	    <?php /* ?>
			<li class="<?=($this->uri->segment(1)=='comments')?'active':''?>">
                <a href="<?php echo base_url() ?>comments">
                    <i class="glyphicon glyphicon-comment"></i> <span><?php echo $welcome->loadPo('Comment Section'); ?></span>
                </a>
            </li>  <?php */ ?>
        </ul>
    </section>
</aside>
