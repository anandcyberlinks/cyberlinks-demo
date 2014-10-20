<style>
    .error{
        color: red;
    }
    div#video{
        box-shadow: 10px 10px 10px black;
        border: 1px solid;
    }
</style>
<aside class="right-side">    
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo $welcome->loadPo('Create Package') ?>
            <small><?php echo $welcome->loadPo('New Package') ?></small>
            </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>"><i class="fa fa-dashboard"></i><?php echo $welcome->loadPo('Dashboard') ?></a></li>
            <li><a href="<?php echo base_url() ?>package"><i class="fa fa-fw fa-minus-circle"></i><?php echo $welcome->loadPo('Package') ?></a></li>
            <li class="active"><?php echo $welcome->loadPo('Create Package') ?></li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title"><?php echo $welcome->loadPo('New Package') ?></h3>                                    
                    </div><!-- /.box-header -->
                    <div class="box-body table-responsive">
                        <form action="" method="POST" id="registerId">
                        <label><?php echo $welcome->loadPo('Package Name') ?></label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-fw fa-bolt"></i>
                            </div>
                            <input type="text" name="package_name" placeholder="<?php echo $welcome->loadPo('Package Name') ?>" class="form-control">
                        </div>
                        <label for="package_name" generated="true" class="error"></label><br>
                        <input type="radio" name="package_type" class="package_type" value="free">Free
                        <input type="radio" name="package_type" class="package_type" value="paid" checked>Paid
                            <br> <br>
                        <input type="submit" name="submit"value="<?php echo $welcome->loadPo('Submit') ?>" class="btn btn-success">&nbsp;
                        <a class="btn btn-warning" href="<?php echo base_url() ?>package"><i class="fa fa-fw fa-mail-reply"></i><?php echo $welcome->loadPo('Cancel') ?></a>
                        </form>
                    </div>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div><!-- /.box -->
    </section><!-- /.content -->
</aside>


