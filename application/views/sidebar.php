<?php
$s = $this->session->all_userdata();

$menu = array();
switch ($s[0]->username) {
    case('superadmin') :
        $menu = array(
            array('name' => 'Dashboard', 'url' => base_url() . 'layout/dashboard', 'class' => 'fa-dashboard', 'li-class' => ($this->uri->segment(1) == 'layout') ? 'active' : ''),
            array('name' => 'User', 'url' => base_url().'user', 'class' => 'fa-users', 'li-class' => (($this->uri->segment(1) == 'user') || ($this->uri->segment(1) == 'role')) ? 'treeview active' : '', 'type' => 'parent', 'childs' => array(
                    array('name' => 'Users', 'li-class' => ($this->uri->segment(1) === 'user') ? 'active' : '', 'url' => base_url() . 'user', 'type' => 'child'),
                //array('name'=>'User Role', 'li-class'=>($this->uri->segment(1) === 'role') ? 'active' : '', 'url'=>base_url().'role', 'type'=>'child'),
        )));
        break;
    case ('veena'):
        $menu = array(
            array('name' => 'Dashboard', 'url' => base_url() . 'layout/dashboard', 'class' => 'fa-dashboard', 'li-class' => ($this->uri->segment(1) == 'layout') ? 'active' : ''),
            array('name' => 'Analytics', 'url' => base_url() . 'analytics/report', 'class' => 'fa-dashboard', 'li-class' => ($this->uri->segment(1) == 'analytics') ? 'active' : ''),
            array('name' => 'Video', 'url' => base_url().'video/index', 'class' => 'fa-video-camera',
                'li-class' => (
                ($this->uri->segment(2) == 'videoUploadSrc') ||
                ($this->uri->segment(2) == 'bulkupload') ||
                ($this->uri->segment(2) == 'video_status') ||
                ($this->uri->segment(2) == 'video_settings') ||
                ($this->uri->segment(2) == 'debug') ||
                ($this->uri->segment(1) . "/" . $this->uri->segment(2) == 'video/index') ) ? 'treeview active' : '',
                'type' => 'parent', 'childs' => array(
                    array('name' => 'Video List', 'li-class' => (($this->uri->segment(1) == 'video') && ($this->uri->segment(2) == 'index')) ? 'active' : '', 'url' => base_url() . 'video/index', 'type' => 'child'),
                    array('name' => 'Video Upload', 'li-class' => ($this->uri->segment(2) == 'videoUploadSrc' || $this->uri->segment(2) == 'upload_other') ? 'active' : '', 'url' => base_url() . 'video/videoUploadSrc/Upload', 'type' => 'child'),
                    array('name' => 'Video Bulk upload', 'li-class' => ($this->uri->segment(2) == 'bulkupload' || $this->uri->segment(2) == 'ftp') ? 'active' : '', 'url' => base_url() . 'video/bulkupload/csv', 'type' => 'child'),
                    array('name' => 'Video Status', 'li-class' => ($this->uri->segment(2) == 'video_status') ? 'active' : '', 'url' => base_url() . 'video/video_status', 'type' => 'child'),
                )),
            array('name' => 'Live Stream', 'url' => base_url() . 'video/live_streaming', 'class' => 'fa-video-camera', 'li-class' => ($this->uri->segment(2) == 'live_streaming') ? 'active' : ''),
            array('name' => 'Audio', 'url' => '#', 'class' => 'fa-bullhorn', 'li-class' => ($this->uri->segment(1) == 'audio') ? 'treeview active' : 'treeview', 'type' => 'parent', 'childs' => array(
                    array('name' => 'Audio List', 'li-class' => ($this->uri->segment(1) == 'audio' && $this->uri->segment(2) == '' ) ? 'active' : '', 'url' => base_url() . 'audio', 'type' => 'child'),
                    array('name' => 'Audio Upload', 'li-class' => ($this->uri->segment(1) == 'audio' && $this->uri->segment(2) == 'upload') ? 'active' : '', 'url' => base_url() . 'audio/upload', 'type' => 'child'),
                )),
            array('name' => 'Advertising', 'url' => '#', 'class' => 'fa-film', 'li-class' => ($this->uri->segment(1) == 'advertising') ? 'treeview active' : 'treeview', 'type' => 'parent', 'childs' => array(
                    array('name' => 'Video', 'li-class' => ($this->uri->segment(1) == 'advertising' && $this->uri->segment(2) == '' ) ? 'active' : '', 'url' => base_url() . 'advertising', 'type' => 'child'),
                    array('name' => 'Live Stream', 'li-class' => ($this->uri->segment(1) == 'advertising' && $this->uri->segment(2) == 'live_stream') ? 'active' : '', 'url' => base_url() . 'advertising/live_stream', 'type' => 'child'),
                )),
            array('name' => 'WebTV', 'url' => base_url() . 'webtv', 'class' => 'fa-film', 'li-class' => ($this->uri->segment(1) == 'webtv' || $this->uri->segment(1) == 'livestream') ? 'active' : ''),
            array('name' => 'Punchang', 'url' => base_url() . 'punchang', 'class' => 'fa-film', 'li-class' => ($this->uri->segment(1) == 'punchang' || $this->uri->segment(1) == 'punchang') ? 'active' : ''),
            array('name' => 'Events', 'url' => base_url() . 'event', 'class' => 'fa-laptop', 'li-class' => ($this->uri->segment(1) == 'event') ? 'active' : ''),
            array('name' => 'Utility', 'url' => '#', 'class' => 'fa-laptop',
                'li-class' => (($this->uri->segment(1) == 'category') ||
                ($this->uri->segment(1) == 'ch_category') ||
                ($this->uri->segment(1) == 'transcode') ||
                ($this->uri->segment(1) == 'genre') ||
                ($this->uri->segment(1) == 'dform') ||
                ($this->uri->segment(1) == 'package') ||
                ($this->uri->segment(1) == 'subscription') ||
                ($this->uri->segment(1) == 'youtubevideo')) ? 'treeview active' : 'treeview',
                'type' => 'parent', 'childs' => array(
                    array('name' => 'Category', 'li-class' => ($this->uri->segment(1) === 'category') ? 'active' : '', 'url' => base_url() . 'category', 'type' => 'child'),
                    array('name' => 'Channel Category', 'li-class' => ($this->uri->segment(1) === 'ch_category') ? 'active' : '', 'url' => base_url() . 'ch_category', 'type' => 'child'),
                    array('name' => 'Package', 'li-class' => ($this->uri->segment(1) === 'genre') ? 'active' : '', 'url' => base_url() . 'genre', 'type' => 'child'),
                    array('name' => 'Adavnce Fields', 'li-class' => ($this->uri->segment(1) === 'dform') ? 'active' : '', 'url' => base_url() . 'dform', 'type' => 'child'),
                    array('name' => 'Package', 'li-class' => ($this->uri->segment(1) === 'package') ? 'active' : '', 'url' => base_url() . 'package', 'type' => 'child'),
                )),
            array('name' => 'Pages', 'url' => base_url() . 'pages', 'class' => 'fa-file-text-o', 'li-class' => ($this->uri->segment(1) == 'pages') ? 'active' : ''),
            array('name' => 'User', 'url' => '#', 'class' => 'fa-users', 'li-class' => (($this->uri->segment(1) == 'user') || ($this->uri->segment(1) == 'role')) ? 'treeview active' : 'treeview', 'type' => 'parent', 'childs' => array(
                    array('name' => 'Users', 'li-class' => ($this->uri->segment(1) === 'user') ? 'active' : '', 'url' => base_url() . 'user', 'type' => 'child'),
                    array('name' => 'User Role', 'li-class' => ($this->uri->segment(1) === 'role') ? 'active' : '', 'url' => base_url() . 'role', 'type' => 'child'),
                )),
            array('name' => 'Device', 'url' => base_url() . 'device', 'class' => 'fa-mobile', 'li-class' => ($this->uri->segment(1) == 'device') ? 'active' : ''),
            array('name' => 'Api', 'url' => base_url() . 'apilist', 'class' => 'fa-list-alt', 'li-class' => ($this->uri->segment(1) == 'apilist') ? 'active' : ''),
            array('name' => 'Comments', 'url' => base_url() . 'comments', 'class' => 'fa-comment', 'li-class' => ($this->uri->segment(1) == 'comments') ? 'active' : ''),
        );
        break;
    default :
        $menu = array(
            array('name' => 'Dashboard', 'url' => base_url() . 'layout/dashboard', 'class' => 'fa-dashboard', 'li-class' => ($this->uri->segment(1) == 'layout') ? 'active' : ''),
            array('name' => 'Analytics', 'url' => base_url() . 'analytics/report', 'class' => 'fa-dashboard', 'li-class' => ($this->uri->segment(1) == 'analytics') ? 'active' : ''),
            array('name' => 'Video', 'url' => base_url().'video/index', 'class' => 'fa-video-camera',
                'li-class' => (
                ($this->uri->segment(2) == 'videoUploadSrc') ||
                ($this->uri->segment(2) == 'bulkupload') ||
                ($this->uri->segment(2) == 'video_status') ||
                ($this->uri->segment(2) == 'video_settings') ||
                ($this->uri->segment(2) == 'debug') ||
                ($this->uri->segment(1) . "/" . $this->uri->segment(2) == 'video/index') ||
                ($this->uri->segment(1) == 'category') ) ? 'treeview active' : '',
                'type' => 'parent', 'childs' => array(
                    array('name' => 'Video List', 'li-class' => (($this->uri->segment(1) == 'video') && ($this->uri->segment(2) == 'index')) ? 'active' : '', 'url' => base_url() . 'video/index', 'type' => 'child'),
                    array('name' => 'Video Upload', 'li-class' => ($this->uri->segment(2) == 'videoUploadSrc' || $this->uri->segment(2) == 'upload_other') ? 'active' : '', 'url' => base_url() . 'video/videoUploadSrc/Upload', 'type' => 'child'),
                    array('name' => 'Video Bulk upload', 'li-class' => ($this->uri->segment(2) == 'bulkupload' || $this->uri->segment(2) == 'ftp') ? 'active' : '', 'url' => base_url() . 'video/bulkupload/csv', 'type' => 'child'),
                    array('name' => 'Video Status', 'li-class' => ($this->uri->segment(2) == 'video_status') ? 'active' : '', 'url' => base_url() . 'video/video_status', 'type' => 'child'),
                    array('name' => 'Category', 'li-class' => ($this->uri->segment(1) === 'category') ? 'active' : '', 'url' => base_url() . 'category', 'type' => 'child'),
                )),
            array('name' => 'Live Stream', 'url' => base_url() . 'video/live_streaming', 'class' => 'fa-video-camera', 'li-class' => ($this->uri->segment(2) == 'live_streaming') ? 'active' : ''),
            array('name' => 'Advertising', 'url' => base_url().'advertising', 'class' => 'fa-film', 'li-class' => ($this->uri->segment(1) == 'advertising') ? 'treeview active' : '', 'type' => 'parent', 'childs' => array(
                    array('name' => 'Video', 'li-class' => ($this->uri->segment(1) == 'advertising' && $this->uri->segment(2) == '' ) ? 'active' : '', 'url' => base_url() . 'advertising', 'type' => 'child'),
                    array('name' => 'Live Stream', 'li-class' => ($this->uri->segment(1) == 'advertising' && $this->uri->segment(2) == 'live_stream') ? 'active' : '', 'url' => base_url() . 'advertising/live_stream', 'type' => 'child'),
                    array('name' => 'Ad Configuration', 'li-class' => ($this->uri->segment(1) == 'advertising' && $this->uri->segment(2) == 'configuration') ? 'active' : '', 'url' => base_url() . 'advertising/configuration', 'type' => 'child'),
                )),
            array('name' => 'WebTV', 'url' => base_url() . 'webtv', 'class' => 'fa-film', 'li-class' => ($this->uri->segment(1) == 'webtv' || $this->uri->segment(1) == 'ch_category') ? 'treeview active' : '', 'type' => 'parent', 'childs' => array(
                    array('name' => 'WebTV', 'li-class' => ($this->uri->segment(1) == 'webtv') ? 'active' : '', 'url' => base_url() . 'webtv', 'type' => 'child'),
                    array('name' => 'Channel Category', 'li-class' => ($this->uri->segment(1) == 'ch_category') ? 'active' : '', 'url' => base_url() . 'ch_category', 'type' => 'child'),
                )),
            array('name' => 'Events', 'url' => base_url() . 'event', 'class' => 'fa-laptop', 'li-class' => ($this->uri->segment(1) == 'event') ? 'active' : ''),
            array('name' => 'Utility', 'url' => base_url().'genre', 'class' => 'fa-laptop',
                'li-class' => (
                ($this->uri->segment(1) == 'transcode') ||
                ($this->uri->segment(1) == 'genre') ||
                ($this->uri->segment(1) == 'dform') ||
                ($this->uri->segment(1) == 'package') ||
                ($this->uri->segment(1) == 'subscription') ||
                ($this->uri->segment(1) == 'youtubevideo')) ? 'treeview active' : '',
                'type' => 'parent', 'childs' => array(
                    array('name' => 'Genre', 'li-class' => ($this->uri->segment(1) === 'genre') ? 'active' : '', 'url' => base_url() . 'genre', 'type' => 'child'),
                )),
            array('name' => 'Customers', 'url' => base_url() . 'user/customers', 'class' => 'fa-users', 'li-class' => ($this->uri->segment(2) == 'customers') ? 'active' : ''),
        );
        break;
}
//echo "<pre>";
//print_r($menu); die;
?>
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
<?php foreach ($menu as $val) { ?>
                <li class="<?= $val['li-class']; ?>">
                    <a href="<?= $val['url'] ?>">
                        <i class="fa <?= $val['class'] ?>"></i>
                        <span><?php echo $welcome->loadPo($val['name']); ?></span>
    <?php if (isset($val['childs'])) { ?><i class="fa fa-angle-left pull-right"></i> <?php } ?>
                    </a>
    <?php if (isset($val['childs'])) { ?>
                        <ul class="treeview-menu">
                            <?php foreach ($val['childs'] as $child) { ?>
                                <li class="<?= $child['li-class'] ?>">
                                    <a href="<?= $child['url'] ?>">
                                        <i class="fa fa-angle-double-right"></i>
                                <?php echo $welcome->loadPo($child['name']) ?></a>
                                </li>

                                    <?php } ?> 
                        </ul>
    <?php } ?>
                </li> <?php } ?>
        </ul>
    </section>
</aside>