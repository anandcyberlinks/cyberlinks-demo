<div id="tab_4" class="tab-pane active">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-solid">				
                <div class="box-header">
                    <div class="box-tools">
                        <button class="btn btn-warning" type="button" data-toggle="dropdown"><?php echo $welcome->loadPo('Add') . " " . $welcome->loadPo('Thumbnail'); ?>&nbsp;&nbsp;<span class="fa fa-caret-down"></span></button>
                        <ul class="dropdown-menu" role="menu">
                            <li><a class="thumb_upload" href="#myModal1" data-toggle="modal" data-backdrop="static"  content_id="<?php echo $content_id; ?>"><?php echo $welcome->loadPo('Upload'); ?></a></li>								
                            <?php /* ?><li><a class="thumb_crop" content_id="<?php echo $content_id; ?>" href="#myModal2" data-backdrop="static" data-toggle="modal" defThumbVal="<?php echo $defaultThumbInfo; ?>" ><?php echo $welcome->loadPo('New') . " " . $welcome->loadPo('Crop'); ?> </a></li>								
                            <li><a class="thumb_grab" href="#myModal3" data-backdrop="static"  data-toggle="modal" data-img-url="<?php  echo amazonFileUrl.$videoInfo; ?>" ><?php echo $welcome->loadPo('Grab') . " " . $welcome->loadPo('from') . " " . $welcome->loadPo('Video'); ?></a></li><?php */ ?>
                        </ul>
                    </div>
                </div>
                <div class="box-body">
                    <table class="table">
                        <tbody>
                            <tr>
                                <th style="width: 2%">#</th>
                                <th style="width: 22%"><?php echo $welcome->loadPo('Thumbnail'); ?></th>
                                <th style="width: 12.5%"><?php echo $welcome->loadPo('Name'); ?></th>
                                <th style="width: 12.5%"><?php echo $welcome->loadPo('Type'); ?></th>
                                <th style="width: 12.5%"><?php echo $welcome->loadPo('Dimensions'); ?></th>
                                <th style="width: 12.5%"><?php echo $welcome->loadPo('Size') . "[KB]"; ?></th>
                                <th style="width: 26%"><?php echo $welcome->loadPo('Action'); ?></th>
                            </tr>						 
                            <?php
                            $i = 1;
                            foreach ($thumbnails_info as $thumb_val) {
                                ?>
                                <tr>
                                    <td><?php echo $i; ?>  </td> 
                                    <td> 
                                        <img src="<?php echo baseurl.serverImageRelPath.$thumb_val->name; ?>" width="100" height="100" /> 
                                    </td>
                                    <td><?php echo $thumb_val->name; ?>  </td>
                                    <td><?php echo $thumb_val->minetype; ?>  </td> 
                                    <?php $data = base64_decode($thumb_val->info);
                                    $data_thumb = @unserialize(strip_slashes($data));
                                    ?>
                                    <td><?php echo $data_thumb['width'] . '*' . $data_thumb['height']; ?></td>
                                    <td><?php echo '0.00'; ?></td>
                                    <td>
                                        <a class="btn btn-info btn-sm <?php if ($thumb_val->default_thumbnail == '1') {
                                        echo "disabled";
                                    } else {
                                        
                                    } ?>" href="<?php echo base_url(); ?>video/set_defltimage?cid=<?php echo $content_id; ?>&fid=<?php echo $thumb_val->id; ?>&redirect_url=<?php echo current_full_url(); ?>"><?php echo $welcome->loadPo('Set as Default'); ?></a>
                                        <a class="btn btn-success btn-sm" href="<?php echo base_url(); ?>video/download_thumb?fileName=<?php echo $thumb_val->name; ?>&redirect_url=<?php echo current_full_url(); ?>"><?php echo $welcome->loadPo('Download'); ?></a>
                                        <a class="confirm_delete btn btn-danger btn-sm <?php if ($thumb_val->default_thumbnail == '1') {
                                        echo "disabled";
                                    } else {
                                        
                                    } ?>" href="<?php echo base_url(); ?>video/delele_thumbimage?cid=<?php echo $content_id; ?>&fid=<?php echo $thumb_val->id; ?>&redirect_url=<?php echo current_full_url(); ?>" ><?php echo $welcome->loadPo('Delete'); ?></a></td>
                                </tr>
    <?php $i++;
} ?>
                        </tbody>
                    </table>
                </div><!-- /.box-body -->
            </div><!-- box box-solid -->
        </div><!-- col-md-12 -->
    </div><!-- row -->
</div><!-- /.tab-4 -->
</div><!-- /.tab-content -->
</div><!-- nav-tabs-custom -->
</div><!-- /.col -->
</div> <!-- /.row -->
</div>
</section>
</aside>
</div>

<!-- Model box to upload thumb image starts --->

<div class="modal fade" id="myModal1"  role="dialog"  aria-hidden="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    &times;
                </button>
                <h4 class="modal-title" id="myModalLabel">
<?php echo $welcome->loadPo('Thumbnail') . " : " . $welcome->loadPo('Upload'); ?>
                </h4>
            </div>
            <form action="<?php echo base_url() ?>video/upload_thumb" id="videoUploadForm" class="filse" enctype="multipart/form-data" method="post" accept-charset="utf-8">
                <div class="modal-body">               
                    <div id="prevElement1"></div>
                    <input type="hidden" id="redirect_url" name="redirect_url" value="<?php echo current_full_url(); ?>" />			
                    <div class="box-body">
                        <div class="form-group">
                            <span class="btn btn-default btn-file btn-sm">
