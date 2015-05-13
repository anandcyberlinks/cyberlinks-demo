 <!-- Content Wrapper. Contains page content -->
   <style>
      .textHeading
      {
        font-weight: 700!important;
      }
      .textareaCustom
      {
        resize: none
      }
      .hiddenClass
      {
        display: none!important;
      }
      .errorValidate
      {
        color: #DD4B39;
      }
    </style> 
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            New Notification
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Forms</a></li>
            <li class="active">General Elements</li>
          </ol>
        </section>
 <?php echo $this->session->flashdata('message'); ?>
        <!-- Main content -->
        <section class="content">
         <form id="frmsubmit" method="post" action="<?php echo base_url();?>push_notification/index/">                
          <div class="row">
            
            <!-- left column -->
            <div class="col-md-12">
              <!-- general form elements -->
              <div class="box box-primary">
                <!-- form start -->
                
                  <div class="box-body">
                        <div class="form-group">
                      <label>Message</label>
                      <textarea name="message" placeholder="Enter Text" rows="3" class="form-control textareaCustom" maxlength="1000"></textarea>
                      <div class="errorValidate hiddenClass textarea-error">Message field is required.</div>
                      <div class="textarea_message"></div>                      
                    </div>                   
                  </div><!-- /.box-body -->
              </div><!-- /.box -->

              <!-- Form Element sizes -->
              <div class="box box-primary">
                <div class="box-header">
                  <h3 class="box-title">Platforms and Recipients</h3>
                </div>
                <div class="box-body">
                  <div class="form-group">
                          <div class="row">
                          <div class="col-md-2"> 
                                 <input type="checkbox" id="iosId" name="device_type[]" value='ios'>
                                 &nbsp;&nbsp;&nbsp;<i class="fa fa-apple"></i>&nbsp;&nbsp;<label class="textHeading">Ios</label>
                           </div>
                          <div class="col-md-2"> 

                                 <input type="checkbox" id="androidId" name="device_type[]" value='android'>
                                 &nbsp;&nbsp;&nbsp;<i class="fa fa-android"></i>&nbsp;&nbsp;<label class="textHeading">Android</label>

                          </div>
                          </div>
                          <div class="errorValidate hiddenClass platform-error">Select atleast one.</div>
                  </div>        
                    <div class="row">
                        <div class="form-group">
                         <div class="col-md-3"> 
                            <div class="radio">
                            <label>
                              <input type="radio" value="broadcast" class="radioCheck" id="broadcast" name="notification_type" checked="checked">
                              <label class="textHeading">Broadcast</label>
                            </label>
                          </div>
                          </div>
                         <div class="col-md-3">
                          <div class="radio">
                            <label>
                              <input type="radio" value="device_by_tag" class="radioCheck" id="devByTag" name="notification_type">
                               <label class="textHeading"> Devices By Tag</label>
                            </label>
                          </div>
                         </div>
                         <div class="col-md-3"> 
                          <div class="radio">
                            <label>
                              <input type="radio" value="one_device" class="radioCheck" id="oneDevice" name="notification_type">
                              <label class="textHeading">One Device</label>
                            </label>
                          </div>
                         </div>
                         <div class="col-md-3"> 
                          <div class="radio">
                            <label>
                              <input type="radio" value="bysegment" class="radioCheck" id="bysegment" name="notification_type">
                              <label class="textHeading">Devices By Segment</label>
                            </label>
                          </div>                         
                         </div>
                        </div>
                    </div>    
                      <div class="errorValidate hiddenClass platformMethod-error">Select atleast one.</div>
                    <div class="form-group hiddenClass devByTag">
                       <input type="text" placeholder="Search By Tag" class="form-control devByTagVal" name="keywords" id="myTags">
                       <div class="errorValidate hiddenClass devByTag-error">Field is required.</div>                       
                    </div>
                    <div class="form-group">
                      <input type="text" placeholder="Enter IOS ID" class="form-control hiddenClass iosId" name="device_id[]">
                    <div class="errorValidate hiddenClass iosId-error">Field is required.</div>
                    </div>
                    <div class="form-group hiddenClass oneDevice">
                      <input type="text" placeholder="Enter a android ID" class="form-control hiddenClass androidId" name="device_id[]">
                        <div class="errorValidate hiddenClass androidId-error">Field is required.</div>
                    </div>                    
                    <div class="form-group hiddenClass bysegment">
                      <select class="form-control" name="segment"> 
                        <option value='' selected='selected'>Select a Segment</option>
                        <option>option 1</option>
                        <option>option 2</option>
                        <option>option 3</option>
                        <option>option 4</option>
                      </select>
                      <div class="errorValidate hiddenClass bysegment-error">Field is required.</div>
                      <br>
                      <div class="col-md-2"><a href="#" class="btn btn-block btn-default">Remove</a></div>
                      <div class="col-md-2"><a href="#" class="btn btn-block btn-default">Add Another</a></div>
                    </div>   

                </div><!-- /.box-body -->
              </div><!-- /.box -->

              <div class="box box-primary">
                <div class="box-header">
                  <h3 class="box-title">Delivery Time</h3>
                </div>
                <div class="box-body">
                    <div class="row">      
                        <div class="form-group">
                         <div class="col-md-3"> 
                            <div class="radio">
                            <label>
                              <input type="radio" checked="checked" class="deliveryTime" value="1" id="notificationSendRight" name="delivery_time">
                              <label class="textHeading">Right Now</label>
                            </label>
                          </div>
                          </div>
                         <div class="col-md-3">  
                          <div class="radio">
                            <label>
                              <input type="radio" class="deliveryTime" value="2" id="deliverTimeLater" name="delivery_time">
                               <label class="textHeading"> Later</label>
                            </label>
                          </div>
                         </div>
                        </div>
                    </div>
                    <div class="row deliverTimeLater hiddenClass">
                     <div class="col-md-3">
                        <input class="datepicker form-control" placeholder="Select Date" name="date">
                      <div class="errorValidate hiddenClass datepicker-error">Field is required.</div>
                    </div>
                    <div class="col-md-2">
                      <input type="text" class="form-control timepicker" placeholder="Select time" name="time">
                      <div class="errorValidate hiddenClass timepicker-error">Field is required.</div>
                      <!--<input type="text" placeholder="Select time" class="form-control">-->
                    </div>
                    <!--<div class="col-md-2">
                      <select class="form-control bysegment"> 
                        <option>AM</option>
                        <option>PM</option>
                      </select>
                    </div> -->                   
                    <div class="col-md-6">
                      <select class="form-control timezone" name='timezone'> 
                        <option value="">Select a Time-Zone</option>
                        <?php foreach($time_zones as $key=>$val){?>
                        <option value="<?php echo $key;?>"><?php echo $val;?></option>
                        <?php }?>
                </select>
                      <div class="errorValidate hiddenClass timezone-error">Field is required.</div>
                    </div>                    
                  </div>
                    </div><!-- /.box-body -->
              </div><!-- /.box -->

              <!-- Input addon -->
              <div class="box box-primary">
                <div class="box-body">
   
                    <div class="box-footer">
                        <a href="#" data-toggle="modal" onclick="return Pushvalidate();" class="btn btn-primary">Send Notification</a>
                        <!--<button type="submit" class="btn btn-primary">Send Notification</button>-->
                   </div>
                </div><!-- /.box-body -->
              </div><!-- /.box -->

              <!-- Input addon -->
            
            </div><!--/.col (left) -->
            <!-- right column -->
          </div>   <!-- /.row -->
          </form>
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
	  
	   <script>
      $(document).ready(function(){
         //--- check for character count --//         
         var text_max = 1000; 
         $('.textarea_message').html(text_max + ' characters remaining');     
         $('.textareaCustom').keyup(function() {
             var text_length = $('.textareaCustom').val().length;
             var text_remaining = text_max - text_length;     
             $('.textarea_message').html(text_remaining + ' characters remaining');
         });
         //--------------------------------//
         
          $(".radioCheck").click(function(){
            var idClass = $(this).attr("id");
            $(".bysegment,.devByTag,.oneDevice").addClass("hiddenClass");
            $('.devByTagVal,.iosId,.androidId,.bysegment select').val('');
            $(".iosId,.androidId").addClass("hiddenClass");
            $("."+idClass).removeClass("hiddenClass");
            if (idClass=="oneDevice") {
              if ($("#iosId").is(':checked')) {
                  $(".iosId").removeClass("hiddenClass");
              }if ($("#androidId").is(':checked')) {
                  $(".androidId").removeClass("hiddenClass");
              }
            }
            });
          $(".deliveryTime").click(function(){
              var deliveryClass = $(this).attr("id");
              $(".deliverTimeLater").addClass("hiddenClass");
              $("."+deliveryClass).removeClass("hiddenClass");
            });
          $("#androidId,#iosId").click(function(){
              if ($("#oneDevice").is(':checked')) {
                $("."+$(this)[0].id).removeClass("hiddenClass");
              }
              if (!$("#androidId").is(':checked')) {
                  $(".androidId").addClass("hiddenClass");
                  $(".androidId").val('');
                }
                if (!$("#iosId").is(':checked')) {
                 $(".iosId").addClass("hiddenClass");
                 $(".iosId").val('');
               }      
            });
          
          $('.datepicker').datepicker({
            dateFormat: 'yy-mm-dd',
            defaultViewDate: "05/08/2015",
            startDate : "today",            
          });
                  $(".timepicker").timepicker({
                    showInputs: false
                  });
        });
      
      function Pushvalidate()
      {
        var popUpStatus = true;
        var textareaCustom = $(".textareaCustom").val();
        $(".bysegment-error,.androidId-error,.devByTag-error,.iosId-error,.platform-error,.platformMethod-error,.textarea-error,.timepicker-error,.datepicker-error,.timepicker-error").addClass("hiddenClass");       
        if (textareaCustom=='') {
          $(".textarea-error").removeClass("hiddenClass");
          popUpStatus = false;
          return false;
        }  
        if ($("#androidId").prop('checked') == false && $("#iosId").prop('checked') == false) {
          $(".platform-error").removeClass("hiddenClass");
          popUpStatus = false;
          return false;
        }   
        if ($("#broadcast").prop('checked') == false && $("#devByTag").prop('checked') == false && $("#oneDevice").prop('checked') == false && $("#bysegment").prop('checked') == false) {
          $(".platformMethod-error").removeClass("hiddenClass");
          popUpStatus = false;
          return false;
        }
        
         if ($("#deliverTimeLater").prop('checked') == true) {
          var laterDate = $('.datepicker').val();
          var timepicker = $('.timepicker').val();
          var timezone   = $('.timezone').val();
          if (laterDate=='') {
            $(".datepicker-error").removeClass('hiddenClass');
            popUpStatus = false;
            return false;
          }
          if (timezone=='') {
            $(".timezone-error").removeClass('hiddenClass');
            popUpStatus = false;
            return false;            
          }if (timepicker=='') {
            $(".timepicker-error").removeClass('hiddenClass');
            popUpStatus = false;
            return false;            
          }
        }
        if ($("#iosId").prop('checked') == true) {
          if ($("#oneDevice").prop('checked') == true) {
              var iosIdVal = $(".iosId").val();
              if(iosIdVal==''){
                  $(".iosId-error").removeClass("hiddenClass");
                  popUpStatus = false;
                  return false;
              }
            //bysegment-error androidId-error iosId-error devByTag-error
            }
          
        }        
         if ($("#androidId").prop('checked') == true)
        {
        if ($("#oneDevice").prop('checked') == true) {
                var androidIdVal = $(".androidId").val();
                if (androidIdVal=='') {
                    $(".androidId-error").removeClass("hiddenClass");
                    popUpStatus = false;
                    return false;
                }
            //bysegment-error androidId-error iosId-error devByTag-error
          }
        }


        if ($("#devByTag").prop('checked') == true) {
            var devByTagVal = $(".devByTagVal").val();
            if (devByTagVal=='') {
                  $(".devByTag-error").removeClass("hiddenClass");
                  popUpStatus = false;
                  return false;
            }
          }
          if ($("#bysegment").prop('checked') == true) {
           var bysegmentVal = $(".bysegment select").val();
           if (bysegmentVal=='') {
                  $(".bysegment-error").removeClass("hiddenClass");
                  popUpStatus = false;
                  return false;
           }
          }
          
        if ($("#iosId").prop('checked') == true) {
          var characterLength = $('.textareaCustom').val().length;
          if (characterLength > 326) {
            //alert(characterLength);
           bootbox.confirm("IOS accept only 326 character only.", function(confirmCheck) {
                   if (confirmCheck) {
                      popUpStatus= true;
                      $('#submitModal').modal('show');
                      return true;
                   }else
                   {
                      popUpStatus = false;
                      return false;
                   }
               }); 
           return false;
          }else
          {
            popUpStatus = true;
            $('#submitModal').modal('show');
            return true;
          }
        }    
        if (popUpStatus) {
           $('#submitModal').modal('show');
        }
      }
    </script>
    
<div class="modal fade" id="submitModal" tabindex="-1" role="dialog" aria-labelledby="helpModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">&times;
                            </span><span class="sr-only">
                                     Close</span></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to send this notification?</p>
                        <a href="javascript:void()" onclick="javascript:$('form').submit();" class="btn btn-primary">Let's Do It!</a>
                        <a href="javascript:void()" onclick="javascript:$('#submitModal').modal('hide');" class="btn btn-primary">No Way!</a>
                    </div>
                </div
            </div>
        </div>