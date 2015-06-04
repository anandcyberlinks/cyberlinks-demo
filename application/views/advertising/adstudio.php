    <style>
/*        .adStudioDefault {
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 5px;
            position: relative;
            
        }
        #ad-LeftSection ul > li > a {
            cursor: move; 
        }
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
                
        }*/

     .adStudioDefault {
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 5px;
        }
/*        #ad-LeftSection ul > li > a {
            cursor: move; 
        }*/
        .bg {
            background-color: #ffffff;
        }
        .adBox{
            min-height: 500px;
        }
        #elementProperties > h1 {
            font-size: 14px;
        }
        
        #elementProperties input {
            width:30%;
            margin: 2px;
        }
        .elementPropertiesBox {
            padding: 5px;
            border: 1px dashed #ccc;
            display: none;
        }
        form button {
            visibility: hidden;
        }
        
        
        .adSize {
            position: relative;
        }
        .closeButton {
            position: absolute;
            top : 3px;
            left:3px;
            color: red;
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
                    <li ><a href="#" class="clearfix"><span class="pull-left">Button</span> <span class="pull-right"><i class="glyphicon glyphicon-plus"></i></span></a></li>
                    <li ><a href="#" class="clearfix"><span class="pull-left">Video</span> <span class="pull-right"><i class="glyphicon glyphicon-plus"></i></span></a></li>
                    <li ><a href="#" class="clearfix"><span class="pull-left">Image</span> <span class="pull-right"><i class="glyphicon glyphicon-plus"></i></span></a></li>
                    <li ><a href="#" class="clearfix"><span class="pull-left">Slider</span> <span class="pull-right"><i class="glyphicon glyphicon-plus"></i></span></a></li>
                    <li><a href="#" class="clearfix adBoxSize"><span class="pull-left ">Ad Box Size</span> <span class="pull-right"><i class="glyphicon glyphicon-plus"></i></span></a></li>
                    <!-- adding custom button here -->
                </ul>   
            </nav>
            </section>
            <!-- right video section -->
            <section id="ad-RightSection" class="adStudioDefault adBox col-sm-6">
                
                <!-- advertisement box -->
                <div id="adBox">
                    <!--Video Element -->
                    <div class="initialVideoSize"></div>
                    <!--button Element -->
                    <div class="initialVideoSize"></div>
                    <!--Video Element -->
                    <div class="initialVideoSize"></div>
                    <!--Video Element -->
                    <div class="initialVideoSize"></div>
                </div>
                
                <!-- priview section -->
                
                <div class="clearfix text-right">
                    <a href="#" id="priview"><button class="btn btn-success btn-sm">Preview</button></a>
                </div>
            </section>
            <!-- Button Properties -->
            <section id="elementProperties" class="adStudioDefault text-center col-lg-3">
                <h1 class="text-warning"><strong>Active Element on the Ad Box can be edited in the box below.</strong></h1>
                <p>Click the element to make active</p>
                
                <form class="form-inline">
                    <div class="elementPropertiesBox" id="adBoxSize">
                        
                        <div class="form-group">
                            <h5 class="text-danger">Height and Width are in Pixels.!</h5>
                          <label for="width">Ad Box Width</label>
                          <input type="text" class="form-control" name="adBoxWidth"  placeholder="100">
                        </div>
                        <div class="form-group">
                          <label for="height">Ad Box Height</label>
                          <input type="text" class="form-control" name="adBoxHeight"  placeholder="100">
                        </div>
                        
                    </div>
                
                <!--Button Edit Box -->
                <div id="buttonEditPanel"></div>
                <!--Image Edit Box -->
                <div id="imageEditPanel"></div>
                <!--Slider Edit Box -->
                <div id="sliderEditPanel"></div>
                
                
                
                    
                <button type="submit" class="btn btn-default">Save</button>
                </form>    
            </section>
        </div>
            </div>
        </section><!-- /.content -->
    </aside><!-- /.right-side -->
    <script>
    
//    // load element :: element variable is the id of the element like (button, video Box, image Box, slider Box)
//    function removeElement(){
//        $(this).parent().parent().hide().fadeOut();
//    }
//    
//    $(document).ready(function(){
//        
//        $("#getVideoBox > a").on('click', function(event){
//            event.preventDefault();
//            $(".videoBox").show().fadeIn();    
//        });
//        
//        // Remove Element
//        $(".closeButton").on('click' , this, removeElement);
//        
//        
//        
//        
//        
//        // testing
//        $(".product").droppable({
//            accept: '#adBox',
//            drop: function(event, ui) {
//                $(this).append($(ui.draggable).clone());
//                $("#container .product").addClass("item");
//                $(".item").removeClass("ui-draggable product");
//                $(".item").draggable({
//                    containment: 'parent',
//                    grid: [150,150]
//                });
//            }
//        });
//        $("#adBox").draggable({
//            helper: 'clone'
//        });
//
//        
//        
//        
//        
//        
//    });

   $(document).ready(function(){
        
        $('.adBox').droppable({
           drop: function(event, ui) {
               
                if($('.adBox').find('div').hasClass('adSize') === false) {
                    $( this ).find( ".adBoxSize" ).remove();
                    $box = $( "<div class='adSize'></div>" ).appendTo(this).resizable();

                    $($box).html("<a href='javascript:void(0);'><i class='closeButton glyphicon glyphicon-remove'> </i></a>")
                    $($box).css({"width": "100px", "height":"100px", "border":"1px solid #f1f1f1"});

                    $($box).draggable({
                        containment: 'parent'
                    });
                    $("form").children('div').hide();
                    $("#adBoxSize").show();
                    $("button").css({"visibility": "show"});
               } else {
                   alert('Div Size Box can only be 1');
               }
        
              // Remove Element
               $(".closeButton").bind('click' , this, removeElement);
               
               $(".adSize").on('mousedown', function(){
                   $(this).resizable();
               });
               
               $(".adSize").resizable();
                
                
           },
           accept:'.adBoxSize',
           
        });
        $('.adBoxSize').draggable({helper : 'clone'});
        $('.adImage').draggable({helper : 'clone'});
        $('.adButton').draggable({helper : 'clone'});
      
        
         // load element :: element variable is the id of the element like (button, video Box, image Box, slider Box)
        function removeElement(){
            $(this).parent().parent().remove().fadeOut();
            $("form").children('div').hide();
            $("button").hide();
        }
        
        
        
       
      
      
      
        
//        $('.adBoxSize').draggable( {
//            cursor: 'move',
//            containment: '.adBox',
//            helper: myHelper,
//            drop: function(event, ui) {
//                $(".ui-draggable-dragging").clone().appendTo(".adbox");
//            }
//        });
//        
        function myHelper( event ) {
                return '<div id="draggableHelper">I am a helper - drag me!</div>';
        }
        
        // changing element on chaning properties
        $("input[name=adBoxWidth]").on('keyup', this, function(){
            
            var $width = ($(this).val() * 50) / 100;
             if($('.adBox').find('div').hasClass('adSize') === true) {
                 $('.adBox').find('.adSize').css({'width':$width});
             }
            
        });
        // changing element on chaning properties
        $("input[name=adBoxHeight]").on('keyup', this, function(){
            
            var $width = ($(this).val() * 50) / 100;
             if($('.adBox').find('div').hasClass('adSize') === true) {
                 $('.adBox').find('.adSize').css({'height':$width});
             }
            
        });
        
   });
        
    
    
    </script>