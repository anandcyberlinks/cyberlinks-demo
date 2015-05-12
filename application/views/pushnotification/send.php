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
                       <input type="text" placeholder="Search By Tag"  class="form-control">
                    </div>
                    <div class="form-group">
                      <input type="text" placeholder="Enter IOS ID" class="form-control hiddenClass iosId">
                    </div>
                    <div class="form-group hiddenClass oneDevice">
                      <input type="text" placeholder="Enter a android ID" class="form-control hiddenClass androidId">
                    </div>                    
                    <div class="form-group hiddenClass bysegment">
                      <select class="form-control "> 
                        <option>Select a Segment</option>
                        <option>option 1</option>
                        <option>option 2</option>
                        <option>option 3</option>
                        <option>option 4</option>
                      </select><br>
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
    <!--
    <option timeZoneId="2" gmtAdjustment="GMT-11:00" useDaylightTime="0" value="-11">(GMT-11:00) Midway Island, Samoa</option>
	<option timeZoneId="3" gmtAdjustment="GMT-10:00" useDaylightTime="0" value="-10">(GMT-10:00) Hawaii</option>
	<option timeZoneId="4" gmtAdjustment="GMT-09:00" useDaylightTime="1" value="-9">(GMT-09:00) Alaska</option>
	<option timeZoneId="5" gmtAdjustment="GMT-08:00" useDaylightTime="1" value="-8">(GMT-08:00) Pacific Time (US & Canada)</option>
	<option timeZoneId="6" gmtAdjustment="GMT-08:00" useDaylightTime="1" value="-8">(GMT-08:00) Tijuana, Baja California</option>
	<option timeZoneId="7" gmtAdjustment="GMT-07:00" useDaylightTime="0" value="-7">(GMT-07:00) Arizona</option>
	<option timeZoneId="8" gmtAdjustment="GMT-07:00" useDaylightTime="1" value="-7">(GMT-07:00) Chihuahua, La Paz, Mazatlan</option>
	<option timeZoneId="9" gmtAdjustment="GMT-07:00" useDaylightTime="1" value="-7">(GMT-07:00) Mountain Time (US & Canada)</option>
	<option timeZoneId="10" gmtAdjustment="GMT-06:00" useDaylightTime="0" value="-6">(GMT-06:00) Central America</option>
	<option timeZoneId="11" gmtAdjustment="GMT-06:00" useDaylightTime="1" value="-6">(GMT-06:00) Central Time (US & Canada)</option>
	<option timeZoneId="12" gmtAdjustment="GMT-06:00" useDaylightTime="1" value="-6">(GMT-06:00) Guadalajara, Mexico City, Monterrey</option>
	<option timeZoneId="13" gmtAdjustment="GMT-06:00" useDaylightTime="0" value="-6">(GMT-06:00) Saskatchewan</option>
	<option timeZoneId="14" gmtAdjustment="GMT-05:00" useDaylightTime="0" value="-5">(GMT-05:00) Bogota, Lima, Quito, Rio Branco</option>
	<option timeZoneId="15" gmtAdjustment="GMT-05:00" useDaylightTime="1" value="-5">(GMT-05:00) Eastern Time (US & Canada)</option>
	<option timeZoneId="16" gmtAdjustment="GMT-05:00" useDaylightTime="1" value="-5">(GMT-05:00) Indiana (East)</option>
	<option timeZoneId="17" gmtAdjustment="GMT-04:00" useDaylightTime="1" value="-4">(GMT-04:00) Atlantic Time (Canada)</option>
	<option timeZoneId="18" gmtAdjustment="GMT-04:00" useDaylightTime="0" value="-4">(GMT-04:00) Caracas, La Paz</option>
	<option timeZoneId="19" gmtAdjustment="GMT-04:00" useDaylightTime="0" value="-4">(GMT-04:00) Manaus</option>
	<option timeZoneId="20" gmtAdjustment="GMT-04:00" useDaylightTime="1" value="-4">(GMT-04:00) Santiago</option>
	<option timeZoneId="21" gmtAdjustment="GMT-03:30" useDaylightTime="1" value="-3.5">(GMT-03:30) Newfoundland</option>
	<option timeZoneId="22" gmtAdjustment="GMT-03:00" useDaylightTime="1" value="-3">(GMT-03:00) Brasilia</option>
	<option timeZoneId="23" gmtAdjustment="GMT-03:00" useDaylightTime="0" value="-3">(GMT-03:00) Buenos Aires, Georgetown</option>
	<option timeZoneId="24" gmtAdjustment="GMT-03:00" useDaylightTime="1" value="-3">(GMT-03:00) Greenland</option>
	<option timeZoneId="25" gmtAdjustment="GMT-03:00" useDaylightTime="1" value="-3">(GMT-03:00) Montevideo</option>
	<option timeZoneId="26" gmtAdjustment="GMT-02:00" useDaylightTime="1" value="-2">(GMT-02:00) Mid-Atlantic</option>
	<option timeZoneId="27" gmtAdjustment="GMT-01:00" useDaylightTime="0" value="-1">(GMT-01:00) Cape Verde Is.</option>
	<option timeZoneId="28" gmtAdjustment="GMT-01:00" useDaylightTime="1" value="-1">(GMT-01:00) Azores</option>
	<option timeZoneId="29" gmtAdjustment="GMT+00:00" useDaylightTime="0" value="0">(GMT+00:00) Casablanca, Monrovia, Reykjavik</option>
	<option timeZoneId="30" gmtAdjustment="GMT+00:00" useDaylightTime="1" value="0">(GMT+00:00) Greenwich Mean Time : Dublin, Edinburgh, Lisbon, London</option>
	<option timeZoneId="31" gmtAdjustment="GMT+01:00" useDaylightTime="1" value="1">(GMT+01:00) Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna</option>
	<option timeZoneId="32" gmtAdjustment="GMT+01:00" useDaylightTime="1" value="1">(GMT+01:00) Belgrade, Bratislava, Budapest, Ljubljana, Prague</option>
	<option timeZoneId="33" gmtAdjustment="GMT+01:00" useDaylightTime="1" value="1">(GMT+01:00) Brussels, Copenhagen, Madrid, Paris</option>
	<option timeZoneId="34" gmtAdjustment="GMT+01:00" useDaylightTime="1" value="1">(GMT+01:00) Sarajevo, Skopje, Warsaw, Zagreb</option>
	<option timeZoneId="35" gmtAdjustment="GMT+01:00" useDaylightTime="1" value="1">(GMT+01:00) West Central Africa</option>
	<option timeZoneId="36" gmtAdjustment="GMT+02:00" useDaylightTime="1" value="2">(GMT+02:00) Amman</option>
	<option timeZoneId="37" gmtAdjustment="GMT+02:00" useDaylightTime="1" value="2">(GMT+02:00) Athens, Bucharest, Istanbul</option>
	<option timeZoneId="38" gmtAdjustment="GMT+02:00" useDaylightTime="1" value="2">(GMT+02:00) Beirut</option>
	<option timeZoneId="39" gmtAdjustment="GMT+02:00" useDaylightTime="1" value="2">(GMT+02:00) Cairo</option>
	<option timeZoneId="40" gmtAdjustment="GMT+02:00" useDaylightTime="0" value="2">(GMT+02:00) Harare, Pretoria</option>
	<option timeZoneId="41" gmtAdjustment="GMT+02:00" useDaylightTime="1" value="2">(GMT+02:00) Helsinki, Kyiv, Riga, Sofia, Tallinn, Vilnius</option>
	<option timeZoneId="42" gmtAdjustment="GMT+02:00" useDaylightTime="1" value="2">(GMT+02:00) Jerusalem</option>
	<option timeZoneId="43" gmtAdjustment="GMT+02:00" useDaylightTime="1" value="2">(GMT+02:00) Minsk</option>
	<option timeZoneId="44" gmtAdjustment="GMT+02:00" useDaylightTime="1" value="2">(GMT+02:00) Windhoek</option>
	<option timeZoneId="45" gmtAdjustment="GMT+03:00" useDaylightTime="0" value="3">(GMT+03:00) Kuwait, Riyadh, Baghdad</option>
	<option timeZoneId="46" gmtAdjustment="GMT+03:00" useDaylightTime="1" value="3">(GMT+03:00) Moscow, St. Petersburg, Volgograd</option>
	<option timeZoneId="47" gmtAdjustment="GMT+03:00" useDaylightTime="0" value="3">(GMT+03:00) Nairobi</option>
	<option timeZoneId="48" gmtAdjustment="GMT+03:00" useDaylightTime="0" value="3">(GMT+03:00) Tbilisi</option>
	<option timeZoneId="49" gmtAdjustment="GMT+03:30" useDaylightTime="1" value="3.5">(GMT+03:30) Tehran</option>
	<option timeZoneId="50" gmtAdjustment="GMT+04:00" useDaylightTime="0" value="4">(GMT+04:00) Abu Dhabi, Muscat</option>
	<option timeZoneId="51" gmtAdjustment="GMT+04:00" useDaylightTime="1" value="4">(GMT+04:00) Baku</option>
	<option timeZoneId="52" gmtAdjustment="GMT+04:00" useDaylightTime="1" value="4">(GMT+04:00) Yerevan</option>
	<option timeZoneId="53" gmtAdjustment="GMT+04:30" useDaylightTime="0" value="4.5">(GMT+04:30) Kabul</option>
	<option timeZoneId="54" gmtAdjustment="GMT+05:00" useDaylightTime="1" value="5">(GMT+05:00) Yekaterinburg</option>
	<option timeZoneId="55" gmtAdjustment="GMT+05:00" useDaylightTime="0" value="5">(GMT+05:00) Islamabad, Karachi, Tashkent</option>
	<option timeZoneId="56" gmtAdjustment="GMT+05:30" useDaylightTime="0" value="5.5">(GMT+05:30) Sri Jayawardenapura</option>
	<option timeZoneId="57" gmtAdjustment="GMT+05:30" useDaylightTime="0" value="5.5">(GMT+05:30) Chennai, Kolkata, Mumbai, New Delhi</option>
	<option timeZoneId="58" gmtAdjustment="GMT+05:45" useDaylightTime="0" value="5.75">(GMT+05:45) Kathmandu</option>
	<option timeZoneId="59" gmtAdjustment="GMT+06:00" useDaylightTime="1" value="6">(GMT+06:00) Almaty, Novosibirsk</option>
	<option timeZoneId="60" gmtAdjustment="GMT+06:00" useDaylightTime="0" value="6">(GMT+06:00) Astana, Dhaka</option>
	<option timeZoneId="61" gmtAdjustment="GMT+06:30" useDaylightTime="0" value="6.5">(GMT+06:30) Yangon (Rangoon)</option>
	<option timeZoneId="62" gmtAdjustment="GMT+07:00" useDaylightTime="0" value="7">(GMT+07:00) Bangkok, Hanoi, Jakarta</option>
	<option timeZoneId="63" gmtAdjustment="GMT+07:00" useDaylightTime="1" value="7">(GMT+07:00) Krasnoyarsk</option>
	<option timeZoneId="64" gmtAdjustment="GMT+08:00" useDaylightTime="0" value="8">(GMT+08:00) Beijing, Chongqing, Hong Kong, Urumqi</option>
	<option timeZoneId="65" gmtAdjustment="GMT+08:00" useDaylightTime="0" value="8">(GMT+08:00) Kuala Lumpur, Singapore</option>
	<option timeZoneId="66" gmtAdjustment="GMT+08:00" useDaylightTime="0" value="8">(GMT+08:00) Irkutsk, Ulaan Bataar</option>
	<option timeZoneId="67" gmtAdjustment="GMT+08:00" useDaylightTime="0" value="8">(GMT+08:00) Perth</option>
	<option timeZoneId="68" gmtAdjustment="GMT+08:00" useDaylightTime="0" value="8">(GMT+08:00) Taipei</option>
	<option timeZoneId="69" gmtAdjustment="GMT+09:00" useDaylightTime="0" value="9">(GMT+09:00) Osaka, Sapporo, Tokyo</option>
	<option timeZoneId="70" gmtAdjustment="GMT+09:00" useDaylightTime="0" value="9">(GMT+09:00) Seoul</option>
	<option timeZoneId="71" gmtAdjustment="GMT+09:00" useDaylightTime="1" value="9">(GMT+09:00) Yakutsk</option>
	<option timeZoneId="72" gmtAdjustment="GMT+09:30" useDaylightTime="0" value="9.5">(GMT+09:30) Adelaide</option>
	<option timeZoneId="73" gmtAdjustment="GMT+09:30" useDaylightTime="0" value="9.5">(GMT+09:30) Darwin</option>
	<option timeZoneId="74" gmtAdjustment="GMT+10:00" useDaylightTime="0" value="10">(GMT+10:00) Brisbane</option>
	<option timeZoneId="75" gmtAdjustment="GMT+10:00" useDaylightTime="1" value="10">(GMT+10:00) Canberra, Melbourne, Sydney</option>
	<option timeZoneId="76" gmtAdjustment="GMT+10:00" useDaylightTime="1" value="10">(GMT+10:00) Hobart</option>
	<option timeZoneId="77" gmtAdjustment="GMT+10:00" useDaylightTime="0" value="10">(GMT+10:00) Guam, Port Moresby</option>
	<option timeZoneId="78" gmtAdjustment="GMT+10:00" useDaylightTime="1" value="10">(GMT+10:00) Vladivostok</option>
	<option timeZoneId="79" gmtAdjustment="GMT+11:00" useDaylightTime="1" value="11">(GMT+11:00) Magadan, Solomon Is., New Caledonia</option>
	<option timeZoneId="80" gmtAdjustment="GMT+12:00" useDaylightTime="1" value="12">(GMT+12:00) Auckland, Wellington</option>
	<option timeZoneId="81" gmtAdjustment="GMT+12:00" useDaylightTime="0" value="12">(GMT+12:00) Fiji, Kamchatka, Marshall Is.</option>
	<option timeZoneId="82" gmtAdjustment="GMT+13:00" useDaylightTime="0" value="13">(GMT+13:00) Nuku'alofa</option>
     -->                 </select>
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
                }
                if (!$("#iosId").is(':checked')) {
                 $(".iosId").addClass("hiddenClass");
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
        $(".platform-error,.platformMethod-error,.textarea-error,.timepicker-error,.datepicker-error,.timepicker-error").addClass("hiddenClass");
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