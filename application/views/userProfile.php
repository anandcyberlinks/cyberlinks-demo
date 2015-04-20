<!-- Right side column. Contains the navbar and content of the page -->
<aside class="right-side">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1><?php echo $welcome->loadPo('Profile') ?></h1> 
    </section>
    <span>
        <div id="msg_div">
            <?php echo $this->session->flashdata('message'); ?>
        </div>	
    </span>
    <!-- Main content -->
    <div class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                           
                            <?php foreach ($data as $data) { ?>
                                <h3 class="panel-title"><?php echo $data->username; ?></h3>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <?php
                                    $image_info = getimagesize($welcome->getimage());
                                    $image_width = $image_info[0];
                                    if ($image_width <= 160) {
                                        $width = "";
                                    } else {
                                        $width = '165';
                                    }
                                    $image_height = $image_info[1];
                                    if ($image_height <= 25) {
                                        $height = "";
                                    } else {
                                        $height = "28";
                                    }
                                    ?>


                                    <div class="col-md-3 col-lg-3 " align="center"> <img alt="User Pic" src="<?php echo $welcome->getimage() ?>" width="<?php echo $width ?>" height="<?php $height ?>"><br><br>
                                        <form action="<?php echo base_url() ?>layout/do_upload<?= ($this->uri->segment(3)) ? '/' . $this->uri->segment(3) : ''; ?>" method="post" enctype="multipart/form-data">
                                            <input class="btn btn-primary btn-sm"  type="file" name="image" size="20" >
                                            <br />
                                            <input class="btn btn-primary btn-sm" type="submit"  name="submit" value="<?php echo $welcome->loadPo('Upload'); ?>">
                                        </form>
                                    </div>
                                    <div class=" col-md-9 col-lg-9 "> 
                                        <table class="table table-user-information">
                                            <tbody>
                                                <tr>
                                                    <td><?php echo $welcome->loadPo('User Name') ?>:</td>
                                                    <td><?php echo $data->username; ?></td>
                                                </tr>  
                                                <tr>
                                                    <td><?php echo $welcome->loadPo('Name') ?>:</td>
                                                    <td><?php echo ucfirst($data->first_name) . " " . ucfirst($data->last_name); ?></td>
                                                </tr>
                                                <tr>
                                                    <td><?php echo $welcome->loadPo('Email') ?>:</td>
                                                    <td><?php echo $data->email; ?></td>
                                                </tr>
                                                <tr>
                                                    <td><?php echo $welcome->loadPo('Gender') ?>:</td>
                                                    <td><?php echo $data->gender; ?></td>
                                                </tr>
                                                <tr>
                                                    <td><?php echo $welcome->loadPo('User Role') ?>:</td>
                                                    <td><?php echo $this->role; ?></td>
                                                </tr>
                                                <tr>
                                                    <td><?php echo $welcome->loadPo('API Token') ?>:</td>
                                                    <td><?php echo $data->token; ?></td>
                                                </tr>
                                                <tr>
                                                    <td><?php echo $welcome->loadPo('Status') ?>:</td>
                                                    <td><?php echo $data->status; ?></td>
                                                </tr>
                                                <tr>
                                                    <td><?php echo $welcome->loadPo('Token') ?>:</td>
                                                    <td><button class="btn btn-warning" id="token">Re-Ganrate</button></td>
                                                </tr>
                                            <?php } ?>
                                                
                                        </tbody>
                                    </table>
                                    <!-----
                                    <a href="#" class="btn btn-primary">Change Password</a>
                                    <a href="<?php echo base_url(); ?>admin/users/userEdit/<?php echo $data->id; ?>" class="btn btn-primary">Edit Profile</a>
--->
                                </div>
                            </div>
                        </div>          
                    </div>
                </div>
            </div>
        </div>
    </div>
</aside>
<script>
    $("#token").click(function () {
        bootbox.confirm("Your old token will be expired,<br> New token will be sent to you email<br>Are you sure?", function (result) {
            if(result){
                var url = 'http://localhost/multitvfinal/user/changestatus/?id=<?=$data->id ?>&email=<?=$data->email ?>&domain=<?=$data->domain ?>&status=inactive';
                $("#token").html("<img src='<?=base_url()?>/assets/img/spinner.gif'>");
                $("#token").addClass('disabled');
                $.ajax({
                    url:url
                }).done(function(){
                    bootbox.alert('Your new token successfully sent to your email');
                    $("#token").html('Token Ganrated');
                })
            }else{
                console.log('not confirm');
            }
        });
        return false;
    })
</script>

