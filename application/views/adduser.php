<style>
    .error
    {
      color: red;  
    }
</style>
<!-- Right side column. Contains the navbar and content of the page -->
<aside class="right-side">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo $welcome->loadpo('New User Registration') ?>
            <small><?php echo $welcome->loadpo('Registration Form') ?></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url() ?>"><i class="fa fa-dashboard"></i><?php echo $welcome->loadPo('Dashboard') ?></a></li>
            <li><a href="<?php echo base_url() ?>user"><i class="fa fa-user"></i><?php echo $welcome->loadPo('Users') ?></a></li>
            <li class="active"><?php echo $welcome->loadPo('Add New'); ?></li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-danger">
                    <div class="box-header">
                        <h3 class="box-title"><?php echo $welcome->loadpo('Registration Form') ?></h3>
                    </div>
                    <div class="box-body">
                        <form action="" method="post" id="registerId">
                            <input type="hidden" name="owner_id" value="<?php echo $owner_id ?>">
                            <div class="row">
                                <div class="col-xs-6">
                                    <label><?php echo $welcome->loadPo('First Name') ?>:</label>
                                    <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-fw fa-user"></i>
                                    </div>
                                    <input type="text" name="first_name" class="form-control" placeholder="<?php echo $welcome->loadPo('First Name') ?>">
                                </div>
                                    <label for="first_name" generated="true" class="error" style="display: none"></label>
                                </div>
                                <div class="col-xs-6">
                                    <label><?php echo $welcome->loadPo('Last Name') ?>:</label>
                                    <input type="test" name="last_name" class="form-control" placeholder="<?php echo $welcome->loadPo('Last Name') ?>">
                                </div>
                            </div><br>
                            <!-- Date mm/dd/yyyy -->
                            <div class="form-group">
                                <label><?php echo $welcome->loadPo('Username') ?>:</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-fw fa-user"></i>
                                    </div>
                                    <input type="text" name="username" placeholder="<?php echo $welcome->loadPo('Username') ?>" class="form-control"/>
                                </div><!-- /.input group -->
                                <label for="username" generated="true" class="error" style="display: none"></label>
                            </div><!-- /.form group -->
                            <div class="form-group">
                                <label><?php echo $welcome->loadPo('Email') ?>:</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-fw fa-envelope-o"></i>
                                    </div>
                                    <input type="text" name="email" placeholder="<?php echo $welcome->loadPo('Email') ?>" class="form-control"/>
                                </div><!-- /.input group -->
                                <label for="email" generated="true" class="error" style="display: none"></label>
                            </div><!-- /.form group -->
                            <div class="row">
                                <div class="col-xs-6">
                                    <label><?php echo $welcome->loadPo('Password') ?>:</label>
                                     <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-fw fa-lock "></i>
                                    </div>
                                    <input type="password" name="password" id="password" class="form-control" placeholder="<?php echo $welcome->loadPo('Password') ?>">
                                     </div>
                                     <label for="password" generated="true" class="error" style="display: none"></label>
                                </div>
                                <div class="col-xs-6">
                                    <label><?php echo $welcome->loadPo('Confirm Password') ?>:</label>
                                    <input type="password" name="cpassword" class="form-control" placeholder="<?php echo $welcome->loadPo('Confirm Password') ?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-4">
                                    <label>Gender:</label>
                                    <select name="gender" class="form-control" <?php if($userrole == 'Superadmin'){ echo 'disabled'; } ?>>
                                        <option value="">-Select-</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </div>
                                <div class="col-xs-4">
                                    <label><?php echo $welcome->loadPo('Language') ?>:</label>
                                    <select name="language" class="form-control">
                                        <option value="">-<?php echo $welcome->loadPo('Select') ?>-</option>
                                        <option value="English">English</option>
                                        <option value="Hindi">हिन�?दी</option>
                                    </select>
                                </div>
                                <div class="col-xs-4">
                                    
                                    <label><?php echo $welcome->loadPo('Role') ?>:</label>
                                    <?php if($userrole == 'Superadmin'){ ?>
                                    <input type="text" class="form-control" value="Admin" disabled >
                                    <input type="hidden" name="role_id" value="1" >   
                                    <?php } else { ?>
                                    <select name="role_id" class="form-control">
                                        <option value="">-<?php echo $welcome->loadPo('Select') ?>-</option>
                                        <?php foreach ($role as $value){ ?>
                                        <option value="<?php echo $value->id ?>"><?php echo $value->name ?></option>
                                        <?php } ?>
                                    </select>
                                    <?php } ?>
                                </div>
                            </div><br>
                            <input type="submit" name="submit" class="btn btn-success" value="<?php echo $welcome->loadPo('Submit') ?>">
                            &nbsp; &nbsp;<a href="<?php echo base_url()?>user" class="btn btn-warning"><?php echo $welcome->loadPo('Cancel') ?></a>
                        </form>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div><!-- /.col (left) -->
        </div><!-- /.row -->                    
    </section><!-- /.content -->
</aside><!-- /.right-side -->
