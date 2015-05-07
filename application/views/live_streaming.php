<style>
    .error{
        color: red;
    }
</style>
<!--div class="wrapper row-offcanvas row-offcanvas-left"-->
    <!-- Right side column. Contains the navbar and content of the page -->
    <aside class="content-wrapper">                
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>Video<small>Control panel</small></h1>
            <ol class="breadcrumb">
                <li><a href="<?php echo base_url(); ?>"><i class="fa fa-dashboard"></i><?php echo $welcome->loadPo('Dashboard') ?></a></li>
                <li><a href="<?php echo base_url(); ?>video"><i class="fa fa-play-circle"></i><?php echo $welcome->loadPo('Video') ?></a></li>
                <li class="active"><?php echo $welcome->loadPo('Live Streaming') ?></li>
            </ol>
        </section>
            <div id="msg_div">	
                <?php
                $url = (count($url) != 0)?json_decode($url[0]->value): array();
                if (isset($msg)) {
                    echo $msg;
                } ?> 
            <?php echo $this->session->flashdata('message'); ?>
            </div>	
        <section class="content">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">Live Stream</h3>
                </div><!-- /.box-header -->
                <!-- form start -->
                <form action="" method="post" id="registerId">
                    <div class="box-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-9">
                                    <label for="Name"><?php echo $welcome->loadPo('Youtube Url'); ?></label>
                                    <input class="form-control" type="text" value="<?=(isset($url->youtube))? $url->youtube: ''?>" name="url[youtube]" placeholder="Youtube Url" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-9">
                                    <label for="Name"><?php echo $welcome->loadPo('IOS Url'); ?></label>
                                    <input class="form-control" type="text" value="<?=(isset($url->ios))? $url->ios: ''?>" name="url[ios]" placeholder="IOS URL" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-9">
                                    <label for="Name"><?php echo $welcome->loadPo('Android Url'); ?></label>
                                    <input class="form-control" type="text" value="<?=(isset($url->android))? $url->android: ''?>" name="url[android]" placeholder="Android URL" />
                                </div>					
                            </div>
                            <div class="row">
                                <div class="col-md-9">
                                    <label for="Name"><?php echo $welcome->loadPo('Windows Url'); ?></label>
                                    <input class="form-control" type="text" value="<?=(isset($url->window))? $url->window: ''?>" name="url[window]" placeholder="Web URL" />
                                </div>					
                            </div>
                            <div class="row">
                                <div class="col-md-9">
                                    <label for="Name"><?php echo $welcome->loadPo('Web Url'); ?></label>
                                    <input class="form-control" type="text" value="<?=(isset($url->web))? $url->web: ''?>" name="url[web]" placeholder="Livestrem URL" />
                                </div>					
                            </div><br>
                            <div class="row">
                                <div class="col-md-9">
                                    <?php if (count($url) != '0') { ?>
                                        <a onclick="return delete_url('<?php echo base_url() . 'video/deleteLive'; ?>');" href="" ><button class="btn btn-danger btn-sm" data-toggle="modal" data-target=".bs-example-modal-sm" >Delete</button></a>
                                        <?php } ?>
                                    <input name="save" type="submit" value="Save" class="btn btn-success btn-sm">
                                </div>
                            </div>
                        </div>
                    </div>
            </div><!-- /.box-body -->
            </form>
        <?php if (count($url) != 0) { ?>
                <div class="box box-success">
                    <div class="box-body">
                        <iframe width="100%" height="400px" src='<?= base_url() . 'video/rendervideo/?path=' . base64_encode($url->web) ?>' allowfullscreen="" frameborder="0"></iframe>
                    </div><!-- /.box-body -->
                </div>
            <?php } ?>
        </section>
    </aside><!-- /.right-side -->
<!--/div--><!-- ./wrapper -->