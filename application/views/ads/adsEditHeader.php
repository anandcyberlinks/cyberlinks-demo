<div class="wrapper row-offcanvas row-offcanvas-left">
    <!-- Right side column. Contains the navbar and content of the page -->
    <aside class="right-side">                
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1><?php echo $welcome->loadPo('Ads') ?> <small><?php echo $welcome->loadPo('Control panel') ?></small></h1>
            <ol class="breadcrumb">
                <li><a href="<?php echo base_url(); ?>"><i class="fa fa-dashboard"></i><?php echo $welcome->loadPo('Dashboard') ?></a></li>
                <li><a href="<?php echo base_url(); ?>ads"><i class="fa fa-play-circle"></i><?php echo $welcome->loadPo('Ads') ?></a></li>
                <li class="active"><?php echo $welcome->loadPo('Edit') ?></li>
            </ol>
        </section>
        <div>
            <div id="msg_div">
                <?php echo $this->session->flashdata('message'); ?>
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
                                <li class="<?= ($this->uri->segment(3) === 'Basic') ? 'active' : '' ?>" ><a href="<?php echo base_url(); ?>ads/videoOpr/Basic?action=<?php if (isset($id) && $id != "") {
												echo base64_encode($id) . '&';
											} else {
												echo $_GET['action'];
											} ?>"><?php echo $welcome->loadPo('Basic') ?></a></li>
								
                                <li class="<?= ($this->uri->segment(3) === 'Thumbnail') ? 'active' : '' ?>" ><a href="<?php echo base_url(); ?>ads/videoOpr/Thumbnail?action=<?php if (isset($id) && $id != "") {
                                                echo base64_encode($id) . '&';
                                            } else {
                                                echo $_GET['action'];
                                            } ?>"><?php echo $welcome->loadPo('Thumbnail') ?></a></li>
                                <?php /* ?><li class="<?= ($this->uri->segment(3) === 'Flavor') ? 'active' : '' ?>" ><a href="<?php echo base_url(); ?>video/videoOpr/Flavor?action=<?php if (isset($id) && $id != "") {
                                                echo base64_encode($id) . '&';
                                            } else {
                                                echo $_GET['action'];
                                            } ?>"><?php echo $welcome->loadPo('Flavor') ?></a></li>
                                <li class="<?= ($this->uri->segment(3) === 'Promo') ? 'active' : '' ?>" ><a href="<?php echo base_url(); ?>video/videoOpr/Promo?action=<?php if (isset($id) && $id != "") {
                                                echo base64_encode($id) . '&';
                                            } else {
                                                echo $_GET['action'];
                                            } ?>"><?php echo $welcome->loadPo('Promo') ?></a></li>
                                <li class="pull-right">
                                    <a class="dropdown-toggle btn btn-default btn-sm" data-toggle="dropdown" ><?php echo $welcome->loadPo('Action') ?><span class="caret"></span></a>
                                    <ul class="dropdown-menu">
                                        <li role="presentation">
<?php foreach ($result as $value) { ?>
                                            <td style='text-align:center'><a class="prev_video" href="#myModal" data-backdrop="static" data-toggle="modal" data-img-url="<?php echo amazonFileUrl.$value->file ?>"><?php echo $welcome->loadPo('Preview') ?></a></td>
                                    </li>
                                    <li role="presentation">
                                        <a class="confirm" onclick="return delete_video1(<?php echo $value->id; ?>);" href="#" ><?php echo $welcome->loadPo('Delete') ?></a>  
                                    </li>
<?php } ?>
                                <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo base_url() ?>video"><?php echo $welcome->loadPo('Back') ?></a></li>
                            </ul>
                            </li><?php */ ?>
                            </ul>
                            <!--  this div for  jwplyer reponce -->
                            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" 
                                 aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" 
                                                    data-dismiss="modal" aria-hidden="true" onclick='stopvideo()'>
                                                &times;
                                            </button>
                                            <h4 class="modal-title" id="myModalLabel">
                                                Preview
                                        </div>
                                        <div class="modal-body no-padding">        
                                            <center>   <div id="prevElement"></div></center>
                                        </div>
                                    </div><!-- /.modal-content -->
                                </div><!-- /.modal -->
                            </div>
                            <div class="tab-content">
