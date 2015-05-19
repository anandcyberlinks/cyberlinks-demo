<?php $uri = $this->uri->segment(1); ?>
<!--div class="wrapper row-offcanvas row-offcanvas-left"-->
    <!-- Right side column. Contains the navbar and content of the page -->
    <aside class="content-wrapper">                
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1><?php echo $welcome->loadPo('Application'); ?> <small><?php echo $welcome->loadPo('Registration'); ?></small></h1>
            <!--ol class="breadcrumb">
                <li><a href="<?php echo base_url(); ?>"><i class="fa fa-dashboard"></i><?php echo $welcome->loadPo('Dashboard') ?></a></li>
                <li><a href="<?php echo base_url() . $uri; ?>"><i class="fa fa-dashboard"></i><?php echo $welcome->loadPo('Category') ?></a></li>
                <li class="active"><?php echo $welcome->loadPo('Edit') . " " . $welcome->loadPo('Category') ?></li>
            </ol-->
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
                                <h3 class="box-title"><?php echo $welcome->loadPo('Application') . ' ' . $welcome->loadPo('Information'); ?></h3>
                            </div><!-- /.box-header -->
                            <!-- form start -->
                                <form action="<?php echo base_url(); ?>applications/registration" id="applicationRegistrationForm" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                                    <input type="hidden" name="appOldFileId" id="appOldFileId" value="<?php echo @$edit_app->app_icon; ?>" />
                                    <input type="hidden" name="edit_id" id="edit_id" value="<?php echo @$edit_app->id; ?>" />
                                    <div class="box-body">
                                        <div class="row">
                                            <div class="form-group col-lg-5">
                                                <div class="input text">
                                                    <label for="Application"><?php echo $welcome->loadPo('Application') . ' ' . $welcome->loadPo('Name'); ?></label>
                                                    <input name="application_name" class="form-control" placeholder="Application Name" maxlength="255" type="text" id="application_name" value="<?php echo @$edit_app->app_name; ?>"/>
                                                    <?php echo form_error('application_name', '<span class="text-danger">', '</span>'); ?>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group col-lg-5">
                                                <div class="input select">
                                                    <label for="applicationCategory"><?php echo $welcome->loadPo('Category'); ?></label>
                                                    <input name="application_category" class="form-control" placeholder="Category" maxlength="255" type="text" id="application_category" value="<?php echo @$edit_app->app_category; ?>"/>
                                                    <?php echo form_error('application_category', '<span class="text-danger">', '</span>'); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-5">
                                                <label for="timezone"><?php echo $welcome->loadPo('Timezone'); ?></label>
                                                <select class="form-control timezone" name='timezone' id="timezone"> 
                                                  <option value="0">Select a Time-Zone</option>
                                                  <?php foreach($time_zones as $key=>$val){?>
                                                  <option value="<?php echo $key;?>" <?php echo ($key == @$edit_app->timezone) ? "selected='selected'" : ''; ?>><?php echo $val;?></option>
                                                  <?php }?>
                                                </select>
                                            <?php echo form_error('timezone', '<span class="text-danger">', '</span>'); ?>
                                            </div>
                                         </div>
                                        
                                        <div class="row"> 
                                            <div class="form-group col-lg-5">
                                                <div class="input text">
                                                    <label for="version"><?php echo $welcome->loadPo('Version'); ?></label>
                                                    <input name="application_version" class="form-control" placeholder="Version" maxlength="255" type="text" id="application_version" value="<?php echo @$edit_app->app_version; ?>"/>
                                                    <?php echo form_error('application_version', '<span class="text-danger">', '</span>'); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row"> 
                                            <div class="form-group col-lg-5">
                                                <label for="applicationLogo"><?php echo $welcome->loadPo('Logo'); ?></label>&nbsp;&nbsp;
                                                <span class="btn btn-default btn-file btn-sm">
                                                    <?php echo $welcome->loadPo('Choose Media') ?> <input name="applicationLogo"  id="applicationLogo"  atr="files" type="file"/>
                                                </span>
                                            </div>
                                        </div>
                                        <?php if ((isset($edit_app->filename)) && (@$edit_app->filename != '')) { ?>
                                            <div class="row">
                                                <div class="form-group col-lg-5">
                                                    <img src="<?php echo baseurl . APPLICATIONS_SMALL_PATH . $edit_app->filename; ?>"  />
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div><!-- /.box-body -->
                                    <div class="box-footer">
                                        <button type="submit" name="submit" value="Save" class="btn btn-primary"><?php echo $welcome->loadPo('Save'); ?></button>
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
<!--/div--><!-- ./wrapper -->
