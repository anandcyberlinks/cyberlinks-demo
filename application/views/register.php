<!DOCTYPE html>
<html class="bg-black">
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
        <script src="<?php echo base_url() ?>assets/js/jquery-1.10.2.js"></script>
    </head>
    <body class="bg-black">
        <div class="form-box" id="login-box">
            <div class="header">Register New Membership</div>
            <form action="" method="post" id="register_form">
                <div class="body bg-gray">
                    <div class="form-group">
                        <label><a id="token" href="">Generate Token</a></label>
                        <input type="text" name="token" id="id_token" value="" class="form-control" placeholder="Generate Token" />
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-xs-6">
                                <input type="text" name="first_name" class="form-control" placeholder="First Name"/>
                            </div>
                            <div class="col-xs-6">
                                <input type="text" name="last_name" class="form-control" placeholder="Last Name"/>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="text" name="email" class="form-control" placeholder="Email"/>
                    </div>
                    <div class="form-group">
                        <input type="tel" name="contact_no" class="form-control" placeholder="Contact number"/>
                    </div>
                    <div class="form-group">
                        <input type="password" id="password" name="password" class="form-control" placeholder="Password"/>
                    </div>
                    <div class="form-group">
                        <input type="password" name="password2" class="form-control" placeholder="Retype password"/>
                    </div>
                </div>
                <div class="footer">                    
                    <button type="submit" name="submit" class="btn bg-olive btn-block">Sign me up</button>
                    <a href="<?=  base_url()?>" class="text-center">I already have a membership</a>
                </div>
            </form>

            <div class="margin text-center">
            </div>
        </div>
        <script>
            $("#token").click(function () {
                $("#id_token").val('');
                $("#id_token").val('<?= uniqid() ?>');
                return false;
            });
        </script>
        <!-- jQuery 2.0.2 -->
        <script src="<?php echo base_url() ?>assets/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url() ?>assets/js/jquery.validate.js" type="text/javascript"></script>
        <script src="<?php echo base_url() ?>assets/js/register.jquery.js" type="text/javascript"></script>
        <script src="<?php echo base_url()?>assets/js/jquery-migrate-1.0.0.js"></script>
    </body>
</html>