<?php $defaultPlayer = @unserialize(strip_slashes($playerData)); ?>
<div id="tab_4" class="tab-pane active">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-solid">				
                <div class="box-header">
                    <div class="box-tools">
                        <a class="promo_upload" href="#myModal1" data-backdrop="static" data-toggle="modal" data-img-url="<?php echo amazonFileUrl.$videoFileName;?>"  content_id="<?php echo $content_id; ?>">
                            <button class="btn btn-warning" type="button"><?php echo $welcome->loadPo('Add') . " " . $welcome->loadPo('Promo'); ?></button>
                        </a>	
                    </div>
                </div>
                <div class="box-body">
                    <table class="table">
                        <tbody>
                            <tr>
                                <th style="width: 2%">#</th>
                                <th style="width: 22%"><?php echo $welcome->loadPo('Promo'); ?></th>
                                <th style="width: 12.5%"><?php echo $welcome->loadPo('Start') . " " . $welcome->loadPo('Time'); ?></th>
                                <th style="width: 12.5%"><?php echo $welcome->loadPo('End') . " " . $welcome->loadPo('Time'); ?></th>
                                <th style="width: 12.5%"><?php echo $welcome->loadPo('Default'); ?></th>
                                <th style="width: 12.5%"><?php echo $welcome->loadPo('Status'); ?></th>
                                <th style="width: 26%"><?php echo $welcome->loadPo('Action'); ?></th>
                            </tr>						 
                            <?php
                            $i = 1;
                            foreach ($promo_info as $promo_val) {
                                ?>
                                <tr>
                                    <td><?php echo $i; ?>  </td> 
                                    <td> 
                                        <img src="<?php echo base_url(); ?>assets/upload/thumbs/<?php echo $promo_val->name; ?>" width="100" height="100" /> 
                                    </td>
                                    <td><?php echo $promo_val->start_time; ?>  </td>
                                    <td><?php echo $promo_val->end_time; ?>  </td>
                                    <td><?php echo $promo_val->default; ?>  </td>
                                    <td><?php echo $promo_val->status; ?>  </td>
                                    <td>
                                        <a class="btn btn-info btn-sm <?php if ($promo_val->default_thumbnail == '1') {
                                echo "disabled";
                            } else {
                                
                            } ?>" href="<?php echo base_url(); ?>video/set_defltimage?cid=<?php echo $content_id; ?>&fid=<?php echo $promo_val->id; ?>&redirect_url=<?php echo current_full_url(); ?>"><?php echo $welcome->loadPo('Set as Default'); ?></a>
                                        <a class="btn btn-success btn-sm" href="<?php echo base_url(); ?>video/download_thumb?fileName=<?php echo $promo_val->name; ?>&redirect_url=<?php echo current_full_url(); ?>"><?php echo $welcome->loadPo('Download'); ?></a>
                                        <a class="confirm_delete btn btn-danger btn-sm <?php if ($promo_val->default_thumbnail == '1') {
                                echo "disabled";
                            } else {
                                
                            } ?>" href="<?php echo base_url(); ?>video/delele_thumbimage?cid=<?php echo $content_id; ?>&fid=<?php echo $promo_val->id; ?>&redirect_url=<?php echo current_full_url(); ?>" ><?php echo $welcome->loadPo('Delete'); ?></a></td>
                                </tr>
								<?php $i++;
							} 
							?>
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

