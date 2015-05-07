<!DOCTYPE html>
<style>
    .error{
        color: red;
    }
</style>
<html>
    <head>
        <meta charset="UTF-8">
        <title>MultiTV | Reset Password</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <!-- bootstrap 3.0.2 -->
        <link href="<?php echo base_url() ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <!-- font Awesome -->
        <link href="<?php echo base_url() ?>assets/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <!-- Theme style -->
        <link href="<?php echo base_url() ?>assets/css/AdminLTE.css" rel="stylesheet" type="text/css" />

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
    </head>
    <body class="login-page">
         <div class="login-box" id="login-box">
            <?php $msg = $this->session->flashdata('msg');
                   $succ = $this->session->flashdata('succ');
            if($msg!=''){ ?>
                <div class="alert alert-danger alert-dismissable">
                    <i class="fa fa-ban"></i>
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <b>Alert!</b> <?php echo $msg; ?>
                </div>
            <?php }
             if($msg!=''){ ?>
                <div class="alert alert-danger alert-dismissable">
                    <i class="fa fa-ban"></i>
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <b>Alert!</b> <?php echo $msg; ?>
                </div>
             <?php } ?>
             <div class="login-logo">
                <b>Multi</b>TV
            </div><!-- /.login-logo -->
          
          <div class="login-box-body">
            <p class="login-box-msg">Create Your New Password</p>
            <form action="" method="post" id="reset_form">
                  
                    <div class="form-group has-feedback">
                        <input type="password" name="password" class="form-control" placeholder="New Password" required="true"/>
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                        <input type="hidden" name="id" value="<?php echo $id;?>">
                    </div>
                    <div class="form-group has-feedback">
                        <input type="password" name="password2" id="password" class="form-control" placeholder="Confirm Password" required="true"/>
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    </div>
                  
                    <div class="row">
                        <div class="col-xs-4">
                            <button type="submit" class="btn btn-primary btn-block btn-flat" name="login">Submit</button>
                        </div><!-- /.col -->
                    </div>
              </form>
          </div>
        </div>


        <!-- jQuery 2.0.2 -->
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
        <script src="<?php echo base_url() ?>assets/js/jquery.validate.js" type="text/javascript"></script>
	<script src="<?php echo base_url() ?>assets/js/register.jquery.js" type="text/javascript"></script>
        <script src="http://code.jquery.com/jquery-migrate-1.0.0.js"></script>
        <!-- Bootstrap -->
        <script src="<?php echo base_url() ?>assets/js/bootstrap.min.js" type="text/javascript"></script>        

    </body>
</html>