<!-- Right side column. Contains the navbar and content of the page -->
<aside class="content-wrapper">                
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo $welcome->loadPo('Video') ?> <small><?php echo $welcome->loadPo('Control Panel') ?></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>><i class="fa fa-dashboard"></i> Home</a></li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">

        <div id="content">

            <div class="row">
                <div class="col-md-12">
                    <!-- Custom Tabs -->
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab_1" data-toggle="tab">Basic</a></li>
                            <li><a href="#tab_2" data-toggle="tab">Advanced</a></li>
                            <li><a href="#tab_3" data-toggle="tab">Availability</a></li>
                            <li><a href="#tab_4" data-toggle="tab">Thumbnail</a></li>
                            <li><a href="#tab_5" data-toggle="tab">Flavors</a></li>
                            <li class="pull-right">
                                <a class="dropdown-toggle" data-toggle="dropdown" >
                                    Action<span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li role="presentation">
                                        <?php foreach ($result as $value) { ?>
                                        <td style='text-align:center'><a class="prev_video" href="#myModal" data-toggle="modal" data-img-url="<?php echo base_url(); ?>assets/videos/<?php echo $value->title ?>"><?php echo $welcome->loadPo('Preview') ?></a></td>
                                      
									</li>
                                <li role="presentation">
                                    <a class="confirm" onclick="return delete_video(<?php echo $value->id; ?>);" href="<?php echo base_url() ?>video" ><?php echo $welcome->loadPo('Delete') ?></a>  
                                </li>
                            <?php } ?>
                            <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo base_url() ?>video">Back</a></li>
                        </ul>
                        </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_1">
                                <div class="box box-solid">
                                    <div class="box-body">
                                        <div class="row">
                                            <form id="metadata" name="metadata" action="" method="post">
                                                <?php foreach ($result as $value) { ?>
                                                    <div class="form-group col-lg-6">
                                                        <label for="exampleInputEmail1"><?php echo $welcome->loadPo('Title') ?></label>
                                                        <input name="content_title" class="form-control" id="content_title" type="text" placeholder="Enter Content Title"  value="<?php echo $value->title; ?>"/>
                                                        <?php echo form_error('content_title', '<span class="text-danger">', '</span>'); ?>
                                                    </div>
                                                    <div class="form-group col-lg-6">
                                                        <label for="exampleInputEmail1"><?php echo $welcome->loadPo('Category') ?></label>
                                                        <select 	name="content_category" class="form-control" id="content_title" type="text" placeholder="Enter Content Title"		>						
                                                            <option value="">--Select Category--</option>
                                                            <?php foreach ($category as $value1) { ?>
                                                                <option value="<?php echo $value1->id; ?>" <?php echo ($value->category == $value1->category) ? "selected='selected'" : ''; ?> ><?php echo ucfirst($value1->category); ?></option>
                                                                <!--<option value="<?php echo $value1->id ?>"><?php echo $value1->category ?></option>-->
                                                            <?php } ?>										
                                                        </select>
                                                        <?php echo form_error('content_category', '<span class="text-danger">', '</span>'); ?>
                                                    </div>
                                                    <div class="form-group col-lg-12">
                                                        <label for="exampleInputEmail1"><?php echo $welcome->loadPo('Description') ?></label>
                                                        <textarea class="textarea" placeholder="Place some text here" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;" name="description"> <?php echo$value->description; ?></textarea>  
                                                        <?php echo form_error('description', '<span class="text-danger">', '</span>'); ?>
                                                    </div>
                                                    <div class="form-group col-lg-6">
                                                        <label for="exampleInputEmail1"><?php echo $welcome->loadPo('Keywords') ?></label>
                                                        <input name="content_keyword" class="form-control" id="content_title" type="text" placeholder="Enter Content Title"  value="<?php echo $value->keyword; ?>" />
                                                        <ul id="myTags">
                                                            <!-- Existing list items will be pre-added to the tags -->

                                                        </ul>
                                                        <?php echo form_error('content_keyword', '<span class="text-danger">', '</span>'); ?>
                                                    </div>
                                                    <div class="form-group col-lg-12">
                                                        <label>
                                                            <input type="checkbox"  name="status"<?php
                                                            if ($value->status == 1) {
                                                                echo " checked";
                                                            } else {
                                                                echo "";
                                                            }
                                                            ?> /><?php echo $welcome->loadPo('Status') ?> 

                                                        </label>
                                                    </div>
													<div class="form-group col-lg-12">
                                                        <label>
                                                            <input type="checkbox"  name="feature_video"<?php
                                                            if ($value->status == 1) {
                                                                echo " checked";
                                                            } else {
                                                                echo "";
                                                            }
                                                            ?> /><?php echo $welcome->loadPo('Feature Video') ?> 

                                                        </label>
                                                    </div>
                                                <?php } ?>
                                        </div>
                                    </div>
                                    <div class="box-footer">
                                        <button class="btn btn-primary btn-sm" type="submit" name="submit" value="Update"><?php echo $welcome->loadPo('Update') ?></button>
                                        <a href="<?php echo base_url(); ?>video" class="btn btn-default"><?php echo $welcome->loadPo('Cancel') ?></a>      
                                    </div>
                                    </form>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab_2">
                                <div class="row">
                                    <div class="col-md-7">
                                        <div class="box box-solid">
                                            <div class="box-body">
                                                Video player 
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="box box-solid">
                                            <div class="box-body">
                                                <div class="box-group" id="accordion">
                                                    <div class="panel box box-solid">
                                                        <div class="box-header">
                                                            <h4 class="box-title">
                                                                <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                                                                    Video Adds
                                                                </a>
                                                            </h4>
                                                        </div>
                                                        <div id="collapseOne" class="panel-collapse collapse in">
                                                            <div class="box-body">
                                                                Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="panel box box-solid">
                                                        <div class="box-header">
                                                            <h4 class="box-title">
                                                                <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
                                                                    Banner Adds
                                                                </a>
                                                            </h4>
                                                        </div>
                                                        <div id="collapseTwo" class="panel-collapse collapse">
                                                            <div class="box-body">
                                                                <div class="row">
                                                                    <div class="form-group col-lg-1">
                                                                        <input type="checkbox">
                                                                    </div>
                                                                    <div class="form-group col-lg-2">
                                                                        hi this is anand
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>
                                                                        <input type="checkbox"> Check me out
                                                                    </label>
                                                                    <label for="exampleInputEmail1">Email address</label>
                                                                    <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="panel box box-solid">
                                                        <div class="box-header">
                                                            <h4 class="box-title">
                                                                <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
                                                                    Custom Adds
                                                                </a>
                                                            </h4>
                                                        </div>
                                                        <div id="collapseThree" class="panel-collapse collapse">
                                                            <div class="box-body">
                                                                <div class="form-group">
                                                                    <label for="exampleInputFile">Vast File</label>
                                                                    <input type="file" id="exampleInputFile">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="box-footer">
                                                <button class="btn btn-primary btn-sm" type="submit">Save</button>
                                                <button class="btn btn-primary btn-sm" type="submit">Reset</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab_3">
                                <div class="box box-solid">
                                    <div class="box-body">
                                        <div class="row">
                                            <div class="form-group col-lg-6">                                    
                                                <label>
                                                    <input type="radio" name="r2" class="minimal-red" checked/>
                                                    Always
                                                </label>
                                                &nbsp;&nbsp;&nbsp;
                                                <label>
                                                    <input type="radio" name="r2" class="minimal-red"/>
                                                    Period
                                                </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-lg-6">                                    
                                                <label>Start Date</label>
                                                <input name="content_title" class="form-control" id="content_title" type="text" placeholder="Start Date" />
                                            </div>
                                            <div class="form-group col-lg-6">                                    
                                                <label>End Date</label>
                                                <input name="content_title" class="form-control" id="content_title" type="text" placeholder="End Date" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="box-footer">
                                        <button class="btn btn-primary btn-sm" type="submit">Save</button>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab_4">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="box box-solid">
                                            <div class="box-body">
                                                <img src="http://localserver/newsnation/thumbs/53b15caef11ae.jpg" /> 
                                            </div><!-- /.box-body -->
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="box box-solid">
                                            <div class="box-body no-padding">
                                                <table class="table">
                                                    <tbody>
                                                        <tr>
                                                            <td>Filename</td>
                                                            <td>65467984564.jpg</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Size</td>
                                                            <td>56 KB</td>
                                                        </tr>
                                                        <tr>
                                                            <td>MineType</td>
                                                            <td>jpg</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Height</td>
                                                            <td>230px</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Width</td>
                                                            <td>430px</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div><!-- /.box-body -->
                                            <div class="box-footer clearfix">
                                                <div class="pull-right">
                                                    <button id="changeThumbBut" class="btn btn-primary btn-sm">Change Thumbnail</button>
                                                </div>
                                            </div><!-- box-footer -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab_5">
                                <div class="box box-solid">
                                    <div class="box-body no-padding">
                                        <table class="table">
                                            <tbody>
                                                <tr>
                                                    <th><?php echo $welcome->loadPo('Device Name') ?></th>
                                                    <th><?php echo $welcome->loadPo('Flavor Name') ?></th>
                                                    <th><?php echo $welcome->loadPo('Bitrate') ?></th>
                                                    <th><?php echo $welcome->loadPo('Video Bitrate') ?></th>
                                                    <th><?php echo $welcome->loadPo('Audio Bitrate') ?></th>
                                                    <th><?php echo $welcome->loadPo('Resolution') ?></th>
                                                    <th><?php echo $welcome->loadPo('Frame Rate (FPS)') ?></th>
                                                    <th><?php echo $welcome->loadPo('Keyframe Rate') ?></th>
                                                    <th><?php echo $welcome->loadPo('Status') ?></th>
                                                    <th><?php echo $welcome->loadPo('Action') ?></th>
                                                </tr>
                                                <?php foreach ($setting as $value2) { ?>
                                                    <tr>
                                                        <td><?php echo $value2->device_name; ?></td>
                                                        <td><?php echo $value2->flavor_name; ?></td>
                                                        <td><?php echo $value2->bitrate; ?></td>
                                                        <td><?php echo $value2->video_bitrate; ?></td>
                                                        <td><?php echo $value2->audio_bitrate; ?></td>
                                                        <td><?php echo $value2->width . ' * ' . $value2->height; ?></td>
                                                        <td><?php echo $value2->frame_rate; ?></td>
                                                        <td><?php echo $value2->keyframe_rate; ?></td>
                                                        <?php
                                                        switch ($value2->status) {
                                                            case 'inprocess' :
                                                                echo sprintf('<td><span class="%s">inprocess</span></td>', 'label label-primary');
                                                                echo sprintf('<td><a href="%s" class="%s">Cancel</a></td>', base_url() . 'video/testlog?id=' . $value2->id, 'btn btn-primary btn-sm');
                                                                break;
                                                            case 'completed' :
                                                                echo sprintf('<td><span class="%s">complete</span></td>', 'label label-success');
                                                                echo sprintf('<td><a href="%s" class="%s">Delete</a></td>', base_url() . 'video/testlog?id=' . $value2->id, 'btn btn-success btn-sm');
                                                                break;
                                                            case 'pending' :
                                                            default :
                                                                echo sprintf('<td><span class="%s">Pending</span></td>', 'label label-danger');
                                                                echo sprintf('<td><a href="%s" class="%s">Convert</a></td>', base_url() . 'video/testlog?id=' . $value2->id, 'btn btn-primary btn-sm');
                                                                break;
                                                        }
                                                        ?>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div><!-- /.box-body -->
                                </div><!-- /.box -->
                            </div>
                        </div><!-- /.tab-content -->
                    </div><!-- nav-tabs-custom -->
                </div><!-- /.col -->
            </div> <!-- /.row -->


            <!-- Dailog box for image change --->
            <div class="modal fade" id="changeThumbModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                            <h4 class="modal-title" id="myModalLabel">Change Thumbnail</h4>
                        </div>
                        <div class="modal-body no-padding">
                            <div class="nav-tabs-custom ">
                                <ul class="nav nav-tabs">
                                    <li class="active"><a data-toggle="tab" href="#tab_1-1">From Video</a></li>
                                    <li class=""><a data-toggle="tab" href="#tab_2-2">From Url</a></li>
                                    <li class=""><a data-toggle="tab" href="#tab_3-3">Upload Image</a></li>
                                </ul>
                                <div class="tab-content">
                                    <div id="tab_1-1" class="tab-pane active">
                                        <div class="row">
                                            <div class="form-group col-lg-4">
                                                <div class="input text"><label for="hours">Hour</label><input name="data[hours]" class="form-control" placeholder="Hour" type="text" id="hours"/></div>                        </div>
                                            <div class="form-group col-lg-4">
                                                <div class="input text"><label for="miniute">Miniute</label><input name="data[miniute]" class="form-control" placeholder="Miniute" type="text" id="miniute"/></div> 
                                            </div>
                                            <div class="form-group col-lg-4">
                                                <div class="input text"><label for="second">Second</label><input name="data[second]" class="form-control" placeholder="Miniute" type="text" id="second"/></div> 
                                            </div>
                                        </div>
                                    </div><!-- /.tab-pane -->
                                    <div id="tab_2-2" class="tab-pane">
                                        <div class="row">
                                            <div class="form-group col-lg-12">
                                                <div class="input text"><label for="url">Url</label><input name="data[url]" class="form-control" placeholder="Hour" type="text" id="url"/></div>                        </div>
                                        </div>
                                    </div><!-- /.tab-pane -->
                                    <div id="tab_3-3" class="tab-pane">

                                    </div><!-- /.tab-pane -->
                                </div><!-- /.tab-content -->
                            </div>
                        </div>
                        <div class="modal-footer" style="margin-top:-19px;">
                            <button data-dismiss="modal" class="btn btn-primary" type="button">Cancel</button>
                            <button type="submit" class="btn btn-primary">Change</button>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </section><!-- /.content -->
</aside><!-- /.right-side -->
</div><!-- ./wrapper -->







<!-- Dailog box for image change --->
<div class="modal fade" id="changeThumbModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel">Change Thumbnail</h4>
            </div>
            <div class="modal-body no-padding">
                <div class="nav-tabs-custom ">
                    <ul class="nav nav-tabs">
                        <li class="active"><a data-toggle="tab" href="#tab_1-1">From Video</a></li>
                        <li class=""><a data-toggle="tab" href="#tab_2-2">From Url</a></li>
                        <li class=""><a data-toggle="tab" href="#tab_3-3">Upload Image</a></li>
                    </ul>
                    <div class="tab-content">
                        <div id="tab_1-1" class="tab-pane active">
                            <form action="" id="thumbnailtime" name="thumbnailtime" method="post" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="form-group col-lg-4">
                                        <!--  <?php
                                        $videourl = $metadata->from_url;
                                        if ($metadata->video_file_name != "") {
                                            $path = base_url() . 'assets/upload/video/' . $metadata->video_file_name;
                                            $imagepath = base_url() . 'assets/upload/thumbnail/' . $metadata->portal_thumbnail;
                                        } else {
                                            $path = $metadata->from_url;
                                        }
                                        $path = base_url() . 'assets/upload/video/1402305672_Akcent-Stay-With-Me[www.savevid.com].mp4';
                                        ?>
                                                                                                       <div>
                                                                                                               <div id="myElementnew"></div>
                                                                                                       </div>
                                                                                                       <script type="text/javascript">
                                                                                                       jwplayer("myElementnew").setup({
                                                                                                       file: "<?php echo $path; ?>",
                                                                                                       image: "<?php echo $imagepath; ?>",
                                                                                                       width: 341,
                                                                                                       height: 195,
                                                                                                       autostart: false,
                                                                                                       events: {
                                                                                                                       onPause :function(){
                                                                                                                               time = jwplayer("myElementnew").getPosition();
                                                                                                                               //get state
                                                                                                                               secondsToTime(time);
                                                                                                                               //alert(time);
                                                                                                                               //console.log(time);
                                                                                                                       }
                                                                                                               }
                                                                                                       });
                                                                                                       function secondsToTime(secs)
                                                                                                       {
                                                                                                               secs = Math.round(secs);
                                                                                                               var hours = Math.floor(secs / (60 * 60));
               
                                                                                                               var divisor_for_minutes = secs % (60 * 60);
                                                                                                               var minutes = Math.floor(divisor_for_minutes / 60);
               
                                                                                                               var divisor_for_seconds = divisor_for_minutes % 60;
                                                                                                               var seconds = Math.ceil(divisor_for_seconds);
                                                                                                               $("#hours").val(hours);
                                                                                                               $("#miniute").val(minutes);
                                                                                                               $("#second").val(seconds);
                                                                                                               
                                                                                                       } -->

                                        </script>


                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-lg-4">

                                        <input type="text" name="hours" id="hours" class="form-control" placeholder="Hour" label="Hour" readonly="readonly">

                                    </div>
                                    <div class="form-group col-lg-4">

                                        <input type="text" name="miniute" id="miniute" class="form-control" placeholder="Miniute" label="Miniute" readonly="readonly">
                                    </div>
                                    <div class="form-group col-lg-4">

                                        <input type="text" name="second" id="second" class="form-control" placeholder="second" label="second" readonly="readonly">
                                    </div>
                                </div>

                                <div class="modal-footer" style="margin-top:-19px;">
                                    <button data-dismiss="modal" class="btn btn-primary" type="button">Cancel</button>
                                    <button type="submit" class="btn btn-primary" name="update" id="update" value="Savethumbnailtime">Change</button>
                                    <input name="videoid" id="videoid" type="hidden" value=<?php echo $videoid; ?> > 
                                </div>  
                            </form>
                        </div><!-- /.tab-pane -->
                        <div id="tab_2-2" class="tab-pane">
                            <form action="" id="thumbnailurl" name="thumbnailurl" method="post">
                                <div class="row">
                                    <div class="form-group col-lg-12">
                                        <input type="text" name="url" id="url" class="form-control" placeholder="url" label="url">
                                    </div>
                                </div>

                                <div class="modal-footer" style="margin-top:-19px;">
                                    <button data-dismiss="modal" class="btn btn-primary" type="button">Cancel</button>
                                    <button type="submit" class="btn btn-primary" name="update" id="update" value="Savethumbnailurl">Change</button>
                                    <input name="videoid" id="videoid" type="hidden" value=<?php echo $videoid; ?> > 
                                </div>  
                            </form>
                        </div><!-- /.tab-pane -->
                        <div id="tab_3-3" class="tab-pane">
                            <form action="" id="thumbnailurl" name="thumbnailurl" method="post" enctype="multipart/form-data"> 
                                <div class="form-group">
                                    <label for="exampleInputFile">Select Thumbnail</label>
                                    <input type="file" name="capture" id="capture"">
                                    <p class="help-block">Thumbnail file size not max then 2 MB.</p>
                                </div>

                                <div class="modal-footer" style="margin-top:-19px;">
                                    <button data-dismiss="modal" class="btn btn-primary" type="button">Cancel</button>
                                    <button type="submit" class="btn btn-primary" name="update" id="update" value="Savethumbnail">Change</button>
                                    <input name="videoid" id="videoid" type="hidden" value=<?php echo $videoid; ?> > 
                                </div>  
                            </form>
                        </div><!-- /.tab-pane -->
                    </div><!-- /.tab-content -->
                </div>
            </div>

        </div>


    </div>
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
                <center>   <div id="prevElement"><!--<img src="/images/brackets/6teamDouble1.jpg">--></div></center>
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal -->
</div> 