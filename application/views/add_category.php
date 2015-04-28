<style>
    .error{
        color: red;
    }
</style>
<?php $uri = $this->uri->segment(1); ?>
<div class="wrapper row-offcanvas row-offcanvas-left">
    <!-- Right side column. Contains the navbar and content of the page -->
    <aside class="right-side">                
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1><?php echo $welcome->loadPo('Category'); ?><small><?php echo $welcome->loadPo('Control panel'); ?></small></h1>
            <ol class="breadcrumb">
                <li><a href="<?php echo base_url(); ?>"><i class="fa fa-dashboard"></i><?php echo $welcome->loadPo('Dashboard') ?></a></li>
                <li><a href="<?php echo base_url() . $uri ?>"><i class="fa fa-laptop"></i><?php echo $welcome->loadPo('Category') ?></a></li>
                <li class="active"><?php echo $welcome->loadPo('Add Category') ?></li>
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
                                <h3 class="box-title"><?php echo $welcome->loadPo('Category') . ' ' . $welcome->loadPo('Add'); ?></h3>
                               
                            </div><!-- /.box-header -->
                            <link href="<?php echo base_url(); ?>assets/css/colorpicker/bootstrap-colorpicker.min.css" rel="stylesheet" type="text/css" />
                            <!-- form start -->
                            <form action="" id="CategoryForm" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                                <div style="display:none;"><input type="hidden" name="_method" value="POST"/></div>
                                <input type="hidden" name="data[Category][id]" id="CategoryId"/>
                                <div class="box-body">
                                    <div class="row">
                                        <div class="form-group col-lg-5">
                                            <div class="input text">
                                                <label for="Category"><?php echo $welcome->loadPo('Category') . ' ' . $welcome->loadPo('Name'); ?></label>
                                                <input name="category" class="form-control" placeholder="<?php echo $welcome->loadPo('Category') . ' ' . $welcome->loadPo('Name'); ?>" maxlength="255" type="text" id="Category" value="<?php echo set_value('category'); ?>" onblur="category_check(this.value);" />
                                                <?php echo form_error('category', '<span class="text-danger">', '</span>'); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php if ($uri == 'ch_category') { ?>
                                        <div class="row">
                                            <div class="form-group col-lg-5">
                                                <div class="input text">
                                                    <label for="range_from"><?php echo $welcome->loadPo('Range') . ' ' . $welcome->loadPo('From'); ?></label>
                                                    <input name="range_from" class="form-control" placeholder="<?php echo $welcome->loadPo('Range') . ' ' . $welcome->loadPo('From'); ?>" maxlength="255" type="text" id="range_from" value="<?php echo set_value('range_from'); ?>" />
                                                    <?php echo form_error('range_from', '<span class="text-danger">', '</span>'); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-lg-5">
                                                <div class="input text">
                                                    <label for="range_to"><?php echo $welcome->loadPo('Range') . ' ' . $welcome->loadPo('To'); ?></label>
                                                    <input name="range_to" class="form-control" placeholder="<?php echo $welcome->loadPo('Range') . ' ' . $welcome->loadPo('To'); ?>" maxlength="255" type="text" id="range_to" value="<?php echo set_value('range_to'); ?>" />
                                                    <?php echo form_error('range_to', '<span class="text-danger">', '</span>'); ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <div class="row">
                                        <div class="form-group col-lg-5">
                                            <div class="input select">
                                                <label for="searchParentId"><?php echo $welcome->loadPo('Parent') . ' ' . $welcome->loadPo('Category');
                                    echo set_value('parent_id'); ?> </label>
                                                <select name="parent_id" class="form-control" placeholder="Parent Category" id="searchParentId">
                                                    <option value="">--<?php echo $welcome->loadPo('Select'); ?>--</option>
                                                    <?php foreach ($allParentCategory as $cat) { ?>
                                                        <option value="<?php echo $cat->id; ?>" <?php if ($cat->id == set_value('parent_id')) {
                                                        echo 'selected="selected"';
                                                    } ?>><?php echo $cat->category; ?></option>
                                              <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-5">
                                            <label for="type"><?php echo $welcome->loadPo('Type'); ?></label>
                                            <select class="form-control" name="type">
                                                <option value="video">Video</option>
                                                <option value="audio">Audio</option>
                                            </select>
                                                    <?php echo form_error('description', '<span class="text-danger">', '</span>'); ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-5">
                                            <label for="Description"><?php echo $welcome->loadPo('Description'); ?></label>
                                            <textarea name="description" class="form-control" placeholder="<?php echo $welcome->loadPo('Description'); ?>" id="Description"><?php if (isset($_POST['description'])) {
                                                    echo $_POST['description'];
                                                } ?></textarea>
                                                <?php echo form_error('description', '<span class="text-danger">', '</span>'); ?>
                                        </div>
                                    </div>
                                    <div class="row"> 
                                        <div class="form-group col-lg-5">
                                            <label for="categoryColor"><?php echo $welcome->loadPo('Color'); ?></label>
                                            <div class="input-group my-colorpicker">                                            
                                                <div class="input text">
                                                    <input name="color" class="form-control" placeholder="<?php echo $welcome->loadPo('Color'); ?>" type="text" value="#31859b" id="color"/>
                                                </div>                                                
                                                <div class="input-group-addon">
                                                    <i></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row"> 
                                        <div class="form-group col-lg-2">
                                            <label for="categoryImage"><?php echo $welcome->loadPo('Image'); ?></label>&nbsp;&nbsp;<br>
                                            <span class="btn btn-default btn-file btn-sm">
                                             <?php echo $welcome->loadPo('Choose Media') ?> <input name="categoryImage"  id="categoryImage"  atr="files" type="file"/>
                                            </span>
                                        </div>
                                        <div class="form-group col-lg-3">
                                            <label for="Status"><?php echo $welcome->loadPo('Status'); ?></label>
                                                <select name="status" class="form-control">
                                                    <option value="0">Inactive</option>
                                                    <option value="1">Active</option>
                                                </select>

                                        </div>
                                    </div>
                                    <div class="row">    
                                        
                                    </div>
                                </div><!-- /.box-body -->
                                <div class="box-footer">
                                    <button type="submit" name="submit" value="Submit" class="btn btn-primary"><?php echo $welcome->loadPo('Submit'); ?></button>
                                    <a href="<?php echo base_url() . $uri; ?>" class="btn btn-default"><?php echo $welcome->loadPo('Cancel'); ?></a>
                                </div>
                            </form>
                            <script src="<?php echo base_url(); ?>assets/js/plugins/colorpicker/bootstrap-colorpicker.min.js" type="text/javascript"></script>
                            <script>
                                                    $(function () {
                                                        //Colorpicker
                                                        $('.my-colorpicker').colorpicker();
                                                    });
                            </script>
                        </div><!-- /.box -->
                    </div><!--/.col (left) -->
                </div>
            </div>
        </section><!-- /.content -->
    </aside><!-- /.right-side -->
</div><!-- ./wrapper -->
