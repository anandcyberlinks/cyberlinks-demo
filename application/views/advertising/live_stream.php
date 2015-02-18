<script type="text/javascript">
    $(function () { $("[data-toggle='tooltip']").tooltip(); });
</script>
<div class="wrapper row-offcanvas row-offcanvas-left">
    <!-- Right side column. Contains the navbar and content of the page -->
    <aside class="right-side"> 
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
                <div class="row">
                    <!-- left column -->
                    <div class="col-md-12">
                        <!-- general form elements -->
                        <div class="box box-primary">
                            <!-- form start -->
                            <form  method="post" action="<?php echo base_url(); ?>advertising/live_stream" onsubmit="return date_check();" id="searchIndexForm" name="searchIndexForm" accept-charset="utf-8">
                                <div style="display:none;"><input type="hidden" name="_method" value="POST"/></div>
                                <div class="box-body">
                                    <div class="row">
                                        <div class="form-group col-lg-4">
                                            <div class="input text">
                                                <label for=""><?php echo $welcome->loadPo('Title') ?></label>
                                                <input type="text" name="content_title_live" id="content_title" class="form-control" value="<?php echo (isset($search['content_title_live'])) ? $search['content_title_live'] : ''; ?>" placeholder="<?php echo $welcome->loadPo('Title') ?>">
                                            </div>
                                        </div>
                                        <div class="form-group col-lg-4">
                                            <div class="input select">
                                                <label for="searchCategory"><?php echo $welcome->loadPo('Category') ?></label>
                                                <select name="category_live" class="form-control" placeholder="<?php echo $welcome->loadPo('Category') ?>" id="searchCategory">
                                                    <option value=""><?php echo $welcome->loadPo('Select') ?></option>
                                                    <?php foreach ($category as $key=>$val) { ?>
                                                        <option value="<?php echo $val->category; ?>" <?php
                                                        if (isset($search['category_live'])) {
                                                            if ($val->category == $search['category_live']) {
                                                                echo 'selected';
                                                            }
                                                        }
                                                        ?>  ><?php echo $val->category ?></option>
                                                            <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-4">
                                            <div class="input text">
                                                <label for="url"><?php echo $welcome->loadPo('Start Date') ?></label>
                                                <input type="text" class="form-control"  id="datepickerstart" name="datepickerstart_live" placeholder="<?php echo $welcome->loadPo('Start Date') ?>" value="<?php echo (isset($search['datepickerstart_live'])) ? $search['datepickerstart_live'] : ''; ?>" >											
                                            </div>
                                        </div>
                                        <div class="form-group col-lg-4">
                                            <div class="input text">
                                                <label for="url"><?php echo $welcome->loadPo('End Date') ?></label>
                                                <input type="text" class="form-control"  id="datepickerend" name="datepickerend_live" placeholder="<?php echo $welcome->loadPo('End Date') ?>" value="<?php echo (isset($search['datepickerend_live'])) ? $search['datepickerend_live'] : ''; ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- /.box-body -->
                                <div class="box-footer">
                                        <!--	<input type="text" id="hddstarddt" name="hddstarddt" value="<?php echo @$_POST['hddstarddt'] ?>"> -->
                                    <button type="submit" name="submit" value="Search"class="btn btn-primary"><?php echo $welcome->loadPo('Search') ?></button>
                                    <button type="submit" name="reset" value="Reset"class="btn btn-primary"><?php echo $welcome->loadPo('Reset') ?></button>
                                </div>
                            </form>
                        </div><!-- /.box -->
                    </div><!--/.col (left) -->
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="box">
                            <div class="box-body table-responsive">
                                <div><a id="linkClicklive"   data-toggle="modal" data-backdrop="static" href="#tester" data-toggle="modal" value='Edit'>Edit</a>

                                    </div>
                                <table id="example2" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>select</th>
                                            <th width="15px"><?php echo $welcome->loadPo('Channel') ?></th>
                                            
                                            <th><?php echo $welcome->loadPo('Category') ?></th>
                                            <th><?php echo $welcome->loadPo('User') ?></th>
                                            <th><?php echo $welcome->loadPo('Status') ?></th>
                                            <!--th><?php //echo $welcome->loadPo('Duration') ?></th-->
                                            <th><?php echo $welcome->loadPo('Preview') ?></th>
                                            <th><?php echo $welcome->loadPo('Publish Date') ?></th>
                                            
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php foreach ($result as $value) { ?>
                                        <tr id="<?php echo $value->id ?>">
                                            <td><input type='checkbox' name='video[]' class="video" value="<?php echo $value->id; ?>" channel="<?php echo $value->name; ?>" channel_id="<?php echo $value->channel_id; ?>" thumbnail="<?php echo $value->thumbnail_url; ?>"></td>
                                                <td  width="350"><?php echo strlen($value->name) > 40 ?  substr($value->name,0,40).'...' : $value->name; ?></td>
                                                <td><?php echo $value->category; ?></td>
                                                <td><?php echo $value->username; ?></td>
                                                <td><?php if ($value->status == 1) { ?>
                                                        <img src="<?php echo base_url(); ?>assets/img/test-pass-icon.png" alt="Active" />
                                                    <?php } else { ?>
                                                        <img src="<?php echo base_url(); ?>assets/img/test-fail-icon.png" alt="Active" />
                                                    <?php } ?></td>
                                                <!--td><?php //echo time_from_seconds($value->duration); ?></td-->                                                
                                                <td style='text-align:center'>
                                                    <?php if(in_array(@$value->minetype,array('video/wmv','video/avi'))) { ?>
                                                    --
                                                    <?php } else { ?>
                                                    <a class="prev_video" href="#myModal" data-backdrop="static" data-toggle="modal" data-img-url="<?php echo $value->file; ?>">Preview</a>
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
                                    <div><a id="linkClicklive"   data-toggle="modal" data-backdrop="static" href="#tester" data-toggle="modal" value='Edit'>Edit</a>

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
    <div class="modal-dialog" style="width:75%">
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
                               
                                <div class="box-body no-padding">
			<div class="table-responsive">
                                <input type="hidden" name="text" id="text">
    <input type="hidden" name="percentage" id="percentage">
<div class="popOver1">
    <div class="content">
     <div class="row">
    <section class="col-lg-6">
        <!-- Box (with bar chart) -->
        <div class="box box-danger">
            <div class="box-header">           
            <h3 class="box-title">Add New Cue Point</h3>
            </div><!-- /.box-header -->
            <div class="box-body no-padding">
            <div class="row">
                <div class="col-sm-10" style="margin-left:10px">
                    <form action="" id="addcue_form">
                    <input type="hidden" value="" name="inialValPoint" id="inialValPoint"> 
                    <input type="hidden" value="" name="inialValPercentage" id="inialValPercentage">
                    <div class="form-group">                        
                     <label style="display:block;" for="exampleInputEmail1">Timecode(hh:mm:ss)</label>
                    <div class="input_fields_wrap">
                        <input maxlength=3 style='width:35px;display:inline' type="text" name="hh[]" id="hh_1" placeholder="hh" value="00" class="form-control input-sm hh">:
                    <input maxlength=2 style='width:35px;display:inline;' type="text" name="mm[]" id="mm_1" placeholder="mm" value="00" class="form-control input-sm mm">:                                    
                    <input maxlength=2 style='width:35px;display:inline' type="text" name="ss[]" id="ss_1" placeholder="ss" value="00" class="form-control input-sm ss">
                    <button class="add_field_button">Add More</button>
                    <span id="errTime_1" style="color:red; display: none;">Please fill valid time format.</span>
                    </div>
                    </div>
                    <div class="form-group">
                    <label for="exampleInputEmail1">Name (optional)</label>
                    <input type="email" id="cueName" name="cueName" placeholder="Name" id="exampleInputEmail1" class="form-control">
                    </div>
                    <div class="box-footer">
                    <button class="btn btn-primary" type="submit" name="adds" id="adds" onclick="return addCuePoints();">Save</button>&nbsp;<a href="<?php echo base_url()?>advertising/live_stream" id="cancel" class="cancel">cancel</a>                    
                    </div>
                    </form>         
                    <div id="innerHtml">
        <span id="success11" style="color:green; display: none;">Cue Points added successfully.</span>        
    </div>
                </div>
            </div>
            </div>
        </div>
    </section>
    <section class="col-lg-6">
        <!-- Box (with bar chart) -->
        <div class="box box-danger">
            <div class="box-header">           
                <h3 class="box-title">Cue Points <span id="cue_title"></span></h3>
            </div><!-- /.box-header -->
            <div class="box-body no-padding">
            <div class="row">
                <div class="col-sm-10" style="margin-left:10px">
                    <div id="innerHtmlPoints">
                        
                    </div>
                </div>
            </div>
            </div>
        </div>
    </section>
    </div>
    </div>     
</div>
                            <!-- .table - Uses sparkline charts-->
                            <table class="table table-striped">
                                <input type="hidden" name="totalCuePoints" id="totalCuePoints" value="">
                                <input type="hidden" name="maxDuration" id="maxDuration" value="">
                                <input type="hidden" name="addFlag" id="addFlag" value="0">
                                <input type="hidden" name="cancelFlag" id="cancelFlag" value="0">
                                <tbody>
                                <tr>
                                    <th width='5%' style="border-right: 1px solid gray;">Sr No.</th>
                                    <th width='5%' style="border-right: 1px solid gray;">Thumbnail</th>
                                    <th width='30%' style="border-right: 1px solid gray;">Channel Name</th>
                                    <th width='10%' style="border-right: 1px solid gray;">Action</th>
        <!--<input type="text" id="range" value="" name="range" /> -->
							
				</tr>
			        </tbody></table>
                        </div>
		    </div>
                            </div>
                        
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal -->
</div>

<script type="text/javascript">
    $(document).ready(function() {
    var max_fields      = <?php echo MAX_CUEPOINTS_FIELDS; ?>; //maximum input boxes allowed
    var wrapper         = $(".input_fields_wrap"); //Fields wrapper
    var add_button      = $(".add_field_button"); //Add button ID
   
    var x = 1; //initlal text box count
    $(add_button).click(function(e){ //on add input button click
        e.preventDefault();
        if(document.getElementById('totalCuePoints').value!==''){
            x = parseInt(document.getElementById('totalCuePoints').value);
            document.getElementById('totalCuePoints').value = '';
        }
        
        if(x < max_fields){
            x++; //text box increment
            
            //$(wrapper).append('<div>Timecode(hh:mm:ss) <input class="hh" name="hh[]"> :<input class="mm" name="mm[]"> : <input class="ss" name="ss[]"><a href="#" class="remove_field">Remove</a> <span id="errTime_'+x+'" style="color:red; display: none;">Please fill valid time format.</span></div>'); //add input box
        var htm = '<div id="timecode_'+x+'"><input maxlength=3 style="width:35px;display:inline" type="text" name="hh[]" id="hh_'+x+'" placeholder="hh" value="00" class="form-control input-sm hh">: ';
      htm +='<input maxlength=2 style="width:35px;display:inline;" type="text" name="mm[]" id="mm_'+x+'" placeholder="mm" value="00" class="form-control input-sm mm">: '; 
      htm += '<input maxlength=2 style="width:35px;display:inline" type="text" name="ss[]" id="ss_'+x+'" placeholder="ss" value="00" class="form-control input-sm ss">';                    
    htm +='<a href="#" class="remove_field">Remove</a><span id="errTime_'+x+'" style="color:red; display: none;">Please fill valid time format.</span></div> ';
    //console.log(htm);
    $(wrapper).append(htm);
        }
    });
   
    $(wrapper).on("click",".remove_field", function(e){ //user click on remove text
        e.preventDefault(); $(this).parent('div').remove(); x--;
    })
});

function addCuePoints(){

    var channel_ids = $('#channel_ids').val();

    var cuePoints = [];
    var myForm = document.forms.addcue_form;
    //var hhTest =  $("#hh").val();
    var hhControls = myForm.elements['hh[]'];
    var mmControls = myForm.elements['mm[]'];
    var ssControls = myForm.elements['ss[]'];
    //console.log(hhTest+"=================>"+hhTest.length);
    
    if(typeof hhControls.length==="undefined"){
        var hh = hhControls.value;
        var mm = mmControls.value;
        var ss = ssControls.value;
        var timeString = hh+':'+mm+':'+ss;
       
        if(IsValidTime(timeString)==false){
            $("#errTime_1").show();
            return false;
        }else{
            $("#errTime_1").hide();
            cuePoints.push(((hh*60*60)+(mm*60)+(ss*1)));
            saveLiveCuePoints(channel_ids,cuePoints);
        }
    }else{
        var a = 0;
        for (var i = 0; i < hhControls.length; i++) {
            var hh = hhControls[i].value;
            var mm = mmControls[i].value;
            var ss = ssControls[i].value;
            var timeString = hh+':'+mm+':'+ss;
            a = a+1;    
            if(IsValidTime(timeString)==false){
                
                $("#errTime_"+a).show();
                return false;
            }else{                
                $("#errTime_"+a).hide();
                cuePoints.push(((hh*60*60)+(mm*60)+(ss*1)));
            }
        }
        saveLiveCuePoints(channel_ids,cuePoints);
    }
    
    return false;
}


function IsValidTime(timeString)
{
    var pattern = /^(?:1[0-2]|0?[0-9]):[0-5][0-9]:[0-5][0-9]$/;
    if (!timeString.match(pattern)){
        return false;
    }
    return true;
}

function saveLiveCuePoints(channel_ids,cuePoints){
    $.ajax({
            type: "POST",
            url: baseurl + "advertising/inserCuePointLiveStream",
            data: {"channel_ids" : JSON.parse(channel_ids),"cue_points" : cuePoints,"cue_name" : $('#cueName').val()}  ,
            sync: true,
            success: function (data)
            {
                $("#success11").show();
                
                setTimeout(function() {
                       $("#tester").hide();
                       location.reload();
                }, 1000);
                
            } 
        });
}

function showPoints(channel_id,channel_name){
    
    $.ajax({
            type: "POST",
            url: baseurl + "advertising/channelCuePoints",
            data: {"channel_id" : channel_id},
            sync: true,
            success: function (data)
            {
                $.ajax({
                    type: "POST",
                    url: baseurl + "advertising/editChannelCuePoints",
                    data: {"channel_id" : channel_id},
                    sync: true,
                    success: function (data1)
                    {
                        form_reset();
                        editForm(data1);
                    } 
                });
                
                document.getElementById('cue_title').innerHTML = 'of '+channel_name;
                document.getElementById('innerHtmlPoints').innerHTML = data;
                
            } 
        });
        return false;
    
}

function editForm(data1){
    var cueData = jQuery.parseJSON(data1);
    document.getElementById('totalCuePoints').value = Object.keys(cueData).length;
    
    var max_fields      = <?php echo MAX_CUEPOINTS_FIELDS; ?>; //maximum input boxes allowed
    var wrapper         = $(".input_fields_wrap"); //Fields wrapper
    var add_button      = $(".add_field_button"); //Add button ID
    
    
    
    for (i = 1; i <= Object.keys(cueData).length; i++) {
        cueTitle = cueData[i].title;
        cueTime = cueData[i].cuepoint.split(':');
        hh = cueTime['0']; 
        mm = cueTime['1']; 
        ss = cueTime['2'];
    
        if(i < max_fields){ //max input box allowed
            
            if(i==1){
                $('#hh_1').val(hh);
                $('#mm_1').val(mm);
                $('#ss_1').val(ss);
            }else{
                var htm = '<div id="timecode_'+i+'"><input maxlength=3 style="width:35px;display:inline" type="text" name="hh[]" id="hh_'+i+'" value="'+hh+'" class="form-control input-sm hh">: ';
                htm +='<input maxlength=2 style="width:35px;display:inline;" type="text" name="mm[]" id="mm_'+i+'" value="'+mm+'" class="form-control input-sm mm">: '; 
                htm += '<input maxlength=2 style="width:35px;display:inline" type="text" name="ss[]" id="ss_'+i+'" value="'+ss+'" class="form-control input-sm ss">';                    
                htm +='<a href="#" class="remove_field">Remove</a><span id="errTime_'+i+'" style="color:red; display: none;">Please fill valid time format.</span></div> ';

                $(wrapper).append(htm);
            }
            $('#cueName').val(cueTitle);
        }
   
        $(wrapper).on("click",".remove_field", function(e){ //user click on remove text
            e.preventDefault(); $(this).parent('div').remove(); x--;
        })
        
    }
    
}

function form_reset(){
    var max_fields      = <?php echo MAX_CUEPOINTS_FIELDS; ?>; //maximum input boxes allowed
    $('#hh_1').val('');;
    $('#mm_1').val('');;
    $('#ss_1').val('');;
    $('#cueName').val('');;
    
    for (i = 2; i <= max_fields; i++) {
        
        $( "#timecode_"+i ).remove();
        $( "#errTime_"+i ).remove();
        $( ".remove_field" ).remove();
    }
}
    </script>
