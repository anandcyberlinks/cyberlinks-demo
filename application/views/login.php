<!DOCTYPE html>
<html class="bg-black">
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
    </head>
    <body class="bg-black">
        <div class="form-box" id="login-box">
            <?php
            $msg = $this->session->flashdata('msg');
            $succ = $this->session->flashdata('succ');
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
            <div class="header">Sign In</div>
            <form action="" method="post">
                <div class="body bg-gray">
                    <div class="form-group">
                        <input type="text" name="username" value="<?= (isset($_COOKIE['user'])) ? $_COOKIE['user'] : ""; ?>" class="form-control" placeholder="User Name"/>
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" value="<?= (isset($_COOKIE['password'])) ? $_COOKIE['password'] : ""; ?>" class="form-control" placeholder="Password"/>
                    </div>          
                    <div class="form-group">
                        <input type="checkbox" name="remember_me" value="remember" <?= (isset($_COOKIE['remember'])) ? 'checked' : ""; ?> /> Remember me
                    </div>
                </div>
                <div class="footer">                                                               
                    <button type="submit" class="btn bg-olive btn-block" name="login">Sign me in</button>  
                    <a href="<?= base_url() . 'layout/register' ?>" class="text-center">Register a membership</a>
                </div>
            </form>
        </div>
        <!-- jQuery 2.0.2 -->
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
        <!-- Bootstrap -->
        <script src="<?php echo base_url() ?>assets/js/bootstrap.min.js" type="text/javascript"></script>        
    </body>
</html>