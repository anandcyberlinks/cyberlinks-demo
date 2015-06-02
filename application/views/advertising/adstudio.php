    <style>
        .adStudioDefault {
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 5px;
            position: relative;
            
        }
/*        #ad-LeftSection ul > li > a {
            cursor: move; 
        }*/
        .adBox{
            min-height: 500px;
   
        }
        #elementProperties > h1 {
            font-size: 14px;
        }
        .bg {
            background-color: #ffffff;
        }
        .videoBox{
            width: 350px;
            height: 250px;
            background-color: #f1f1f1;
            text-align: center;
            vertical-align: middle;
            font-weight: bold;
            margin: 100px auto;
            padding: 10px;
            position: relative;
            display: none;
        }
        .adBox span {
            font-weight: normal;
        }
        .closeButton {
            position: absolute;
            top : 3px;
            left:333px;
            color: red;
        }
        #preview {
            width: 80px;
            position: absolute;
            left: 595px;
            top: 10px; 
                
        }
        
        </style>
    <!-- Right side column. Contains the navbar and content of the page -->
    <aside class="content-wrapper"> 
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1><?php echo $welcome->loadPo('Ad Studio') ?></h1>
            <ol class="breadcrumb">
                <li><a href="<?php echo base_url(); ?>"><i class="fa fa-dashboard"></i><?php echo $welcome->loadPo('Dashboard') ?></a></li>
                <li class="active"><?php echo $welcome->loadPo('Advertising') ?></li>
            </ol>
        </section>
        <!--div-->
            <div id="msg_div">
                <?php echo $this->session->flashdata('message'); ?>
            </div>	
            <?php if (isset($error) && !empty($error)) { ?><div id="msg_div"><?php echo $error; ?></div><?php } ?>
        <!--/div-->
        <!-- Main content -->
        <section class="content">
            <?php $search = $this->session->userdata('search_form');
            ?></pre>
            <div id="content">
                <div class="row bg">
                    <!-- left navigation> -->
                    <section id="ad-LeftSection" class="adStudioDefault col-lg-3 ">
                        <nav class="well sidebar-nav">
                        <ul class="nav nav-pills nav-stacked">
                            <li><a href="#" class="clearfix"><span class="pull-left">Button</span> <span class="pull-right"><i class="glyphicon glyphicon-plus"></i></span></a></li>
                            <li id="getVideoBox"><a href="#" class="clearfix"><span class="pull-left">Video</span> <span class="pull-right"><i class="glyphicon glyphicon-plus"></i></span></a></li>
                            <li><a href="#" class="clearfix"><span class="pull-left">Image</span> <span class="pull-right"><i class="glyphicon glyphicon-plus"></i></span></a></li>
                            <li><a href="#" class="clearfix"><span class="pull-left">Slider</span> <span class="pull-right"><i class="glyphicon glyphicon-plus"></i></span></a></li>
                            <!-- adding custom button here -->
                        </ul>   
                    </nav>
                    </section>
                    <!-- right video section -->
                    <section id="ad-RightSection" class="adStudioDefault adBox col-sm-6">

                        <!-- advertisement box -->
                        <div id="adBox">
                            <!--Video Element -->
                            <div class="videoBox">
                                <a href="javascript:void(0);"><i class="closeButton glyphicon glyphicon-remove"> </i></a>
                                <div id="videoSize">Video Size : <br/> <span>Width : 200px  &nbsp;&nbsp; Height : 150px</span></div>
                                <br/>
                                <div id="videoPosition">Video Position : <br/> <span>Top : 200px  &nbsp;&nbsp; Left : 150px</span></div>
                            </div>
                            <!--button Element -->
                            <div class="initialButton"></div>
                            <!--Image Element -->
                            <div class="initialImage"></div>
                            <!--Slider Element -->
                            <div class="initialSlider"></div>
                        </div>

                        <!-- preview section -->

                        <div id="preview">
                            <a href="#" ><button class="btn btn-success btn-sm">Preview</button></a>
                        </div>
                    </section>
                    <!-- Button Properties -->
                    <section id="elementProperties" class="adStudioDefault text-center col-lg-3">
                        <h1 class="text-warning"><strong>Active Element on the Ad Box can be edited in the box below.</strong></h1>
                        <p>Click the element to make active</p>
                        
                        <form>
                            <!--Video Edit Box -->
                            <div id="videoEditPanel">
                                
                            </div>

                            <!--Button Edit Box -->
                            <div id="buttonEditPanel"></div>
                            <!--Image Edit Box -->
                            <div id="imageEditPanel"></div>
                            <!--Slider Edit Box -->
                            <div id="sliderEditPanel"></div>
                        </form>   
                    </section>
                </div>
            </div>
        </section><!-- /.content -->
    </aside><!-- /.right-side -->
    <script>
    
    // load element :: element variable is the id of the element like (button, video Box, image Box, slider Box)
    function removeElement(){
        $(this).parent().parent().hide().fadeOut();
    }
    
    $(document).ready(function(){
        
        $("#getVideoBox > a").on('click', function(event){
            event.preventDefault();
            $(".videoBox").show().fadeIn();    
        });
        
        // Remove Element
        $(".closeButton").on('click' , this, removeElement);
    });
    
    
    </script>