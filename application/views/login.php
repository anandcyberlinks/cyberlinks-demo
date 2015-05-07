<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>MultitvFinal | Log in</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <!-- bootstrap 3.0.2 -->
        <link href="<?php echo base_url() ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <!-- font Awesome -->
        <link href="<?php echo base_url() ?>assets/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <!-- Theme style -->
        <link href="<?php echo base_url() ?>assets/css/AdminLTE.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url() ?>assets/css/iCheck/square/blue.css" rel="stylesheet" type="text/css" />
    </head>
    <body class="login-page">
        <div class="login-box" id="login-box">
            <?php
            $msg = $this->session->flashdata('msg');
            $succ = $this->session->flashdata('succ');
            echo $success = $this->session->flashdata('message');
            if ($msg != '') {
                ?>
                <div class="alert alert-danger alert-dismissable">
                    <i class="fa fa-ban"></i>
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php echo $msg; ?>
                </div>
            <?php
            }
            if ($succ != '') {
                ?>
                <div class="alert alert-success alert-dismissable">
                    <i class="fa fa-check"></i>
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <?php echo $succ; ?>
                </div>
           <?php } ?>
            <div class="login-logo">
                <b>Multi</b>TV
            </div><!-- /.login-logo -->
            <div class="login-box-body">
                <p class="login-box-msg">Sign in to start your session</p>
                <form action="" method="post">
                    
                    <div class="form-group has-feedback">
                        <input type="text" name="username" value="<?= (isset($_COOKIE['user'])) ? $_COOKIE['user'] : ""; ?>" class="form-control" placeholder="User Name"/>
                        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                    </div>
                    <div class="form-group has-feedback">
                        <input type="password" name="password" value="<?= (isset($_COOKIE['password'])) ? $_COOKIE['password'] : ""; ?>" class="form-control" placeholder="Password"/>
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>

                    </div>
                    
                    <div class="row">
                        <div class="col-xs-8">    
                          <div class="checkbox icheck">
                            <label>
                              <input type="checkbox" name="remember_me" value="remember" <?= (isset($_COOKIE['remember'])) ? 'checked' : ""; ?> /> Remember me
                            </label>
                          </div>                        
                        </div><!-- /.col -->
                        <div class="col-xs-4">
                            <button type="submit" class="btn btn-primary btn-block btn-flat" name="login">Sign In</button>
                        </div><!-- /.col -->
                    </div>
                </form>
                
                <a href="<?=  base_url().'layout/forgot'?>">Forgot Password</a><br>
                <a href="<?= base_url() . 'layout/register' ?>" class="text-center">Register a membership</a>
            </div>
        </div>
        <!-- jQuery 2.0.2 -->
        <!--script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script-->
        <script src="<?php echo base_url() ?>assets/js/jQuery-2.1.3.min.js"></script>
        <!-- Bootstrap -->
        <script src="<?php echo base_url() ?>assets/js/bootstrap.min.js" type="text/javascript"></script>        
        <script src="<?php echo base_url() ?>assets/js/icheck.min.js" type="text/javascript"></script>        
        <script>
        $(function () {
          $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
          });
        });
      </script>
    </body>
</html>