<?php if(count($advance)!='0'){ ?>
<div class="tab-pane active" id="tab_1">
    <div class="box box-solid">
        <div class="box-body">
            <div class="row">
                   <form id="metadata" name="metadata" action="" method="post">
			<input type="hidden" name="cid" value="<?php echo $_REQUEST['action']?>"/>
			 <input type="hidden" name="curl" value="<?php echo current_full_url() ?>"> 
 			<?php  foreach($advance as $advance_val){
			    if(count($fvalue[$advance_val->field_id])!=0){
			     $value_field[$advance_val->field_id] = $fvalue[$advance_val->field_id][0]->value;
			    }else {
				$value_field[$advance_val->field_id] ="";
			    } 
			     
			?>
			<div class="form-group col-lg-12">
                            <label for="exampleInputEmail1"><?php echo $advance_val->field_title; ?>:-</label>
                            <?php if($advance_val->field_type=="text"){?>
			    <input name="<?php echo $advance_val->field_id;?>" class="form-control" id="<?php echo $advance_val->field_name;?>" type="text" placeholder="Enter Content Title"   value="<?php echo $value_field[$advance_val->field_id];?>" <?php if($advance_val->field_validate == 1){ echo "required";} ?> />
			 <?php } else if($advance_val->field_type=="textarea"){?>
			  <textarea class="textarea" placeholder="Place some text here" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;" name="<?php echo $advance_val->field_id;?>" id="<?php echo $advance_val->field_name;?>"> </textarea>
                        <?php }  else if(($advance_val->field_type=="radio")||($advance_val->field_type=="checkbox")){
			$arrRadioOption  = explode(",", $advance_val->field_options);
			$count = count($arrRadioOption);
			 for($y=0;$y<$count;$y++){
			?>
	    &nbsp;&nbsp;<input <?php  if( $value_field[$advance_val->field_id]==$arrRadioOption[$y]){?> checked <?php }?> type="<?php echo $advance_val->field_type;?>" id="<?php echo $advance_val->field_name;?>"  name="<?php echo $advance_val->field_id;?>"  value="<?php echo $arrRadioOption[$y];?>"   <?php if($advance_val->field_validate == 1){ echo "required";} ?>/>&nbsp;&nbsp;<?php echo $arrRadioOption[$y];
			} 
			 ?>
			 <?php }  ?>
                         <?php echo form_error($advance_val->field_name, '<span class="text-danger">', '</span>');?> 
                        </div><?php    }   ?>
		      	<div class="form-group col-lg-12">
		      	    <input type="submit" name="submit" value="Submit" class="btn btn-success">
			</div>
	           </form>
    </div>
</div><!-- /.tab-content -->
</div><!-- nav-tabs-custom -->
</div><!-- /.col -->
</div> <!-- /.row -->
</div>
</section>
</aside>
</div>

<!--  this div for  jwplyer reponce -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" 
     aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" 
                        data-dismiss="modal" aria-hidden="true" onclick='stopvideo()'>
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
<?php }else{
    echo "No Dinamic Fields/Forms Available"; } ?>
