<!--div class="wrapper row-offcanvas row-offcanvas-left"-->
    <!-- Right side column. Contains the navbar and content of the page -->
    <aside class="content-wrapper"> 
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1><?php echo $welcome->loadPo('Video') ?><small><?php echo $welcome->loadPo('Control panel') ?></small></h1>
            <ol class="breadcrumb">
                <li><a href="<?php echo base_url(); ?>"><i class="fa fa-dashboard"></i><?php echo $welcome->loadPo('Dashboard') ?></a></li>
                <li class="active"><?php echo $welcome->loadPo('Advertising') ?></li>
            </ol>
        </section>
        <div>
            <div id="msg_div">
                <?php echo $this->session->flashdata('message'); ?>
            </div>	
            <?php if (isset($error) && !empty($error)) { ?><div id="msg_div"><?php echo $error; ?></div><?php } ?>
        </div>
        <!-- Main content -->
        <section class="content">
            <?php $search = $this->session->userdata('search_form');
            ?></pre>
            <div id="content">
                <!-- form start -->
                <form  method="post" action="<?php echo base_url(); ?>advertising/index" onsubmit="return date_check();" id="searchIndexForm" name="searchIndexForm" accept-charset="utf-8">
                    <div class="row">
                        <!-- left column -->
                        <div class="col-md-12">
                            <!-- general form elements -->
                            <div class="box box-primary collapsed-box">
                            <div class="box-header">
                            <!-- tools box -->
                            <div class="pull-right box-tools">
                                <button class="btn btn-danger btn-sm" data-widget='collapse' data-toggle="tooltip" title="Collapse"><i class="fa fa-plus"></i></button>
                            </div><!-- /. tools -->
                            <h3 class="box-title">Search Stream</h3>
                        </div>

                                    <div style="display:none;"><input type="hidden" name="_method" value="POST"/></div>
                                    <div class="box-body" style="display:none;">
                                        <div class="row">
                                            <div class="form-group col-lg-4">
                                                <div class="input text">
                                                    <label for=""><?php echo $welcome->loadPo('Title') ?></label>
                                                    <input type="text" name="content_title" id="content_title" class="form-control" value="<?php echo (isset($search['content_title'])) ? $search['content_title'] : ''; ?>" placeholder="<?php echo $welcome->loadPo('Title') ?>">
                                                </div>
                                            </div>
                                            <div class="form-group col-lg-4">
                                                <div class="input select">
                                                    <label for="searchCategory"><?php echo $welcome->loadPo('Category') ?></label>
                                                    <select name="category" class="form-control" placeholder="<?php echo $welcome->loadPo('Category') ?>" id="searchCategory">
                                                        <option value=""><?php echo $welcome->loadPo('Select') ?></option>
                                                        <?php foreach ($category as $key=>$val) { ?>
                                                            <option value="<?php echo $key; ?>" <?php
                                                            if (isset($search['category'])) {
                                                                if ($key == $search['category']) {
                                                                    echo 'selected';
                                                                }
                                                            }
                                                            ?>  ><?php echo $val ?></option>
                                                                <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-lg-4">
                                                <div class="input text">
                                                    <label for="url"><?php echo $welcome->loadPo('Start Date') ?></label>
                                                    <input type="text" class="form-control"  id="datepickerstart" name="datepickerstart" placeholder="<?php echo $welcome->loadPo('Start Date') ?>" value="<?php echo (isset($search['datepickerstart'])) ? $search['datepickerstart'] : ''; ?>" >											
                                                </div>
                                            </div>
                                            <div class="form-group col-lg-4">
                                                <div class="input text">
                                                    <label for="url"><?php echo $welcome->loadPo('End Date') ?></label>
                                                    <input type="text" class="form-control"  id="datepickerend" name="datepickerend" placeholder="<?php echo $welcome->loadPo('End Date') ?>" value="<?php echo (isset($search['datepickerend'])) ? $search['datepickerend'] : ''; ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div><!-- /.box-body -->
                                    <div class="box-footer" style="display:none;">
                                            <!--	<input type="text" id="hddstarddt" name="hddstarddt" value="<?php echo @$_POST['hddstarddt'] ?>"> -->
                                        <button type="submit" name="submit" value="Search"class="btn btn-primary"><?php echo $welcome->loadPo('Search') ?></button>
                                        <button type="submit" name="reset" value="Reset"class="btn btn-primary"><?php echo $welcome->loadPo('Reset') ?></button>
                                    </div>
                                
                            </div><!-- /.box -->
                        </div><!--/.col (left) -->
                    </div>
                </form>    
                <div class="row">
                    <div class="col-xs-12">
                        <div class="box">
                            <div class="box-body table-responsive">
                                <?php if ($this->pagination->total_rows != '0') {?>
                                <div><a class="linkClick"   data-toggle="modal" data-backdrop="static" href="#tester" data-toggle="modal" value='Edit'>Edit</a>

                                    </div>
                                <?php } ?>
                                <table id="example2" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>select</th>
                                            <th width="15px"><a href="<?php echo base_url(); ?>advertising/index/title/<?php echo (!empty($show_t)) ? $show_t : 'asc'; ?>"><?php echo $welcome->loadPo('Channel Name') ?></a></th>
                                            
                                            <th><?php echo $welcome->loadPo('Category') ?></th>
                                            <th><?php echo $welcome->loadPo('User') ?></th>
                                            <th><?php echo $welcome->loadPo('Status') ?></th>
                                            <th><?php echo $welcome->loadPo('Preview') ?></th>
                                            <th><?php echo $welcome->loadPo('Publish Date') ?></th>
                                            
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php foreach ($result as $value) { ?>
                                        <tr id="<?php echo $value->id ?>">
                                            <td><input type='checkbox' name='video[]' class="video" value="<?php echo $value->id; ?>"></td>
                                                <td  width="350"><?php echo strlen($value->name) > 40 ?  substr($value->name,0,40).'...' : $value->name; ?></td>
                                                <td><?php echo $value->category; ?></td>
                                                <td><?php echo $value->username; ?></td>
                                                <td><?php if ($value->status == 1) { ?>
                                                        <img src="<?php echo base_url(); ?>assets/img/test-pass-icon.png" alt="Active" />
                                                    <?php } else { ?>
                                                        <img src="<?php echo base_url(); ?>assets/img/test-fail-icon.png" alt="Active" />
                                                    <?php } ?></td>
                                                <td style='text-align:center'>
                                                    <?php if(in_array(@$value->minetype,array('video/wmv','video/avi'))) { ?>
                                                    --
                                                    <?php } else { ?>
                                                    <?php $new_val = json_decode($value->url); ?>
                                                    <a class="prev_video" href="#myModal" data-backdrop="static" data-toggle="modal" data-img-url="<?php echo $new_val['0']->{'web'}->{'3g'}; ?>">Preview</a>
                                                    <?php } ?>
                                                </td>
                                                <td  width="120"><?php echo date('M d,Y', strtotime($value->created)); ?></td>
                                               
                                            </tr>
                                        <?php } ?>
                                    </tbody>


                                </table>
                                
                                <!-- Pagination start --->
                                <?php
                                if ($this->pagination->total_rows == '0') {
                                    echo "<tr><td colspan=\"7\"><h4>" . $welcome->loadPo('No Record Found') . "</td></tr></h4>";
                                } else {
                                    ?>
                                    </table>
                                    <div><a class="linkClick"   data-toggle="modal" data-backdrop="static" href="#tester" data-toggle="modal" value='Edit'>Edit</a>

                                    </div>
                                    <div class="row pull-left">
                                        <div class="dataTables_info" id="example2_info"><br>
                                            <?php
                                            $param = $this->pagination->cur_page * $this->pagination->per_page;
                                            if ($param > $this->pagination->total_rows) {
                                                $param = $this->pagination->total_rows;
                                            }
                                            if ($this->pagination->cur_page == '0') {
                                                $param = $this->pagination->total_rows;
                                            }
                                            $off = $this->pagination->cur_page;
                                            if ($this->pagination->cur_page > '1') {
                                                $off = (($this->pagination->cur_page * '10') - 9);
                                            }
                                            echo "&nbsp;&nbsp;Showing <b>" . $off . "-" . $param . "</b> of <b>" . $this->pagination->total_rows . "</b> total results";
                                        }
                                        ?>
                                    </div>
                                </div>	
                                <div class="row pull-right">
                                    <div class="col-xs-12">
                                        <div class="dataTables_paginate paging_bootstrap">
                                            <ul class="pagination"><li><?php echo $welcome->loadPo($links); ?></li></ul> 
                                        </div>
                                    </div>
                                </div>
                            </div>		
                            <!-- Pagination end -->
                        </div><!-- /.box-body -->
                    </div><!-- /.box -->
                </div>
            </div>
            </div>
        </section><!-- /.content -->
    </aside><!-- /.right-side -->
<!--/div--><!-- ./wrapper -->

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


<!-- Model box Cue points  -->
<div class="modal fade" id="cuepointModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel">Modal title</h4>
            </div>
            <div class="modal-body"><div align="center" id="cuepoint"></div></div>
        </div>
    </div>
</div>

<script>
</script>
<!--  this div for  jwplyer reponce -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" 
     aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" 
                        data-dismiss="modal" aria-hidden="true" onclick='stopvideo("prevElement")'>
                    &times;
                </button>
                <h4 class="modal-title" id="myModalLabel">
                    Preview
            </div>
            <div class="modal-body no-padding">        
                <center>   <div id="prevElement"></div></center>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal -->
</div> 
<div class="modal fade" id="tester" tabindex="-1" role="dialog" 
     aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width:70%">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" 
                        data-dismiss="modal" aria-hidden="true" onclick='stopvideo("prevElement")'>
                    &times;
                </button>
                <h4 class="modal-title" id="myModalLabel">
                    CuePoint
            </div>
            <div class="modal-body no-padding">        
                      
                            <div id="prevElement" class="innerResponse">
                                <!--<div class="headerTb" style="border:1px solid gray;min-height: 200px;padding: 0px 10px">
                                    <div class="headingTb">
                                        <div style="background-color: gray;padding:10px 0px;color: white">Click on scrollbar to add or edit</div>
                                        <div class="ionslider" id="ionslider">
                                            <div class="thumbLeft" style="float:left; width: 20%">1</div>
                                            <div class="thumbRight"  style="float:left;width: 80%">Slider</div>
                                        </div>
                                        <div class="thumbLeft" style="float:left; width: 20%">1</div>
                                        <div class="thumbRight"  style="float:left;width: 80%">2</div>
                                    </div>
                                </div>   -->
                                <div class="box-body no-padding">
			<div class="table-responsive">
                             <input type="hidden" name="forChangeVal" id="forChangeVal">
                                <input type="hidden" name="text" id="text">
    <input type="hidden" name="percentage" id="percentage">
<div class="popOver">
    <div class="content">
    <div class="row">
    <section class="col-lg-6 addCueDiv">
        <!-- Box (with bar chart) -->
        <div class="box box-danger">
            <div class="box-header">           
            <h3 class="box-title">Add New Cue Point</h3>
            </div><!-- /.box-header -->
            <div class="box-body no-padding">
            <div class="row">
                <div class="col-sm-10" style="margin-left:10px">
                                <div class="form-group">
                                    <input type='hidden' name="delVal" id="delVal">
                                    <input type="hidden" value="" name="singleVideoFlag" id="singleVideoFlag">
                                    <input type="hidden" value="" name="inialValPoint" id="inialValPoint">
                                <input type="hidden" value="" name="inialValPercentage" id="inialValPercentage">
                                    <label style="display:block;" for="exampleInputEmail1">Timecode(mm:ss)</label>
                                    <input maxlength=3 style='width:35px;display:inline' type="text" name="hh" id="hh" placeholder="hh" class="form-control input-sm hh">:
                                    <input maxlength=3 style='width:35px;display:inline;' type="text" name="mm" id="mm" placeholder="mm" class="form-control input-sm mm">:                                    
                                    <input maxlength=3 style='width:35px;display:inline' type="text" name="ss" id="ss" placeholder="ss" class="form-control input-sm ss">
                                </div>
                                <div class="form-group">
                                            <label for="exampleInputEmail1">Name (optional)</label>
                                            <input type="email" id="cueName" name="cueName" placeholder="Name" id="exampleInputEmail1" class="form-control">
                                        </div>
                                <div class="box-footer">
                                    <button class="btn btn-primary" type="submit" name="add" id="add">Add</button><button class="btn btn-primary" type="submit" name="update" id="update">Update</button>&nbsp;<a href="#" id="cancel" class="cancel">cancel</a>
                                <span style="float:right;"><a href="#" id="delete" class="cancel">delete</a></span>
                                </div>
                                <div id="cue_err_msg" style="display:none;" class="form-group">
                                    <span  style="color:red;">Maximum Cue Point is One hour.</span>
                                </div>
                </div>
            </div><!-- /.row - inside box -->
            </div><!-- /.box-body -->
        </div><!-- /.box -->
        </section>
   
   
    <section class="col-lg-6 infoDiv">
        <!-- Box (with bar chart) -->
        <div class="box box-danger">
            <div class="box-header">           
            <h3 class="box-title">To Add Cue Point</h3>
            </div><!-- /.box-header -->
            <div class="box-body no-padding">
            <div class="row">
                <div class="col-sm-10">
                                Click Cue point button in the video player or click anywhere in the timeline below.
                </div>
            </div><!-- /.row - inside box -->
            </div><!-- /.box-body -->
        </div><!-- /.box -->
        </section>
   
    <section class="col-lg-5 playerDiv" style='float:right;'>
        <!-- Box (with bar chart) -->
        <div class="box box-danger">
            <div class="box-header">           
            <h3 class="box-title">Add New Cue Point</h3>
            </div><!-- /.box-header -->
            <div class="box-body no-padding">
            <div class="row">
                <div class="col-sm-10">
                               
        <div id="prevElement1" class="innerResponse" >
        </div>

        <div class="box-footer">
        <button class="btn btn-primary" onclick='play()' type="button" name="cue_point" id="cue_point">Cue points</button>
        </div>
                </div>
            </div><!-- /.row - inside box -->
            </div><!-- /.box-body -->
        </div><!-- /.box -->
        </section>
    </div>
</div>
<script>
   
    function play()
    {        
       jwplayer().play();
       state = jwplayer().getState();
       //alert(state);
       if (state==="PAUSED") {
        //alert(jwplayer().getPosition());
        console.log(jwplayer().getPosition());
        var pos = Math.floor(jwplayer().getPosition());
        //pos.split(".");
        //console.log(pos);
                    var sectToTime = secTotime(pos);
                    var splitArrTime = sectToTime.split(":");
                    $(".mm").val(splitArrTime[0]);
                    $(".ss").val(splitArrTime[1]);
                    var from_percentage = pos *(0.05301561);
                    var finalPercentage = from_percentage.toFixed(5);
                    var removeClass = "append_"+pos;
                    //var num = secTotime(v.cue_points);
                    $(".infoDiv,#update,#delete").hide();
                    $(".addCueDiv,#add").show();
                    document.getElementById('closeClickEvent').style.pointerEvents = 'none';
                    $('#text').val(pos);
                    
                    $('#percentage').val(finalPercentage);
                    $(".irs").append("<span class='irs-single mybar1 "+ removeClass +"' style='left: "+finalPercentage +"%;'>"+  sectToTime +"</span>");
                    $(".irs-with-grid").append("<span class='irs-bar mybar1 "+ removeClass +"' style='width:"+ finalPercentage +"%'></span>");
                    $(".irs-with-grid").append("<span class='irs-slider single mybar1 "+ removeClass +"' style='left: "+ finalPercentage +"%;'></span>");         
       }
    }
    //--set video player --//
    

</script>
</div>

    
    <!--<div class="popOver">
    <div id="innerHtml">
        <h5 style="font: 12px">Add New Cue Point</h5>
        <input type="hidden" value="" name="inialValPoint" id="inialValPoint"> 
        <input type="hidden" value="" name="inialValPercentage" id="inialValPercentage">
        <div class='timeDiv' style="padding: 0px 10px 0px 10px">
            Timecode(hh:mm:ss:ms)<br>
            <input class="hh" name="hh" id="hh"> :
            <input class="mm" name="mm" id="mm"> :
            <input class="ss" name="ss" id="ss"> :
            <input class="ms" name="ms" id="ms">
        </div>
        <span> Name (Optional)</span>
        <input type="text" id="cueName" name="cueName"><br><br>
        <input type="submit" name="add" id="add" value="Add"><input type="submit" name="update" id="update" value="Update">&nbsp;<a href="#" id="cancel" class="cancel">cancel</a>
        
        
    
</div>
</div>-->
                            <!-- .table - Uses sparkline charts-->
                            <table class="table table-striped">
                                <input type="hidden" name="maxDuration" id="maxDuration" value="">
                                <input type="hidden" name="addFlag" id="addFlag" value="0">
                                <input type="hidden" name="updateFlag" id="updateFlag" value="0">                               
                                <input type="hidden" name="cancelFlag" id="cancelFlag" value="0">
                                <tbody>
                                <tr>
                                    <th width='5%' style="border-right: 1px solid gray;">Sr No.</th>
                                    <th width='8%' style="border-right: 1px solid gray;">Content</th>
                                    <th width="60%" style="border-right: 1px solid gray;padding-left: 10px" class="loaderheader"><div id="closeClickEvent" class="tester"><div>
        <input type="text" id="range" value="" name="range" />
    </div>

</div></th>							
				</tr>

			        </tbody></table>
                        </div>
		    </div>
                            </div>
                        
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal -->
</div>
<style>
    /*.popOver
    {
           width: 300px;
           height: 300px;
           border: 1px solid gray;
           background: aliceblue;
           margin: 0px;
           padding: 0px;
           display: none;
    }
    .timeDiv input
    {
          width: 25px;  
        
    }*/
  /*.irs-slider {
    background-color: gray!important;
    background-position: 0 -120px;
    height: 89px!important;
    top: 22px;
    width: 16px;
}*/
    .progress.xs {
    height: 20px;
}
.progress {
    background-color: #f5f5f5;
    border-radius: 4px;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1) inset;
    height: 20px;
    margin-bottom: 20px;
    overflow: hidden;
    width: 100%;
    //padding-left: 8px;
}
</style>
<script>
    $(document).ready(function (){
        $('#tester').on('hidden.bs.modal', function () {
   location.reload();
})
    });
</script>