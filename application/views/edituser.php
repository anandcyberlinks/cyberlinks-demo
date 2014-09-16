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
            <?php echo $welcome->loadpo('Update')." ".$welcome->loadPo('User'); ?>
            <small><?php echo $welcome->loadpo('Form') ?></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url() ?>"><i class="fa fa-dashboard"></i><?php echo $welcome->loadPo('Dashboard') ?></a></li>
            <li><a href="<?php echo base_url() ?>user"><?php echo $welcome->loadPo('Users') ?></a></li>
            <li class="active"><?php echo $welcome->loadPo('Update') ?></li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-danger">
                    <div class="box-header">
                        <h3 class="box-title"><?php echo $welcome->loadpo('User')." ".$welcome->loadPo('Update')." ".$welcome->loadPo('Form'); ?></h3>
                    </div>
                    <div class="box-body">
                        <form action="" method="post" id="registerId">
                            <div class="row">
                                <?php foreach ($result as $value1) { ?>
                                <div class="col-xs-6">
                                    <label><?php echo $welcome->loadPo('First')." ".$welcome->loadPo('Name') ?>:</label>
                                    <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-fw fa-user"></i>
                                    </div>
                                        <input type="text" name="first_name" value="<?php echo $value1->first_name; ?>" class="form-control" placeholder="First Name">
                                </div>
                                    <label for="first_name" generated="true" class="error" style="display: none"></label>
                                </div>
                                <div class="col-xs-6">
                                    <label><?php echo $welcome->loadPo('Last')." ".$welcome->loadPo('Name') ?>:</label>
                                    <input type="text" name="last_name" value="<?php echo $value1->last_name; ?>" class="form-control" placeholder="Last Name">
                                </div>
                            </div><br>
                            <!-- Date mm/dd/yyyy -->
                            <div class="form-group">
                                <label><?php echo $welcome->loadPo('Choose')." ".$welcome->loadPo('Username') ?>:</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-fw fa-user"></i>
                                    </div>
                                    <input type="text" name="user_name" value="<?php echo $value1->username; ?>" placeholder="User Name" class="form-control" disabled>
                                </div><!-- /.input group -->
                                <label for="username" generated="true" class="error" style="display: none"></label>
                            </div><!-- /.form group -->
                            <div class="form-group">
                                <label><?php echo $welcome->loadPo('Email') ?>:</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-fw fa-envelope-o"></i>
                                    </div>
                                    <input type="text" name="email_1" value="<?php echo $value1->email; ?>" placeholder="Email" class="form-control" disabled>
                                </div><!-- /.input group -->
                                <label for="email" generated="true" class="error" style="display: none"></label>
                            </div><!-- /.form group -->
                            <div class="row">
                                <div class="col-xs-4">
                                    <label><?php echo $welcome->loadPo('Gender') ?>:</label>
                                    <select name="gender" class="form-control">
                                        <option value="">-Select-</option>
                                        <option value="Male" <?php if($value1->gender ==  'Male'){ echo 'selected'; } ?>>Male</option>
                                        <option value="Female" <?php if($value1->gender ==  'Female'){ echo 'selected'; } ?>>Female</option>
                                    </select>
                                </div>
                                <div class="col-xs-4">
                                    <label><?php echo $welcome->loadPo('Language') ?>:</label>
                                    <select name="language" class="form-control">
                                        <option value="">-Select-</option>
                                        <option value="English" <?php if($value1->language ==  'English'){ echo 'selected'; } ?>>English</option>
                                        <option value="Hindi" <?php if($value1->language ==  'Hindi'){ echo 'selected'; } ?>>Hindi</option>
                                    </select>
                                </div>
                                <div class="col-xs-4">
                                    
                                    <label><?php echo $welcome->loadPo('Role') ?>:</label>
                                    <?php if($userrole == 'Superadmin'){ ?>
                                    <input type="text" class="form-control" value="Admin" disabled >
                                    <input type="hidden" name="role_id" value="1" >   
                                    <?php } else { ?>
                                    <select name="role_id" class="form-control">
                                        <option value="">-Select-</option>
                                        <?php foreach ($role as $value){ ?>
                                        <option value="<?php echo $value->id ?>" <?php if($value1->role_id ==  $value->id){ echo 'selected'; } ?>><?php echo $value->name ?></option>
                                        <?php } ?>
                                    </select>
                                <?php } } ?>
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
