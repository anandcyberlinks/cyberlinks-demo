<style>
    .error{
        color: red;
    }
</style>
<aside class="right-side">    
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo $welcome->loadPo('Edit Genre') ?>
            <small><?php echo $welcome->loadPo('Edit Genre') ?></small>
            </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>"><i class="fa fa-dashboard"></i><?php echo $welcome->loadPo('Dashboard') ?></a></li>
            <li><a href="<?php echo base_url() ?>genre"><i class="fa fa-fw fa-minus-circle"></i><?php echo $welcome->loadPo('Genre') ?></a></li>
            <li class="active"><?php echo $welcome->loadPo('Edit Genre') ?></li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title"><?php echo $welcome->loadPo('Edit Genre') ?></h3>                                    
                    </div><!-- /.box-header -->
                    <div class="box-body table-responsive">
                        <form action="" method="POST">
                        <label><?php echo $welcome->loadPo('Genre Name') ?></label>
                        <div class="input-group">
                            
                            <input type="text" name="genre_name" value="<?php echo $genreName[0]->genre_name; ?>" class="form-control">
                            <?php echo form_error('genre_name','<span class="text-danger">','</span>'); ?>
                        </div><label for="genre_name" generated="true" class="error"></label><br>
                        <input type="submit" name="update"value="<?php echo $welcome->loadPo('Update') ?>" class="btn btn-success">&nbsp;
                        <a class="btn btn-warning" href="<?php echo base_url() ?>genre"><i class="fa fa-fw fa-mail-reply"></i><?php echo $welcome->loadPo('Cancel') ?></a>
                        </form>
                    </div>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div><!-- /.box -->
    </section><!-- /.content -->
</aside>               

