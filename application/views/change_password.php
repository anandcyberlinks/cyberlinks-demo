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
                <div class='alert alert-danger alert-dismissabl'>
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
                
                <form action="<?php echo base_url().'api/user/changepassword/token/'.$data['token'];?>" method="post" onsubmit="return validatedata(<?php echo "'".$data['id']."'";?>);">
                    <div class="form-group has-feedback">
                        <input type="password" name="oldpassword" id="oldpassword" required value="" class="form-control" placeholder="Old Password"/>
                        <input type="hidden" name="id" value="<?php echo $data['id'];?>" />
                        <span class="glyphicon glyphicon-lock form-control-feedback">
                            
                        </span>
                        <em style='color: red' id="error_oldpassword"></em>
                    </div>
                    <div class="form-group has-feedback">
                        <input type="password" name="newpassword" id="newpassword" required value="" class="form-control" placeholder="New Password"/>
                        <span class="glyphicon glyphicon-lock form-control-feedback">
                            
                        </span>
                        <em style='color: red' id="error_newpassword"></em>

                    </div>
                    <div class="form-group has-feedback">
                        <input type="password" name="confirmpassword" required id="confirmpassword" value="" class="form-control" placeholder="Confirm Password"/>
                        <span class="glyphicon glyphicon-lock form-control-feedback">
                            
                        </span>
                        <em style='color: red' id="error_confirmpassword"></em>
                    </div>
                    
                    <div class="row">
                        
                        <div class="col-xs-4">
                            <button type="submit" class="btn btn-primary btn-block btn-flat" name="submit" value="submit">Submit</button>
                        </div><!-- /.col -->
                    </div>
                </form>
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
        function validatedata(id){
            var count=0;
            var oldpassword = $('#oldpassword').val();
            var newpassword = $('#newpassword').val();
            var confirmpassword = $('#confirmpassword').val();
            $.ajax({
                type: 'POST',
                async: false,
                url: '<?=base_url() ?>layout/validpass',
                data: {'id':id,'password':oldpassword},
                success: function (responce) {
                 if (responce=='0'){
                    $("#error_oldpassword").html('Old password doesnot matched');
                    count++;
                 }
                }
            });
            if (oldpassword.trim()==''){
                $("#error_oldpassword").html('Field cant be blank');
                count++;
            }else if (newpassword.trim()==''){
                $("#error_newpassword").html('Field cant be blank');
                count++;
            }else if(newpassword.length <= 7){
                $("#error_newpassword").html('Must be of 8 characters');
                count++;
            }else if (confirmpassword.trim()==''){
                $("#error_confirmpassword").html('Field cant be blank');
                count++;
            }else if(confirmpassword.length <= 7){
                $("#error_confirmpassword").html('Must be of 8 characters');
                count++;
            }else{
                $("#error_confirmpassword").html('');
            }

          if (count>0) {
            return false;
          }else{
             return true;    
            }
        }
      </script>
    </body>
</html>