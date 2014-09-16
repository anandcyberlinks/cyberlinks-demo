<!DOCTYPE html>
<?php
$s = $this->session->all_userdata();

    $width = '180';
    $height = "45";

?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>AdminLTE | Dashboard</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <!-- bootstrap 3.0.2 -->
        <link href="<?php echo base_url() ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <!-- font Awesome -->
        <link href="<?php echo base_url() ?>assets/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <!-- Ionicons -->
        <link href="<?php echo base_url() ?>assets/css/ionicons.min.css" rel="stylesheet" type="text/css" />
        <!-- Morris chart -->
        <link href="<?php echo base_url() ?>assets/css/AdminLTE.css" rel="stylesheet" type="text/css" />
        <!-- fullCalendar -->
        <link href="<?php echo base_url() ?>assets/css/fullcalendar/fullcalendar.css" rel="stylesheet" type="text/css" />
        <!-- bootstrap3-wysihtml5.min  -->
        <link href="<?php echo base_url() ?>assets/css/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css" rel="stylesheet" type="text/css" />
        <!-- daterangepicker  -->
        <link href="<?php echo base_url() ?>assets/css/daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css" /> 
        <!-- jcrop -->
        <link href="<?php echo base_url() ?>assets/css/jcrop/jquery.Jcrop.css" rel="stylesheet" type="text/css" />
        <!-- jquery.tagit.css -->
        <link href="<?php echo base_url() ?>assets/css/jquery.tagit.css" rel="stylesheet" type="text/css">
        <!-- tagit.ui-zendesk.css-->
        <link href="<?php echo base_url() ?>assets/css/tagit.ui-zendesk.css" rel="stylesheet" type="text/css">
        <link href="<?php echo base_url(); ?>assets/css/timepicker/bootstrap-timepicker.min.css" rel="stylesheet"/>
        <!-- jquery-1.10.2 -->
        <script src="<?php echo base_url() ?>assets/js/jquery-1.10.2.js"></script>
		
    </head>
    <body class="skin-blue">
        <!-- header logo: style can be found in header.less -->
        <header class="header">
            <a href="<?php echo base_url(); ?>" class="logo">
                <!-- Add the class icon to your logo image or logo icon to add the margining -->
                <img alt="User Pic" src="<?php echo $welcome->getimage() ?>" width="<?php echo $width ?>" height="<?php echo $height ?>">
            </a>
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Sidebar toggle button-->
                <a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>
                <div class="navbar-right">

                    <ul class="nav navbar-nav">
                        <!-- Messages: style can be found in dropdown.less-->
                        <?php
                        $sess = $this->session->all_userdata();
                        if (isset($sess['lan'])) {
                            $lang = $sess['lan'];
                        } else {
                            $lang = 'eng';
                        }
                        ?>
                        <li class="dropdown">
                            <a href="<?php echo base_url() ?>users/index/<?php echo $lang; ?>" class="dropdown-toggle" data-toggle="dropdown">                                
                                <span><?php
                                    if ($lang == 'hin') {
                                        echo $welcome->loadPo('Hindi');
                                    } else {
                                        echo "English";
                                    }
                                    ?><i class="caret"></i></span>
                            </a>
                            <ul class="dropdown-menu">                                
                                <!-- Menu Body -->
                                <li>                                   
                                    <a href="<?php echo base_url() ?>users/index/eng">English</a>                                   
                                </li>
                                <!-- Menu Footer-->
                                <li>                                  
                                    <a href="<?php echo base_url() ?>users/index/hin"><?php echo $welcome->loadPo('Hindi'); ?></a>                                    
                                </li>
                            </ul>
                        </li>
                        <!-- User Account: style can be found in dropdown.less -->
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="glyphicon glyphicon-user"></i>
                                <span><?php echo $s[0]->username; ?><i class="caret"></i></span>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- User image -->
                                <li class="user-header bg-light-blue">
                                    <div><img alt="User Pic" src="<?php echo $welcome->getimage() ?>" width="<?php echo $width ?>" height="<?php echo $height ?>"></div>
                                    <p>
                                        <?php echo $s[0]->email; ?>
                                        <small>Member since.  <?php echo $s[0]->created; ?></small>
                                        <small>Role.  <?php echo $s[0]->role; ?></small>
                                    </p>
                                </li>
                                <!-- Menu Footer-->
                                <li class="user-footer">
                                    <div class="pull-left">
                                        <a href="<?php echo base_url() ?>layout/profile" class="btn btn-default btn-flat">Profile</a>
                                    </div>
                                    <div class="pull-right">
                                        <a href="<?php echo base_url() ?>layout/logout" class="btn btn-default btn-flat">Sign out</a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>

                           