<script type="text/javascript">
    $(function() {
        var file_path = $('a.promo_upload').attr('data-img-url');
        var file_min_duration = 0;
        var file_max_duration = Math.floor($('#file_duration').val());
        var file_max_duration_format = secondsToTimeGet(file_max_duration);
        $("#start_time_video_div").html('00:00:00');
        $("#end_time_video_div").html(file_max_duration_format);
        $("#start_time_video").val('00:00:00');
        $("#end_time_video").val(file_max_duration_format);
        var duration = 0;
        jwplayer("prevElement1").setup({
            "file": file_path,
            "mute": "<?php echo $defaultPlayer['player']['mute']; ?>",
            "repeat": "<?php echo $defaultPlayer['player']['repeat_video']; ?>",
            "skin": "<?php echo $defaultPlayer['player']['player_skin']; ?>",
            "androidhls": "<?php echo $defaultPlayer['player']['androidhls']; ?>",
            "controls": "<?php echo $defaultPlayer['player']['controls']; ?>",
            "autostart": "<?php echo $defaultPlayer['player']['auto_start']; ?>",
            "width": "<?php echo $defaultPlayer['player']['width']; ?>",
            "height": "<?php echo $defaultPlayer['player']['height']; ?>",
            "aspectratio": "<?php echo $defaultPlayer['player']['player_aspectration']; ?>",
            "captions":
                    {
                        "color": "<?php echo $defaultPlayer['player']['caption_color']; ?>",
                        "fontSize": "<?php echo $defaultPlayer['player']['caption_fontsize']; ?>",
                        "fontfamily": "<?php echo $defaultPlayer['player']['caption_fontfamily']; ?>",
                        "fontOpacity": "<?php echo $defaultPlayer['player']['caption_fontopacity']; ?>",
                        "backgroundColor": "<?php echo $defaultPlayer['player']['caption_backgroundcolor']; ?>",
                        "backgroundOpacity": "<?php echo $defaultPlayer['player']['caption_backgroundopacity']; ?>",
                        "windowColor": "<?php echo $defaultPlayer['player']['caption_windowcolor']; ?>",
                        "windowOpacity": "<?php echo $defaultPlayer['player']['caption_windowopacity']; ?>"
                    },
            "abouttext": "<?php echo $defaultPlayer['player']['about_text']; ?>",
            "aboutlink": "<?php echo $defaultPlayer['player']['about_link']; ?>",
            "image": "",
            events: {
            }

        });

        $("#slider-range").slider({
            range: true,
            min: file_min_duration,
            max: file_max_duration,
            values: [file_min_duration, file_max_duration],
            slide: function(event, ui) {
                var stime = secondsToTimeGet(ui.values[0]);
                var etime = secondsToTimeGet(ui.values[1]);
                $("#start_time_video_div").html(stime);
                $("#end_time_video_div").html(etime);
                $("#start_time_video").val(stime);
                $("#end_time_video").val(etime);
                jwplayer().seek(ui.values[0]);
            }
        });
        var pauseOnSeek = false;
        jwplayer("prevElement1").onSeek(function(event) {
            pauseOnSeek = true;
        });

        jwplayer("prevElement1").onTime(function(event) {
            var statePlayer = this.getState();
            if (statePlayer == 'PLAYING' && pauseOnSeek == true) {
                this.pause(true);
                pauseOnSeek = false;
            }
        });
        $('#myModal1').on('hide.bs.modal', function() {
            jwplayer().stop();
        });

        $('button#cut_promo').click(function(e) {
            var startTime = $("#start_time_video").val();
            var endTime = $("#end_time_video").val();
            if (startTime == endTime) {
                alert('Please select a range first.');
                return false;
            }
        });
    });

    function secondsToTimeGet(secs)
    {
        secs = Math.floor(secs);
        if (secs > 0) {
            var hours = Math.floor(secs / (60 * 60));
            var divisor_for_minutes = secs % (60 * 60);
            var minutes = Math.floor(divisor_for_minutes / 60);
            var divisor_for_seconds = divisor_for_minutes % 60;
            var seconds = Math.ceil(divisor_for_seconds);
            if (hours < 10) {
                hours = "0" + hours;
            }
            if (minutes < 10) {
                minutes = "0" + minutes;
            }
            if (seconds < 10) {
                seconds = "0" + seconds;
            }
            var time_val = hours + ' : ' + minutes + ' : ' + seconds;
            return time_val;
        } else {
            return '00:00:00';
        }
    }


</script>
<!-- Model box to add new promo starts --->

<div class="modal fade" id="myModal1" aria-hidden="false">
    <div class="modal-dialog">
        <form action="<?php echo base_url(); ?>video/promoInfo" id="cutPromoForm" method="post" accept-charset="utf-8" accept-charset="utf-8" > 
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="false" onclick='stopvideo()'></button>
                    <h4 class="modal-title" id="myModalLabel">
<?php echo $welcome->loadPo('Promo') . " : " . $welcome->loadPo('Add'); ?>  
                    </h4>
                </div>
                <div class="modal-body text-center no-padding">        
                    <div id="prevElement1"></div>
                    <input type="hidden" name="content_id" id="content_id" value="<?php echo $content_id; ?>" />
                    <input type="hidden" name="file_duration" id="file_duration" value="<?php echo $videoFileSize; ?>" />
                    <input type="hidden" id="start_time_video" name="start_time_video" value="" />
                    <input type="hidden" id="end_time_video"  name="end_time_video" value="" />
                    <div class="box">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div id="slider-range"></div>
                                    <div class="fl btn bg-purple margin btn-sm" id="start_time_video_div" style="border:0; text-align : right; "></div>
                                    <div class="fr btn bg-purple margin btn-sm" id="end_time_video_div" style="border:0; text-align : right; "></div> 
                                </div>								
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer"  style="margin-top:-25px;" >
                    <button type="submit" id="cut_promo" class="btn btn-primary" ><?php echo $welcome->loadPo('Cut Promo'); ?></button>  
                    <button class="btn btn-default" data-dismiss="modal" type="button"><?php echo $welcome->loadPo('Close'); ?></button>				
                </div>
            </div><!-- /.modal-content -->
        </form>
    </div><!-- /.modal -->
</div> 
<!-- Model box to add new promo ends --->
