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
            <h1><?php echo $welcome->loadPo('Add Playlist'); ?><small><?php echo $welcome->loadPo('Control panel'); ?></small></h1>
            <ol class="breadcrumb">
                <li><a href="<?php echo base_url(); ?>"><i class="fa fa-dashboard"></i><?php echo $welcome->loadPo('Dashboard') ?></a></li>
                <li><a href="<?php echo base_url() ?>event"><i class="fa fa-laptop"></i><?php echo $welcome->loadPo('WebTV') ?></a></li>
                <li class="active"><?php echo $welcome->loadPo('Add Playlist') ?></li>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content">                
            <div id="content">
                <div class="row">
                    <!-- left column -->
                    <div class="col-md-12">
                        <!-- general form elements -->
                        <div class="box box-primary">
                            <div class="box-header">
                                <?php if(!isset($value)){ ?>
                                <h3 class="box-title"><?php echo $welcome->loadPo('Add') . ' ' . $welcome->loadPo('Playlist'); ?></h3>
                                <?php } else { ?>
                                <h3 class="box-title"><?php echo $welcome->loadPo('Edit') . ' ' . $welcome->loadPo('Playlist'); ?></h3>
                                <?php } ?>
                                <div class="box-tools pull-right">
                                    <a href="<?php echo base_url(); ?>webtv" class="btn btn-default btn-sm"><?php echo $welcome->loadPo('Back'); ?></a>
                                </div>
                            </div><!-- /.box-header -->
                            <div class="box-body">
                                <form id="registerId" action="" method="post">
                                    <div class="row">
                                        <div class="form-group col-md-10">
                                            <label><?php echo $welcome->loadPo('Playlist Name'); ?></label>
                                            <input class="form-control" type="text" name="name" value="<?php if(isset($value)){ echo $value[0]->name; }?>">
                                        </div>
                                        <div class="form-group col-md-10">
                                            <label><?php echo $welcome->loadPo('Description'); ?></label>
                                            <textarea class="form-control" name="description"><?php if(isset($value)){ echo $value[0]->description; }?></textarea>
                                        </div>
                                        <div class="form-group col-md-10">
                                            <label><?php echo $welcome->loadPo('Time'); ?></label>
                                            <input id="reservationtime" class="form-control" type="text" name="start_date" value="<?php if(isset($value)){ echo str_replace('-', '/', $value[0]->start_date)." - ".str_replace('-','/',$value[0]->end_date); }?>" readonly="readonly">
                                        </div>
                                        <div class="form-group col-md-10">
                                            <label><?php echo $welcome->loadPo('Status'); ?></label>
                                            <input type="hidden" name="status" value="0">
                                            <input type="checkbox" name="status" value="1" <?php if(isset($value)){ if($value[0]->status == '1'){echo "checked";} }?>>
                                        </div>
                                        <div class="form-group col-md-5">
                                            <input type="submit" name="submit" value="Submit" class="btn btn-success">
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div><!-- /.box -->
                    </div><!--/.col (left) -->
                </div>
            </div>
        </section><!-- /.content -->
    </aside><!-- /.right-side -->
</div><!-- ./wrapper -->
