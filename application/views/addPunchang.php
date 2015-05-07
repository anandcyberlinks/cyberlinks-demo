<style>
    .error{
        color: red;
    }
</style>
<div class="wrapper row-offcanvas row-offcanvas-left">
    <!-- Right side column. Contains the navbar and content of the page -->
    <aside class="content-wrapper"> 
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1><?php echo $welcome->loadPo('Punchang') ?><small><?php echo $welcome->loadPo('List') ?></small>
                <a href="" class="btn btn-success btn-sm">Add</a>
            </h1>
            <ol class="breadcrumb">
                <li><a href="<?php echo base_url(); ?>"><i class="fa fa-dashboard"></i><?php echo $welcome->loadPo('Dashboard') ?></a></li>
                <li class="active"><?php echo $welcome->loadPo('Punchang') ?></li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div id="content">
                <div id="msg">
                    <?php
                    $msg = $this->session->flashdata('message');
                    if ($msg != '') {
                        echo $msg;
                    }
                    ?>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="box box-info">
                            <div class="box-header">
                                <h3 class="box-title">Punchang</h3>
                            </div>
                            <div class="box-body">
                                <form id="Punchang" action="" method="post">
                                    <?php if(isset($result)){ ?>
                                        <input type="hidden" name="id" value="<?=$result[0]->id?>" />
                                    <?php } ?>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <!-- time Picker -->
                                            <div class="form-group">
                                                <label>Date:</label>
                                                <?php if(isset($result)){ ?>
                                                <input type="text" class="form-control" value="<?=$result[0]->date?>" readonly="true"/>
                                                <?php } else{  ?>
                                                    <input type="text" name="date" id="datepick" class="form-control" placeholder="Date" readonly="true"/>
                                                <?php } ?>
                                            </div><!-- /.form group -->
                                        </div>

                                        <div class="col-lg-6">
                                            <!-- time Picker -->
                                            <div class="form-group">
                                                <label>Month</label>
                                                <input type="text" name="month" value="<?=(isset($result))?$result[0]->month:''?>" class="form-control" placeholder="Month"/>
                                            </div><!-- /.form group -->
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <!-- time Picker -->
                                            <div class="form-group">
                                                <label>Pakshya:</label>
                                                <input type="text" name="pakshya" value="<?=(isset($result))?$result[0]->pakshya:''?>" class="form-control" placeholder="Pakshya"/>
                                            </div><!-- /.form group -->
                                        </div>

                                        <div class="col-lg-6">
                                            <!-- time Picker -->
                                            <div class="form-group">
                                                <label>Tithi</label>
                                                <input type="text" name="tithi" value="<?=(isset($result))?$result[0]->tithi:''?>" class="form-control" placeholder="Tithi"/>
                                            </div><!-- /.form group -->
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <!-- time Picker -->
                                            <div class="bootstrap-timepicker">
                                                <div class="form-group">
                                                    <label>Sunrise</label>
                                                    <div class="input-group">                                            
                                                        <input type="text" name="sunrise" value="<?=(isset($result))?$result[0]->sunrise:''?>" class="form-control timepicker"/>
                                                        <div class="input-group-addon">
                                                            <i class="fa fa-clock-o"></i>
                                                        </div>
                                                    </div><!-- /.input group -->
                                                </div><!-- /.form group -->

                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <!-- time Picker -->
                                            <div class="bootstrap-timepicker">
                                                <div class="form-group">
                                                    <label>Sunset</label>
                                                    <div class="input-group">                                            
                                                        <input type="text" name="sunset" value="<?=(isset($result))?$result[0]->sunset:''?>" class="form-control timepicker" placeholder="tIME"/>
                                                        <div class="input-group-addon">
                                                            <i class="fa fa-clock-o"></i>
                                                        </div>
                                                    </div><!-- /.input group -->
                                                </div><!-- /.form group -->

                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-5">
                                            <!-- time Picker -->
                                            <div class="bootstrap-timepicker">
                                                <div class="form-group">
                                                    <label>Rahukal</label>
                                                    <div class="input-group">                                            
                                                        <input type="text" name="rahukal[]" value="<?php if(isset($result)){
                                                                                                             $rahukal = explode(' TO ', $result[0]->rahukal);
                                                                                                             echo $rahukal[0];
                                                                                                             }else{
                                                                                                                 echo '';
                                                                                                                 } ?>" class="form-control timepicker"/>
                                                        <div class="input-group-addon">
                                                            <i class="fa fa-clock-o"></i>
                                                        </div>
                                                    </div><!-- /.input group -->
                                                </div><!-- /.form group -->

                                            </div>
                                        </div>
                                        <div class="col-lg-1"><br><label>To</label></div>
                                        <div class="col-lg-6">
                                            <!-- time Picker -->
                                            <div class="bootstrap-timepicker">

                                                <div class="form-group">
                                                    <label></label>
                                                    <div class="input-group">                                            
                                                        <input type="text" name="rahukal[]" value="<?php if(isset($result)){
                                                                                                             $rahukal = explode(' TO ', $result[0]->rahukal);
                                                                                                             echo $rahukal[1];
                                                                                                             }else{
                                                                                                                 echo '';
                                                                                                                 } ?>" class="form-control timepicker"/>
                                                        <div class="input-group-addon">
                                                            <i class="fa fa-clock-o"></i>
                                                        </div>
                                                    </div><!-- /.input group -->
                                                </div><!-- /.form group -->

                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-1">
                                            <input class="btn btn-success" type="submit" name="submit" value="Submit" />
                                        </div>
                                        <div class="col-lg-1">
                                            <a href="<?=  base_url().'punchang' ?>" class="btn btn-warning">Cancel</a>
                                        </div>
                                    </div>
                                </form>
                            </div><!-- /.box-body -->
                        </div><!-- /.box -->	
                        <!-- Pagination end -->
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div>
            </div>
            </div>
        </section><!-- /.content -->
    </aside><!-- /.right-side -->
</div><!-- ./wrapper -->

<script>
    $("#msg").fadeOut(3500);
    $('#submitcsv')
            .submit(function (e) {
                $("#load").html('loading.....')
                $.ajax({
                    url: $('#submitcsv').attr('action'),
                    type: 'POST',
                    data: new FormData(this),
                    processData: false,
                    contentType: false
                }).done(function (data) {
                    console.log(data);
                    var res = JSON.parse(data);
                    if (res.result == 'success') {
                        $("#load").html('');
                        var msg = "<div class=\"alert alert-success alert-dismissable\"><i class=\"fa fa-check\"></i><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">×</button><b>Alert!</b> Record Successfully Added</div>";
                        $("#msg").html(msg);
                        $("#msg").fadeIn();
                        $("#msg").fadeOut(3500);
                        window.setTimeout(function () {
                            window.location.href = "<?php echo base_url() . 'punchang'; ?>";
                        }, 2000);
                    } else {
                        $("#load").html('');
                        var msg = "<div class=\"alert alert-danger alert-dismissable\"><i class=\"fa fa-ban\"></i><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">×</button><b>Alert!</b> Please Select Valid CSV File</div>";
                        $("#msg").html(msg);
                        $("#msg").fadeIn();
                        $("#msg").fadeOut(3500);
                    }
                });
                e.preventDefault();
            });
</script>