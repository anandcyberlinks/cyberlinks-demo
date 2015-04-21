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
            <h1>Live Channels<small>Control panel</small></h1>
            <ol class="breadcrumb">
                <li><a href="<?php echo base_url(); ?>"><i class="fa fa-dashboard"></i><?php echo $welcome->loadPo('Dashboard') ?></a></li>                                
                <li><a href="<?php echo base_url() ?>webtv"><?php echo $welcome->loadPo('Web Tv') ?></a></li>
                <li class="active"><?php echo $channel->name; ?></li>
            </ol>
        </section>
        <div>
            <div id="msg_div">	
                <?php
                if (isset($msg)) {
                    echo $msg;
                }
                ?> 
                <?php echo $this->session->flashdata('message'); ?>
            </div>	
        </div>
        <section class="content">
            <div class="box box-primary">
                <div class="box-header">
                </div><!-- /.box-header -->
                <!-- form start -->

                <div class="box-body">
                    <form action="" method="post" id="frmstream" enctype='multipart/form-data'>
                        <div class="form-group">
                            <?php /*
                              <div class="row">
                              <div class="col-md-9">
                              <label for="Name"><?php echo $welcome->loadPo('Youtube Url'); ?></label>
                              <input class="form-control" type="text" value="<?=isset($result->youtube) ? $result->youtube : ''?>" name="youtube" placeholder="Youtube URL" />
                              </div>
                              </div>

                             */ ?>

                            <div class="row">
                                <div class="col-md-9">
                                    <label for="Name"><?php echo $welcome->loadPo('IOS Url'); ?></label>
                                    <input class="form-control" type="text" value="<?= isset($result->ios) ? $result->ios : '' ?>" name="ios" placeholder="IOS URL" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-9">
                                    <label for="Name"><?php echo $welcome->loadPo('Android Url'); ?></label>
                                    <input class="form-control" type="text" value="<?= isset($result->android) ? $result->android : '' ?>" name="android" placeholder="Android URL" />
                                </div>					
                            </div>
                            <div class="row">
                                <div class="col-md-9">
                                    <label for="Name"><?php echo $welcome->loadPo('Windows Url'); ?></label>
                                    <input class="form-control" type="text" value="<?= isset($result->windows) ? $result->windows : '' ?>" name="windows" placeholder="Window URL" />
                                </div>					
                            </div>
                            <div class="row">
                                <div class="col-md-9">
                                    <label for="Name"><?php echo $welcome->loadPo('Web Url'); ?></label>
                                    <input class="form-control" type="text" value="<?= isset($result->web) ? $result->web : '' ?>" name="web" placeholder="Web URL" />
                                </div>					
                            </div>
                            <br/>
                            <div class="row"> 
                                <div class="col-md-9">
                                    <label for="Image"><?php echo $welcome->loadPo('Image'); ?></label>
                                    <span class="btn btn-default btn-file btn-sm">
                                        <?php echo $welcome->loadPo('Choose Media') ?>
                                        <input name="chanelImage"  id="chanelImage" atr="files" type="file" />
                                    </span>
                                    <span>
                                        <?php
                                        if (isset($result->thumbnail_url) && $result->thumbnail_url != '') {
                                            echo sprintf('<img width=100 height=100 src="%s" />', $result->thumbnail_url);
                                        }
                                        ?>
                                    </span>
                                </div>
                            </div>
                            <br/>
                            <div class="row"> 
                                <div class="col-md-9">
                                    <label>Epg&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                    <span class="btn btn-default btn-file btn-sm">
                                        <span id="csv_file"><?php echo $welcome->loadPo('Choose CSV') ?></span>
                                        <input name="csv"  id="csv_epg" atr="files" type="file" />
                                    </span>
                                    <a href="<?=  base_url().'assets/csv/epg.csv'?>">Download sample file</a>
                                </div>
                            </div>
                            <br/>
                            <div class="row">
                                <div class="col-md-6">		
                                    <button name="save" type="submit" class="btn btn-success" id="save">Save</button>
                                    <a class="btn btn-warning" href="<?= base_url() ?>webtv"><i class="fa fa-mail-reply"></i>&nbsp;Back</a>
                                </div>
                            </div>
                        </div>
                    </form>


                   
                </div><!-- /.box-body -->
            </div>

            <?php if (isset($result->web) && $result->web != '') { ?>
                <div class="box box-success">
                    <div class="box-body">
                        <iframe width="100%" height="400px" src='<?= base_url() . 'livestream/rendervideo/?path=' . base64_encode($result->web) ?>' allowfullscreen="" frameborder="0"></iframe>
                    </div><!-- /.box-body -->
                </div>
            <?php } ?>
        </section>
    </aside><!-- /.right-side -->
</div><!-- ./wrapper -->
<script>
    $("document").ready(function () {
        $("#csv_epg").change(function () {
            var filename = $("#csv_epg").val();
            var valid_extensions = /(\.csv)$/i;
            if (valid_extensions.test(filename))
            {
                $("#csv_file").html(filename+"<img src='<?=base_url()?>/assets/img/spinner.gif'>");
                $("#csv_epg").addClass('disabled');
                var formData = new FormData($("#frmstream")[0]);
               $("#save").addClass('disabled');
               $("#save").html("<img src='<?=base_url()?>/assets/img/spinner.gif'>");
                $.ajax({
                    url: '<?=  base_url().'webtv/Epg/'.$this->uri->segment(3)?>',
                    type: 'POST',
                    data: formData,
                    async: false,
                    success: function (data) {
                        bootbox.alert(data);
                    },
                    cache: false,
                    contentType: false,
                    processData: false
                });
                $("#save").removeClass('disabled');
                $("#save").html('Save');
                $("#csv_file").html(filename);
            }
            else
            {   
                $("#csv_file").html('Choose CSV');
                bootbox.alert('Invalid File, Please choose valid csv file');
                $("#csv_file").attr('val', '');
            }
            return false;
        });
    });
</script>
