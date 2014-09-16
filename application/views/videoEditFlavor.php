	
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
                <?php
                // echo '<pre>'; print_r($setting);
                foreach ($setting as $value2) {
                    ?>
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
                        $curr_url = current_full_url();
                        $contectid = base64_decode($_GET['action']);
                        switch ($value2->status) {
                            case 'inprocess' :
                                echo sprintf('<td><span class="%s">inprocess</span></td>', 'label label-info');
                                echo sprintf('<td><a href="%s" class="%s">Cancel</a></td>', base_url() . 'video/changeStatus?flavor_id=' . $value2->id. '&content_id='.$contectid. '&status=inprocess'. '&redirecturl=' . $curr_url, 'btn btn-warning btn-sm');
                                break;
                            case 'completed' :
                                echo sprintf('<td><span class="%s">complete</span></td>', 'label label-success');
                                echo sprintf('<td><a href="%s"  class="%s">Delete</a></td>', base_url() . 'video/changeStatus?flavor_id=' . $value2->id. '&content_id='.$contectid. '&status=complete'. '&redirecturl=' . $curr_url, 'btn btn-danger btn-sm');
                                break;
                            case 'pending' :
                            default :
                                echo sprintf('<td><span class="%s">Pending</span></td>', 'label label-warning');
                                echo sprintf('<td><a href="%s" class="%s">Convert</a></td>', base_url() . 'video/changeStatus?flavor_id=' . $value2->id . '&content_id='.$contectid. '&status=pending'. '&redirecturl=' . $curr_url, 'btn btn-primary btn-sm');
                                break;
                        }
                        ?>
                    </tr>
<?php } ?>
            </tbody>
        </table>
    </div><!-- /.box-body -->
</div><!-- /.box -->
							</div><!-- /.tab-content -->
						</div><!-- nav-tabs-custom -->
					</div><!-- /.col -->
				</div> <!-- /.row -->
			</div>
		</section>
	</aside>
</div>


<script>

/* start date and end date */
$(document).ready(function(){

	$("#datepickerstart").datepicker({
		dateFormat: 'yy-mm-dd',
		numberOfMonths: 1,
		onSelect: function(selected) 
		{
		var date = $(this).datepicker('getDate');
		//$("#hddstarddt").val(date);
			if (date)
			{
				date.setDate(date.getDate() + 1);
			}
			$("#datepickerend").datepicker("option","minDate", date)
		}
		});
		$("#datepickerend").datepicker({
		dateFormat: 'yy-mm-dd',
		numberOfMonths: 1,
		onSelect: function(selected)
		{
		var date = $(this).datepicker('getDate');
		if (date)
		{
			date.setDate(date.getDate() - 1);
		}
			$("#datepickerstart").datepicker("option","maxDate", date || 0)
		}
	});
	
	 $(".timepicker").timepicker({
		minuteStep: 1,
		showInputs: false,
		disableFocus: true
	});
});

 
$(document).ready(function(){
//By Default Disable radio button
	$("#datepickerstart").attr('disabled', false);
	$("#datepickerend").attr('disabled', false);

//Disable radio buttons function on Check Disable radio button 
$("form input:radio").change(function () {
if ($(this).val() == "Disable") 
 {		
	$("#datepickerstart").attr('disabled', true);
	$("#datepickerend").attr('disabled', true);
 } 
// else Enable radio buttons 	 
else 
 {
	$("#datepickerstart").attr('disabled', false);
	$("#datepickerend").attr('disabled', false);
 }
});

});
</script>
