<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>MultitvFinal | Registration</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <!-- bootstrap 3.0.2 -->
        <style>
            .error{
                color: red
            }
        </style>
        <link href="<?php echo base_url() ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <!-- font Awesome -->
        <link href="<?php echo base_url() ?>assets/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <!-- Theme style -->
        <link href="<?php echo base_url() ?>assets/css/AdminLTE.css" rel="stylesheet" type="text/css" />
        <script src="<?php echo base_url() ?>assets/js/jQuery-2.1.3.min.js"></script>
    </head>
    <body class="register-page">
        <div class="register-box">
        <div class="form-box" id="login-box">
            <div class="register-logo">
                <b>Multi</b>TV
            </div>
            <div class="register-box-body">
            <p class="login-box-msg">Register a new membership</p>
            <form action="" method="post" id="register_form">
                
                    <div class="form-group has-feedback">
                        <div class="row">
                            <div class="col-xs-6">
                                <input type="text" name="first_name" class="form-control" placeholder="First Name"/>
                            </div>
                            <div class="col-xs-6">
                                <input type="text" name="last_name" class="form-control" placeholder="Last Name"/>
                            </div>
                        </div>
                    </div>
                    <div class="form-group has-feedback">
                        <input type="text" name="email" class="form-control" placeholder="Email"/>
                    </div>
                    <div class="form-group has-feedback">
                        <input type="text" name="domain" class="form-control" placeholder="http://www.example.com"/>
                    </div>
                    <div class="form-group has-feedback">
                        <input type="tel" name="contact_no" class="form-control" placeholder="Contact number"/>
                    </div>
                    <div class="form-group has-feedback">
                        <input type="password" id="password" name="password" class="form-control" placeholder="Password"/>
                    </div>
                    <div class="form-group has-feedback">
                        <input type="password" name="password2" class="form-control" placeholder="Retype password"/>
                    </div>
                
                
                <div class="row">
                    <div class="col-xs-8">    
                       <a href="<?=  base_url()?>" class="text-center">I already have a membership</a>                     
                    </div><!-- /.col -->
                    <div class="col-xs-4">
                      <button type="submit" name="submit" id="submit" class="btn btn-primary btn-block btn-flat">Sign me up</button>
                    </div><!-- /.col -->
                </div>
            </form>
            </div>

            <div class="margin text-center">
            </div>
        </div>
        <!-- jQuery 2.0.2 -->
        
        <script src="<?php echo base_url() ?>assets/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url() ?>assets/js/jquery.validate.js" type="text/javascript"></script>
             
        <script src="<?php echo base_url() ?>assets/js/register.jquery.js" type="text/javascript"></script>
        <script src="<?php echo base_url()?>assets/js/jquery-migrate-1.0.0.js"></script>
     </div>
    </body>
</html>