<?php echo $welcome->loadPo('Choose') . " " . $welcome->loadPo('Thumbnail'); ?> <input name="thumb_img"  id="thumb_img"  type="file" accept="image/*" />
                            </span>
                        </div>
                    </div>

                    <div id="status" style="color:red;"  class="callout-danger" ></div>
                </div>
                <div class="modal-footer">
                    <input type="submit" name="submit" value="<?php echo $welcome->loadPo('Upload'); ?>" id="thumbImgUpload" class="btn btn-primary">
                    <button class="btn btn-default" data-dismiss="modal" type="button"><?php echo $welcome->loadPo('Close'); ?></button>						
                </div>
            </form>
        </div>

    </div><!-- /.modal-content -->
</div><!-- /.modal -->
<!-- Model box to upload thumb image ends --->

<!-- Model box to crop thumb image starts --->
<div class="modal fade" id="myModal2"  role="dialog"  aria-hidden="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title" id="myModalLabel">
                        <?php echo $welcome->loadPo('Thumbnail') . " : " . $welcome->loadPo('Crop') . " " . $welcome->loadPo('Thumbnail'); ?> 
                </h4>
            </div>
            <div class="modal-body ">  
                <div class="jc-demo-box">  
                    <form action="<?php echo base_url() ?>video/crop_thumb" method="post" onsubmit="return checkCoords();">
<?php if ($defaultThumbInfo == '0') {
    echo "Please Select default image.";
} else { ?>
    <?php foreach ($thumbnails_info as $thumb_val) { ?>
        <?php if ($thumb_val->default_thumbnail == '1') { ?>
                                    <img src="<?php echo amazonFileUrl.$thumb_val->name; ?>" id="cropbox" width="560" height="350"/>
                                    <input type="hidden" id="thumb_imgName" name="thumb_imgName" value="<?php echo $thumb_val->name; ?>" />
            <?php $data = base64_decode($thumb_val->info);
            $data_thumb = @unserialize(strip_slashes($data));
            ?>
                                    <input type="hidden" id="orig_width" name="orig_width" value="<?php echo $data_thumb['width']; ?>" />
                                    <input type="hidden" id="orig_height" name="orig_height" value="<?php echo $data_thumb['height']; ?>" />					<?php } ?>
    <?php } ?>	
<?php } ?>	
                        <!-- This is the form that our event handler fills -->				
                        <input type="hidden" id="redirect_url" name="redirect_url" value="<?php echo current_full_url(); ?>" />			
                        <center>			
                            <div id="prevElement2"></div>
                            <div class="box-body">						
                                <!-- This is the image we're attaching Jcrop to -->			
                                <input type="hidden" id="x" name="x" />
                                <input type="hidden" id="y" name="y" />
                                <input type="hidden" id="w" name="w" />
                                <input type="hidden" id="h" name="h" />						
                            </div>&nbsp;
                            <div class="modal-footer">
                                <input type="submit" name="submit" value="<?php echo $welcome->loadPo('Crop') . " " . $welcome->loadPo('Image'); ?> " class="btn btn-primary">
                                <!--<button type="submit" id="load" class="btn btn-primary btn-sm" data-toggle="modal" data-target=".bs-example-modal-sm" >Upload</button>-->
                                <button class="btn btn-default" data-dismiss="modal" type="button"><?php echo $welcome->loadPo('Close'); ?></button>				</div>
                            </div>
                        </center>
                    </form>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal -->

    <!-- Model box to crop thumb image ends --->
    <script type="text/javascript">
        setInterval(function() {
            var z = (jwplayer("prevElement3").getPosition());
            secondsToTime(z);
        }, 600);
        $('#myModal3').on('hide.bs.modal', function() {
            jwplayer().stop();
        });


    </script>
    <!-- Model box to grab video image starts --->

    <div class="modal fade" id="myModal3" role="dialog"  aria-hidden="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" onclick='stopvideo()'>
                    </button>
                    <h4 class="modal-title" id="myModalLabel">
<?php echo $welcome->loadPo('Grab') . " " . $welcome->loadPo('Video') . " " . $welcome->loadPo('Image'); ?>  
                    </h4>
                </div>
                <div class="modal-body text-center no-padding">        
                    <div id="prevElement3"></div>
                    <div class="box">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-xs-4">
                                    <div class="input text">
                                        <input id="thumbgrabHours" class="form-control" type="text" name="thumbgrabSeconds" value="0">
                                    </div>
                                </div>
                                <div class="col-xs-4">
                                    <div class="input text">
                                        <input id="thumbgrabMinutes" class="form-control" type="text" name="thumbgrabSeconds" value="0">
                                    </div>
                                </div>
                                <div class="col-xs-4">
                                    <div class="input text">
                                        <input id="thumbgrabSeconds" class="form-control" type="text" placeholder="Seconds" name="thumbgrabSeconds" value="0">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer"  style="margin-top:-25px;"           >
                    <a onclick='jwplayer().play()'><button type="submit" id="loadPlay" class="btn btn-primary" ><?php echo $welcome->loadPo('Grab') . " " . $welcome->loadPo('from') . " " . $welcome->loadPo('Video'); ?></button></a>  
                    <button class="btn btn-default" data-dismiss="modal" type="button"><?php echo $welcome->loadPo('Close'); ?></button>				
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal -->
    </div> 
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
            <div class="modal-body">        
                <center>   <div id="prevElement"></div></center>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal -->
</div> 
    <!-- Model box to grab video image ends --->

