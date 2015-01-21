<style>
    .error{
        color: red;
    }
</style>
<div class="tab-pane active" id="tab_1">
    <div class="box box-solid">
        <div class="box-body">
            <div class="row">
                <form id="registerId" name="metadata" action="" method="post">
                        <div class="form-group col-lg-6">
                            <label for="exampleInputEmail1"><?php echo $welcome->loadPo('Title') ?></label>
                            <input name="content_title" class="form-control" id="content_title" type="text" placeholder="Enter Content Title"  value="<?php if(isset($result['ad_title'])) { echo $result['ad_title']; } ?>"/>
                            <?php echo form_error('content_title', '<span class="text-danger">', '</span>'); ?>
                        </div>
                        <div class="form-group col-lg-6">
                            <label for="exampleInputEmail1"><?php echo $welcome->loadPo('Category') ?></label>
                            <select name="content_category" class="form-control" id="content_title" type="text" placeholder="Enter Content Title">						
                                <option value="">--<?php echo $welcome->loadPo('Category') . ' ' . $welcome->loadPo('Select') ?>--</option>
                                <?php foreach($category as $key=>$val) { ?>
                                    <option value="<?php echo $key; ?>" <?php echo (isset($result['category']) && $result['category'] == $key) ? "selected='selected'" : ''; ?> ><?php echo $val; ?></option>
                                    <?php echo $key ?>"><?php echo  $val ?></option>-->
                                <?php } ?>										
                            </select>
                            <?php echo form_error('content_category', '<span class="text-danger">', '</span>'); ?>
                        </div>
                        <div class="form-group col-lg-12">
                            <label for="exampleInputEmail1"><?php echo $welcome->loadPo('Description') ?></label>
                            <textarea class="textarea" placeholder="Place some text here" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;" name="description"> <?php if (isset($result['ad_desc']) && $result['ad_desc'] != "") {
                            echo $result['ad_desc'];
                        } else {
                            echo set_value('description');
                        } ?></textarea>  
                            <?php echo form_error('description', '<span class="text-danger">', '</span>'); ?>
                        </div>
                        <div class="form-group col-lg-12">
                            <label for="exampleInputEmail1"><?php echo $welcome->loadPo('Keywords') ?></label>
                            <input name="tags" class="form-control" id="myTags" type="text" placeholder="Enter keywords"  value="<?php echo $result['keywords']; ?> " /> 
			    <?php echo form_error('content_keyword', '<span class="text-danger">', '</span>'); ?>
                        </div>
                       
                        <div class="form-group col-lg-12">
                            <label>
                                <input type="checkbox"  name="status"<?php if (isset($result['status']) && $result['status'] == 1) {
        echo " checked";
    } else {
        echo "";
    } ?> />&nbsp;&nbsp;<?php echo $welcome->loadPo('Status') ?> 
                            </label>
                        </div>
                        <div class="form-group col-lg-6">
                            <label for="age_group_from"><?php echo $welcome->loadPo('Age Group from') ?></label>
                            <input name="age_group_from" class="form-control" id="age_group_from" type="text" placeholder="Enter Age From"  value="<?php if(isset($result['age_group_from'])) { echo $result['age_group_from']; }else{echo '0'; } ?>" numeric />
                            <?php echo form_error('age_group_from', '<span class="text-danger">', '</span>'); ?>
                        </div>
                        <div class="form-group col-lg-6">
                            <label for="age_group_to"><?php echo $welcome->loadPo('Age Group To') ?></label>
                            <input name="age_group_to" class="form-control greaterThan" id="age_group_to" type="text" placeholder="Enter Age To"  value="<?php if(isset($result['age_group_to'])) { echo $result['age_group_to']; }else{echo '0'; } ?>" data-min="age_group_from" />
                            <?php echo form_error('age_group_to', '<span class="text-danger">', '</span>'); ?>
                        </div>
            </div>
        </div>
        <div class="box-footer">
            <button class="btn btn-primary btn-sm" type="submit" name="submit" value="Update"><?php echo $welcome->loadPo('Update') ?></button>
            <a href="<?php echo base_url(); ?>ads" class="btn btn-default"><?php echo $welcome->loadPo('Cancel') ?></a>           
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