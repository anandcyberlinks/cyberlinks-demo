							<h1>This is Advance</h1>
							</div><!-- /.tab-content -->
						</div><!-- nav-tabs-custom -->
					</div><!-- /.col -->
				</div> <!-- /.row -->
			</div>
		</section>
	</aside>
</div>
<div class="modal-body"><div align="center" id="jsplayer"></div></div>

<script>
 $('td a.prev_video').click(function(e) {
	// alert("dsdsdsdsd");
		var file_path = $(this).attr('data-img-url')
		var str = '<script type="text/javascript">';
		str += 'jwplayer("prevElement").setup({ ';
		str += 'primary: "html5",';
		str += 'file: ' + '"' + file_path + '"';
		str += '});';
		str += '<\/script>';
		$('#myModal #prevElement').html(str);
	});
     function stopvideo()
    {
        jwplayer('prevElement').stop();
    }
	 function delete_video(id)
	{
	  bootbox.confirm("<?php echo  $welcome->loadPo('Are you sure you want to Delete video') ?>", function(confirmed) 
							{
								if (confirmed) 
								{
									location.href = '<?php echo base_url();?>video/deletevideo?id='+id ;
								}
							})
	}

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