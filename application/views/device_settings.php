<div class="wrapper row-offcanvas row-offcanvas-left">
    <!-- Right side column. Contains the navbar and content of the page -->
    <aside class="right-side">                
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1><?php echo $welcome->loadPo('Video') ?><small><?php echo $welcome->loadPo('Control panel') ?></small></h1>
            <ol class="breadcrumb">
                <li><a href="<?php echo base_url(); ?>"><i class="fa fa-dashboard"></i><?php echo $welcome->loadPo('Dashboard') ?></a></li>
                <li><a href="<?php echo base_url(); ?>video"><i class="fa fa-play-circle"></i><?php echo $welcome->loadPo('Video') ?></a></li>
                <li class="active"><?php echo $welcome->loadPo('Video') . " " . $welcome->loadPo('Settings') ?></li>
            </ol>
        </section>
        <div>
            <div id="msg_div">	
                <?php if (isset($msg)) {
                    echo $msg;
                } ?> 
<?php echo $this->session->flashdata('message'); ?>
            </div>	
        </div>
        <!-- Main content -->
        <section class="content">
            <div id="content">
                <div class="row">
                    <div class="col-md-12">
                        <!-- Custom Tabs -->
                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                                <li class="<?= ($tab === 'Splash') ? 'active' : '' ?>"><a href="<?php echo base_url(); ?>device/setting/splash" ><?php echo $welcome->loadPo('Splash Screen'); ?></a></li>
                                <!--<li class="<?= ($tab === 'Player') ? 'active' : '' ?>"><a href="<?php echo base_url(); ?>video/setting/Player" ><?php echo $welcome->loadPo('Player'); ?></a></li>
                                <li class="<?= ($tab === 'country') ? 'active' : '' ?>"><a href="<?php echo base_url(); ?>video/setting/country" ><?php echo $welcome->loadPo('country'); ?></a></li>
                                <li class="pull-right">&nbsp;</li>-->
                            </ul>
                            <div class="tab-content">
                                <link href="<?php echo base_url(); ?>assets/css/colorpicker/bootstrap-colorpicker.min.css" rel="stylesheet" type="text/css" />
                                <!-- Flavors section starts -->
                                <?php if ($tab == 'Splash') { ?>
                                    <div class="tab-pane active" id="tab_flavor">
                                        <div class="row">
                                            <form action="<?php echo base_url() ?>device/setting_splash" id="splashsetting" method="post" accept-charset="utf-8" enctype='multipart/form-data'>
                                            <div class="col-md-6">
                                                <!-- general form elements -->
                                                <div class="box box-primary">
                                                    <div class="box-header">
                                                        <!--<h3 class="box-title">Video Elements</h3> -->
                                                    </div><!-- /.box-header -->
                                                    <!-- form start -->
                                                    <div class="box-body">
                                                        <div class="form-group">
                                                            <div class="input file">
                                                                <label for="upload"><?php echo $welcome->loadPo('Upload file'); ?> </label>
                                                                <input type='file' name='uploadfile' id="uploadfile">
                                                            </div>                                       
                                                        </div>                                                    
                                                     <div class="form-group">
                                                            <div class="input file">                                                                
                                                                <input type='submit' name='upload' value='Upload'>
                                                            </div>                                       
                                                        </div>
                                                    </div>
                                                     
                                                </div><!-- /.box -->
                                                <div><img width=280 height=500 src="<?php echo base_url().$splash_screen;?>"></div>
                                            </div><!--/.col (left) -->
                                            
                                            <div class="col-md-6">
                                            
                                                <input type="hidden" id="redirect_url" name="redirect_url" value="<?php echo current_full_url(); ?>" />
                                                <div class="box box-warning">
                                                    <div class="box-header">
                                                        <h3 class="box-title">Splash Screen Dimensions</h3> 
                                                    </div>
                                                    <div class="box-body">                                                       
                                                        <input id="loc_tot" name="loc_tot" value="" type='hidden'>
                                                       <div id="InputsWrapper">                                                                                                        
                                                    <?php if($result){
                                                        $i=0;                                                       
                                                   foreach($result as $row){
                                                   $i++;
                                                   ?>
                                                    <div style='padding:5px;'>  
                                                        
                                                        Width: <input style="width:50px" maxlength="4" type='text' id="w_splash_<?php echo $i?>" name='wsplash[]' value='<?php echo $row->width?>' >
                                                        Hight: <input style="width:50px" maxlength="4" type='text' id="h_splash_<?php echo $i?>" name='hsplash[]' value='<?php echo $row->height?>' >
                                                        <input type='hidden' name='dimension[]' value="<?php echo $row->dimension_name;?>">
                                                        
                                                        <?php echo $row->dimension_name  ?>
                                                    </div>
                                                   <?php } }?>
                                                  <!--<div><a href="#" id="AddMoreFileBox" class="btn btn-info">Add More</a></div>-->
                                                </div>         
                                                    </div>        
                                                </div>
                                                <div class="box-footer">
                                                    <button class="btn btn-primary btn-sm" type="submit" name="save" value="Save"><?php echo $welcome->loadPo('Save'); ?></button>										
                                                </div>
                                            
                                        </div><!-- /.box -->
                                        </form>
                                    </div>
<?php } ?>
                                <!-- Flavors section ends -->

                                <!-- Player section starts -->

                                <!-- Player section ends -->

                            </div><!-- /.tab-content -->
                        </div><!-- nav-tabs-custom -->
                    </div><!-- /.col -->
                </div> <!-- /.row -->
            </div>
        </section><!-- /.content -->
    </aside><!-- /.right-side -->
