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
            <h1><?php echo $welcome->loadPo('Pages'); ?><small><?php echo $welcome->loadPo('Control panel'); ?></small></h1>
            <ol class="breadcrumb">
                <li><a href="<?php echo base_url(); ?>"><i class="fa fa-dashboard"></i><?php echo $welcome->loadPo('Dashboard') ?></a></li>
                <li><a href="<?php echo base_url() . $uri ?>"><i class="fa fa-laptop"></i><?php echo $welcome->loadPo('Pages') ?></a></li>
                <li class="active"><?php if(isset($result['id'])&&($result['id']!='')){ $skin='Edit';echo $welcome->loadPo('Edit Page');}else{$skin='Add';echo $welcome->loadPo('Add Page');} ?></li>
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
                                <h3 class="box-title"><?php echo $welcome->loadPo('Page') . ' ' . $welcome->loadPo($skin); ?></h3>
                                <div class="box-tools pull-right">
                                    <a href="<?php echo base_url() . $uri; ?>" class="btn btn-default btn-sm"><?php echo $welcome->loadPo('Back'); ?></a>
                                </div>
                            </div><!-- /.box-header -->
                            <link href="<?php echo base_url(); ?>assets/css/colorpicker/bootstrap-colorpicker.min.css" rel="stylesheet" type="text/css" />
                            <!-- form start -->
                            <form action="" id="CategoryForm" method="post" accept-charset="utf-8" enctype="multipart/form-data" onsubmit="return validate();">
                                <div style="display:none;"><input type="hidden" name="_method" value="POST"/></div>
                                <input type="hidden" name="data[Category][id]" id="CategoryId"/>
                                <div class="box-body">
                                    <div class="row">
                                        <div class="form-group col-lg-5">
                                            <div class="input text">
                                                <label for="Title"><?php echo $welcome->loadPo('Title'); ?></label>
                                                <input name="page_title" value="<?php if(isset($result['page_title'])&&($result['page_title']!='')){echo $result['page_title'];}else{echo set_value('title');} ?>" class="form-control" required placeholder="<?php echo $welcome->loadPo('Title'); ?>" maxlength="255" type="text" id="title" />
                                                <span id="error_title" class="text-danger"></span>
                                            </div>
                                            
                                        </div>
                                    </div>                                                                    
                                
                                    <div class="row">
                                        <div class="form-group col-lg-5">
                                            <label for="Description"><?php echo $welcome->loadPo('Description'); ?></label>
                                            <textarea name="page_description" class="form-control" placeholder="<?php echo $welcome->loadPo('Description'); ?>" id="Description"><?php if (isset($_POST['page_description'])) {
                                                    echo $_POST['page_description'];
                                                }else if(isset($result['page_description'])&&($result['page_description']!='')){echo $result['page_description'];} ?></textarea>
                                                <span id="error_Description" class="text-danger"></span>
                                        </div>
                                    </div>
                                    
                                    
                                     
                                <div class="row">    
                                        <div class="form-group col-lg-5">
                                            <label for="Status"><?php echo $welcome->loadPo('Status'); ?>
                                                <input type="hidden" name="status" id="status" value="0"/>
                                                <span align="left"><input type="checkbox" name="status" value="1" <?php if(isset($result['status'])&&($result['status']=='1')){echo "checked='checked'";}?> /></span></label>
                                            <span id="error_status" class="text-danger"></span>
                                        </div>
                                    </div>   
                                </div><!-- /.box-body -->
                                <div class="box-footer">
                                    <button type="submit" name="submit" value="<?php if(isset($result['id'])&&($result['id']!='')){echo 'Update';}else{echo 'Submit';}?>" class="btn btn-primary"><?php echo $welcome->loadPo('Submit'); ?></button>
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
<script>
    function validate(){
     return true;
    }
    
</script>