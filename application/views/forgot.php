<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>AdminLTE | Log in</title>
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
            <div>
                <?php $msg = $this->session->flashdata('msg');
                if ($msg != '') {
                    ?>
                    <?php echo $msg; ?>
                <?php } ?>
            </div>
            <div class="login-logo">
                <b>Multi</b>TV
            </div><!-- /.login-logo -->
            <div class="login-box-body">
            <p class="login-box-msg">Password Reset</p>
                <form action="" method="post">
                    <div class="form-group has-feedback">
                        <div class="form-group">
                            <input type="email" name="email" class="form-control" placeholder="Your Email" required/>
                            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-xs-8">    
                          <div class="checkbox icheck">
                            <label>
                              <p><a href="<?php echo base_url() ?>">Try Login</a></p>
                            </label>
                          </div>                        
                        </div><!-- /.col -->
                        <div class="col-xs-4">
                            <button type="submit" class="btn btn-primary btn-block btn-flat" name="forgot">Submit</button>
                        </div><!-- /.col -->
                    </div>
                    
                </form>
            </div>
        </div>


        <!-- jQuery 2.0.2 -->
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
        <!-- Bootstrap -->
        <script src="<?php echo base_url() ?>assets/js/bootstrap.min.js" type="text/javascript"></script>        

    </body>
</html>