<div class="tab-pane active" id="tab_1">
    <div class="box box-solid">
        <div class="box-body">
            <div class="row">
                   <form id="metadata" name="metadata" action="" method="post">
			
			  
 			<?php // print_r($advance);

			foreach($advance as $advance_val){
			?>
			<div class="form-group col-lg-12">
                            <label for="exampleInputEmail1"><?php echo $advance_val->field_title; ?>:-</label>
                            <?php if($advance_val->field_type=="text"){?>
			    <input name="<?php echo $advance_val->field_name;?>" class="form-control" id="<?php echo $advance_val->field_name;?>" type="text" placeholder="Enter Content Title"  value=""/>
			 <?php } else if($advance_val->field_type=="textarea"){?>
			  <textarea class="textarea" placeholder="Place some text here" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;" name="<?php echo $advance_val->field_name;?>"> </textarea>
                        <?php } else if(($advance_val->field_type=="radio")||($advance_val->field_type=="checkbox")){
			$arrRadioOption  = explode(",", $advance_val->field_options);
			$count = count($arrRadioOption);
			for($y=0;$y<$count;$y++){
			?>
			&nbsp;&nbsp;<input type="<?php echo $advance_val->field_type;?>"  name="<?php echo $welcome->loadPo('Status') ?>"  />&nbsp;&nbsp;<?php echo $arrRadioOption[$y];
			}
			 ?>
			 <?php }?>
                         <?php echo form_error($advance_val->field_name, '<span class="text-danger">', '</span>');?>
                        </div><?php
			/* echo $advance_val->field_title;
			//echo "<br>";
			echo $advance_val->field_name;
			//echo "<br>";
			echo $advance_val->field_type;
			//echo "<br>";
			echo $advance_val->field_options;
			//echo "<br>";
			echo $advance_val->field_validate;
			//echo "<hr>";
			//echo $advance_val->status;
			*/

		       }
                      ?>
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