</div><!-- ./wrapper -->

<!-- Model player  -->
<div class="modal fade" id="playerModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel">Modal title</h4>
            </div>
            <div class="modal-body"><div align="center" id="jsplayer"></div></div>
        </div>
    </div>
</div>
<script>
//-- add more text box in jquery --//

$(document).ready(function() {

var senscount = 0;

var MaxInputs       = 20; //maximum input boxes allowed
var InputsWrapper   = $("#InputsWrapper"); //Input boxes wrapper ID
var AddButton       = $("#AddMoreFileBox"); //Add button ID

if(senscount>0){
   var x = senscount; //initlal text box count
   var FieldCount=senscount; //to keep track of text box added     
}else{
    var x = InputsWrapper.length; //initlal text box count
    var FieldCount=1; //to keep track of text box added
}

//alert(x);
$('#seekid').val(FieldCount);
 $('#loc_tot').val(x);
$(AddButton).click(function (e)  //on add input button click
{
        if(x <= MaxInputs) //max input box allowed
        {
            FieldCount++; //text box added increment
            //add input box
            $(InputsWrapper).append('<div style="padding:5px;"> Width: <input type="text" style="width:50px" maxlength="4" name="offset_hrs_loc[]" id="h_loc_'+ FieldCount +'" value="00"/> Height: <input type="text" style="width:50px" maxlength="4" name="offset_minutes_loc[]" id="m_loc_'+ FieldCount +'" value="00"/><a href="#" class="removeclass">&times;</a></div>');
            x++; //text box increment
            $('#seekid').val(FieldCount);
             $('#loc_tot').val(x);
        }
return false;
});

$("body").on("click",".removeclass", function(e){ //user click on remove text
        if( x > 1 ) {
                $(this).parent('div').remove(); //remove text box
                x--; //decrement textbox   
                 $('#loc_tot').val(x);
        }       
return false;
}) 

//-- get latitude and longitude from browser --//
/*navigator.geolocation.getCurrentPosition(GetLocation);
function GetLocation(location) {
    alert(location.coords.latitude);
    alert(location.coords.longitude);
    alert(location.coords.accuracy);
    }*/
});
//-----------------------------------//
</script>