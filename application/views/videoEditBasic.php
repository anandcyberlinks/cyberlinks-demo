
<div class="tab-pane active" id="tab_1">
    <div class="box box-solid">
        <div class="box-body">
            <div class="row">
                <form id="metadata" name="metadata" action="" method="post">

                        <div class="form-group col-lg-6">
                            <label for="exampleInputEmail1"><?php echo $welcome->loadPo('Title') ?></label>
                            <input name="content_title" class="form-control" id="content_title" type="text" placeholder="Enter Content Title"  value="<?php if(isset($result['title'])) { echo $result['title']; } ?>"/>
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
                            <textarea class="textarea" placeholder="Place some text here" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;" name="description"> <?php if (isset($result['description']) && $result['description'] != "") {
                            echo $result['description'];
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
						<div class="form-group col-lg-12">
                            <label>
                                <input type="checkbox"  name="feature_video"<?php if (isset($result['feature_video']) && $result['feature_video'] == 1) {
        echo " checked";
    } else {
        echo "";
    } ?> />&nbsp;&nbsp;<?php echo $welcome->loadPo('Feature Video') ?> 
                            </label>
                        </div>

            </div>
        </div>
        <div class="box-footer">
            <button class="btn btn-primary btn-sm" type="submit" name="submit" value="Update"><?php echo $welcome->loadPo('Update') ?></button>
            <a href="<?php echo base_url(); ?>video" class="btn btn-default"><?php echo $welcome->loadPo('Cancel') ?></a>           
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