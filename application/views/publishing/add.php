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
            <h1><?php echo $welcome->loadPo('Skins'); ?><small><?php echo $welcome->loadPo('Control panel'); ?></small></h1>
            <ol class="breadcrumb">
                <li><a href="<?php echo base_url(); ?>"><i class="fa fa-dashboard"></i><?php echo $welcome->loadPo('Dashboard') ?></a></li>
                <li><a href="<?php echo base_url() . $uri ?>"><i class="fa fa-laptop"></i><?php echo $welcome->loadPo('Skin') ?></a></li>
                <li class="active"><?php echo $welcome->loadPo('Add Skin') ?></li>
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
                                <h3 class="box-title"><?php echo $welcome->loadPo('Skin') . ' ' . $welcome->loadPo('Add'); ?></h3>
                                <div class="box-tools pull-right">
                                    <a href="<?php echo base_url() . $uri; ?>" class="btn btn-default btn-sm"><?php echo $welcome->loadPo('Back'); ?></a>
                                </div>
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
                                                <label for="Title"><?php echo $welcome->loadPo('Title'); ?></label>
                                                <input name="title" class="form-control" placeholder="<?php echo $welcome->loadPo('Title'); ?>" maxlength="255" type="text" id="title" value="<?php echo set_value('title'); ?>" />
                                                <?php echo form_error('title', '<span class="text-danger">', '</span>'); ?>
                                            </div>
                                        </div>
                                    </div>                                                                    
                                
                                    <div class="row">
                                        <div class="form-group col-lg-5">
                                            <label for="Description"><?php echo $welcome->loadPo('Description'); ?></label>
                                            <textarea name="description" class="form-control" placeholder="<?php echo $welcome->loadPo('Description'); ?>" id="Description"><?php if (isset($_POST['description'])) {
                                                    echo $_POST['description'];
                                                } ?></textarea>                                                
                                        </div>
                                    </div>
                                    <div class="row"> 
                                        <div class="form-group col-lg-5">
                                            <label for="Dimension"><?php echo $welcome->loadPo('Dimensions'); ?></label>
                                            <div class="input-group">                                            
                                                <div class="input text">
                                                    <input name="dimension" class="form-control" placeholder="<?php echo $welcome->loadPo('Dimension'); ?>" type="text" value="" id="dimension"/>
                                               <?php echo form_error('dimension', '<span class="text-danger">', '</span>'); ?>
                                                </div>                                                
                                               
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row"> 
                                        <div class="form-group col-lg-5">
                                            <label for="Image"><?php echo $welcome->loadPo('Image'); ?></label>&nbsp;&nbsp;
                                            <span class="btn btn-default btn-file btn-sm">
                                             <?php echo $welcome->loadPo('Choose Media') ?> <input name="image_file"  id="image_file"  atr="files" type="file"/>
                                            </span>
                                            <?php echo form_error('image', '<span class="text-danger">', '</span>'); ?>
                                        </div>
                                    </div>
                                    
                                     <div class="row"> 
                                        <div class="form-group col-lg-5">
                                            <label for="Skin"><?php echo $welcome->loadPo('Skin'); ?></label>&nbsp;&nbsp;
                                            <span class="btn btn-default btn-file btn-sm">
                                             <?php echo $welcome->loadPo('Choose File') ?> <input name="skin_file"  id="skin_file"  atr="files" type="file"/>
                                            </span>
                                            <?php echo form_error('skin', '<span class="text-danger">', '</span>'); ?>
                                        </div>
                                    </div>
                                     
                                    <div class="row">    
                                        <div class="form-group col-lg-5">
                                            <label for="Status"><?php echo $welcome->loadPo('Status'); ?>
                                                <input type="hidden" name="status" id="status" value="0"/>
                                                <span align="left"><input type="checkbox" name="status" value="1"/></span></label>
                                            <?php echo form_error('status', '<span class="text-danger">', '</span>'); ?>
                                        </div>
                                    </div>
                                </div><!-- /.box-body -->
                                <div class="box-footer">
                                    <button type="submit" name="submit" value="Submit" class="btn btn-primary"><?php echo $welcome->loadPo('Submit'); ?></button>
                                    <a href="<?php echo base_url() . $uri; ?>" class="btn btn-default"><?php echo $welcome->loadPo('Cancel'); ?></a>
                                </div>
                            </form>                            
                        </div><!-- /.box -->
                    </div><!--/.col (left) -->
                </div>
            </div>
        </section><!-- /.content -->
    </aside><!-- /.right-side -->
</div><!-- ./wrapper -->
