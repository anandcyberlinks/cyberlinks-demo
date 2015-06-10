
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          
        </section>

        <!-- Main content -->
        <section class="content" id="customCss">
          <form role="form">
          <div class="row">
            <!-- left column -->
            <div class="col-md-12">
              <!-- Form Element sizes -->
              <div class="box box-primary">
                <div class="box-header">
                    <div class="widget-header logo"></div>
                    <h3 class="timeHeading pull-left">Sessions</h3>
          <div class="mailbox-controls pull-right">
                    <!-- Check all button -->
                    <a class="btn btn-default btn-sm reservation" onclick="dateRange()" id="reservation"><i class="fa fa-calendar"></i></a>
                    <div class="btn-group ">
                      <a class="btn btn-default btn-sm year-class" value="365">2015</a>
                      <a class="btn btn-default btn-sm active-header-btn today-class" value="1">Today</a>
                      <a class="btn btn-default btn-sm week-class" value="7">7 Days</a>
                      <a class="btn btn-default btn-sm month-class" value="30">30 Days</a>
                      <a class="btn btn-default btn-sm months2-class" value="60">60 Days</a>
                      <a class="btn btn-default btn-sm months3-class" value="90">90 Days</a>
                    </div><!-- /.btn-group -->
                  </div>
                </div>
                  <div >
                      
                      <canvas id="line-chart" width="1250" height="300"></canvas>
                  </div>           
                <div class="box-body" id="analyticsUser">
              <div class="dashboard" id="big-numbers-container">
					<div class="big-numbers three-column">
                                                <div id="draw-events-served" class="select">Total Session</div>
						<div style="width: 100%;background: #88BBC8;height: 4px;   height: 4px;position: relative;top: 28px;"></div>
                                                <div class="inner">
							<div class="number" id="total">00,000</div>
                                                        <div style="background-image:url('bootstrap/img/dtrend.png');" class="trend"></div>
						</div>
					</div>
					
					<div class="big-numbers three-column">
                                          <div id="draw-events-served" class="select">New Session</div>
                                          <div style="width: 100%;background: #ED8662;height: 4px;   height: 4px;position: relative;top: 28px;"></div>
						<div class="inner">
							<div class="number" id='new'>00,000</div>
                                                        <div style="background-image:url('bootstrap/img/utrend.png');" class="trend"></div>
						</div>
					</div>
					
					<div class="big-numbers three-column">
						<div id="draw-events-served" class="select">Unique Session</div>
                                                <div style="width: 100%;background: #BEEB9F;height: 4px;   height: 4px;position: relative;top: 28px;"></div>
                                                <div class="inner">
							<div class="number" id='unique'>00,000</div>
                                                        <div style="background-image:url('bootstrap/img/dtrend.png');" class="trend"></div>
						</div>
					</div>
					
                </div>                 
                 
                 <br>
                </div><!-- /.box-body -->
              
                                
              </div><!--/.col (left) -->
            

              <div class="box">
                <div class="box-body">
                  <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                    <div class="row"><div class="col-sm-12">
                    <table class="table table-bordered table-striped dataTable" id="example1" role="grid" aria-describedby="example1_info">
                    <thead>
                      <tr role="row"><th style="width: 167px;">Date/Hours</th><th>Total Session</th><th>New Session</th><th>Unique Session</th></tr>
                    </thead>
                    <tbody>
                    
                    </tbody>
                  </table></div></div></div>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
                        
            
            
            </div><!--/.col (left) -->
            
          
           
            <!-- right column -->
          
            </div>   <!-- /.row -->
          </form>
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
    <!-- jQuery 2.1.3 -->
   <!-- <script src="<?php echo base_url() ?>assets/js/plugins/flot/jquery.flot.min.js" type="text/javascript"></script>-->
    <!-- FLOT RESIZE PLUGIN - allows the chart to redraw when the window is resized -->
   <!-- <script src="<?php echo base_url() ?>assets/js/plugins/flot/jquery.flot.resize.min.js" type="text/javascript"></script>-->
    <!-- FLOT PIE PLUGIN - also used to draw donut charts -->
  <!--  <script src="<?php echo base_url() ?>assets/js/plugins/flot/jquery.flot.pie.min.js" type="text/javascript"></script> -->
    <!-- FLOT CATEGORIES PLUGIN - Used to draw bar charts -->
  <!--  <script src="<?php echo base_url() ?>assets/js/plugins/flot/jquery.flot.categories.min.js" type="text/javascript"></script>-->
          <script src="<?php echo base_url() ?>assets/js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url() ?>assets/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js" type="text/javascript"></script>
      <script src="<?php echo base_url() ?>assets/js/plugins/daterangepicker/daterangepicker.js" type="text/javascript"></script>
                      <script src="http://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
                <script src="<?php echo base_url() ?>assets/js/plugins/morris/morris.min.js"></script>
                <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/1.10.7/integration/bootstrap/3/dataTables.bootstrap.css">
    <script type="text/javascript" language="javascript" src="//cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" language="javascript" src="//cdn.datatables.net/plug-ins/1.10.7/integration/bootstrap/3/dataTables.bootstrap.js"></script> 
    <script type="text/javascript" language="javascript" src="<?php echo base_url() ?>assets/js/plugins/chartjs/Chart.min.js"></script>
<script type="text/javascript" language="javascript" src="<?php echo base_url() ?>assets/js/plugins/chartjs/Chart.js"></script>

   <script>
       // var data = [    ["Edinburgh","5421","2011/04/25","$3,120"],["Edinburgh","8422","2011/07/25","$5,300"    ]]
       
        function drawDataGrid(data)
         {            
            $('#example1').dataTable({
              data: data,
              bFilter: false,
              bLengthChange: false,
                 }); 
          }
         
      $(document).ready(function(){
           var myLineChart;
           $(".reservation").daterangepicker({
               format: 'YYYY-MM-DD',
        });
           $('.mailbox-controls .btn-default').click(function () {
            $('.btn-default').removeClass('active-header-btn');
            $(this).addClass('active-header-btn');
             var num = $('.btn-group').find(".active-header-btn").attr('value');
            // alert(num);
             if(!(num ===undefined))
             {
                 ajaxCall(num);
             }        
                 
          });
          ajaxCall('Today');
         
         // ajaxCall();   //  Bydefault Load Today Data 
        //drawDataGrid(data);
    drawdata = {
    labels: ["January", "February"],
    datasets: [
                {
                    label: "My First dataset",
                    strokeColor: "rgba(220,220,220,1)",
                    pointColor: "rgba(220,220,220,1)", 
                    pointStrokeColor: "#dff",
                    data: [65, 59]
                },
                {
                    label: "My Second dataset",
                    strokeColor: "rgba(151,187,205,1)",
                    pointColor: "rgba(151,187,205,1)",
                    data: [28, 48]
                }
                ,
                {
                    label: "My third dataset",
                    strokeColor: "rgba(190,235,159,0.75)",
                    pointColor: "rgba(90,235,159,0.75)",
                    data: [48, 28]
                }
            ]
        };
        
  DrawLineChart(drawdata);

 /* Date Select Functionality */
         $('.applyBtn').on('click',function(){
            num =[]; 
            strdt = $('input[name="daterangepicker_start"]').val();
            enddt = $('input[name="daterangepicker_end"]').val();
            num[0] =strdt;
            num[1] =enddt;
           //console.log(strdt);
           
            //alert($('input[name="daterangepicker_start"]').val());
             ajaxCall(num)
            });
        });
      
   
    
      function dateRange() {
          $("#reservation").daterangepicker('show');
    }  
      
		 function ajaxCall(num){

                      	$(function()
                        { // start of doc ready.
                            $.ajax({ 	 	
                            dataType:'json',
                                  url: 'Sessions',
                                  data: {'daydiff': num}, // change this to send js object
                                  type: "post",

                                  success: function(data){                                    
                                    
                                    //Draw Grid
                                   // 
                                    $('#total').html(data.total[0]);
                                    $('#new').html(data.total[1]);
                                    $('#unique').html(data.total[2]);
                                    $("#example1").dataTable().fnDestroy();
                                    drawDataGrid(data.grid); 
                                    
                                   // console.log(data.graph);
                                    RemoveGraph();
                                   // drawdata=data.graph;
                                    DrawLineChart(data.graph);
                                  }
                            });
					 
			});
			//console.log($('.btn-group').find(".active-header-btn").text());
		//console.log($(".big-numbers.active").find(".select").text());
	       }
               
function DrawLineChart(drawdata)
   {
        var areaChartOptions = {
          
    // Boolean - Whether to animate the chart
    animation: true,

    // Number - Number of animation steps
    animationSteps: 60,

    // String - Animation easing effect
    // Possible effects are:
    // [easeInOutQuart, linear, easeOutBounce, easeInBack, easeInOutQuad,
    //  easeOutQuart, easeOutQuad, easeInOutBounce, easeOutSine, easeInOutCubic,
    //  easeInExpo, easeInOutBack, easeInCirc, easeInOutElastic, easeOutBack,
    //  easeInQuad, easeInOutExpo, easeInQuart, easeOutQuint, easeInOutCirc,
    //  easeInSine, easeOutExpo, easeOutCirc, easeOutCubic, easeInQuint,
    //  easeInElastic, easeInOutSine, easeInOutQuint, easeInBounce,
    //  easeOutElastic, easeInCubic]
    animationEasing: "easeOutQuart",

    // Boolean - If we should show the scale at all
    showScale: true,

    // Boolean - If we want to override with a hard coded scale
    scaleOverride: false,

    // ** Required if scaleOverride is true **
    // Number - The number of steps in a hard coded scale
    scaleSteps: null,
    // Number - The value jump in the hard coded scale
    scaleStepWidth: null,
    // Number - The scale starting value
    scaleStartValue: null,

    // String - Colour of the scale line
    scaleLineColor: "rgba(0,0,0,.1)",

    // Number - Pixel width of the scale line
    scaleLineWidth: 1,

    // Boolean - Whether to show labels on the scale
    scaleShowLabels: true,

    // Interpolated JS string - can access value
    scaleLabel: "<%=value%>",

    // Boolean - Whether the scale should stick to integers, not floats even if drawing space is there
    scaleIntegersOnly: true,

    // Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
    scaleBeginAtZero: false,

    // String - Scale label font declaration for the scale label
    scaleFontFamily: "'Helvetica Neue', 'Helvetica', 'Arial', sans-serif",

    // Number - Scale label font size in pixels
    scaleFontSize: 12,

    // String - Scale label font weight style
    scaleFontStyle: "normal",

    // String - Scale label font colour
    scaleFontColor: "#666",

    // Boolean - whether or not the chart should be responsive and resize when the browser does.
    responsive: true,

    // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
    maintainAspectRatio: true,

    // Boolean - Determines whether to draw tooltips on the canvas or not
    showTooltips: true,

    // Function - Determines whether to execute the customTooltips function instead of drawing the built in tooltips (See [Advanced - External Tooltips](#advanced-usage-custom-tooltips))
    customTooltips: false,

    // Array - Array of string names to attach tooltip events
    tooltipEvents: ["mousemove", "touchstart", "touchmove"],

    // String - Tooltip background colour
    tooltipFillColor: "rgba(0,0,0,0.8)",

    // String - Tooltip label font declaration for the scale label
    tooltipFontFamily: "'Helvetica Neue', 'Helvetica', 'Arial', sans-serif",

    // Number - Tooltip label font size in pixels
    tooltipFontSize: 14,

    // String - Tooltip font weight style
    tooltipFontStyle: "normal",

    // String - Tooltip label font colour
    tooltipFontColor: "#fff",

    // String - Tooltip title font declaration for the scale label
    tooltipTitleFontFamily: "'Helvetica Neue', 'Helvetica', 'Arial', sans-serif",

    // Number - Tooltip title font size in pixels
    tooltipTitleFontSize: 14,

    // String - Tooltip title font weight style
    tooltipTitleFontStyle: "bold",

    // String - Tooltip title font colour
    tooltipTitleFontColor: "#fff",

    // Number - pixel width of padding around tooltip text
    tooltipYPadding: 6,

    // Number - pixel width of padding around tooltip text
    tooltipXPadding: 6,

    // Number - Size of the caret on the tooltip
    tooltipCaretSize: 8,

    // Number - Pixel radius of the tooltip border
    tooltipCornerRadius: 6,

    // Number - Pixel offset from point x to tooltip edge
    tooltipXOffset: 10,

    // String - Template string for single tooltips
    tooltipTemplate: "<%if (label){%><%=label%>: <%}%><%= value %>",

    // String - Template string for multiple tooltips
    multiTooltipTemplate: "<%= value %>",

    // Function - Will fire on animation progression.
    onAnimationProgress: function(){},

    // Function - Will fire on animation completion.
    onAnimationComplete: function(){}
   };
        
        var ctx = document.getElementById("line-chart").getContext("2d");
        //var myNewChart = new Chart(ctx).PolarArea(data);
        //lineChartOptions.datasetFill = false;
         var lineChartOptions =areaChartOptions;
        lineChartOptions.datasetFill = false;
        //lineChartOptions.responsive = true;
        myLineChart = new Chart(ctx).Line(drawdata,lineChartOptions);
        //Chart.defaults.global.responsive = true;
}						 

function RemoveGraph()
{
    myLineChart.destroy();
           
}
 </script>
<style>
  h3.timeHeading
  {
    margin-top : 4px !important
    
  }
  .widget-header.logo {
    background-image: url("bootstrap/img/anayltcs_header_icons.png");
    float: left;
    height: 38px;
    width: 38px;
}
#customCss .box-header
{
  background: none repeat scroll 0 0 #3c8dbc;
  color: #fff;
  }  
#conditionalDiv {
  padding: 0px;
}
.item
{
  width: 25%;
  height: 50px
  
}
#big-numbers-container.dashboard {
    -moz-border-bottom-colors: none;
    -moz-border-left-colors: none;
    -moz-border-right-colors: none;
    -moz-border-top-colors: none;
    border-color: #b8b9b9 #c2c2c2 -moz-use-text-color;
    border-image: none;
    border-left: 1px solid #c2c2c2;
    border-right: 1px solid #c2c2c2;
    border-style: solid solid none;
    border-width: 1px 1px medium;
    padding-bottom: 8px;
}
.three-column:first-child {
    width: 33.33%;
}
.three-column {
    width: 33.33%;
}
.big-numbers {
    float: left;
    position: relative;
    cursor: pointer
}

.big-numbers:first-child .inner {
    border-left: medium none;
}
.big-numbers.active .inner {
    background-color: #fff;
}
#analyticsUser .widget-content .big-numbers .inner {
    cursor: pointer;
    height: 98px;
}
#analyticsUser .big-numbers .inner {
    background-color: #f9f9f9;
    border-bottom: 1px solid #a1a1a1;
    border-left: 1px solid #c9c9c9;
    height: 98px;
    text-align: center;
    padding: 48px
}

.big-numbers.active:nth-child(1) .logo {
    background-position: 5px center;
}
.big-numbers:nth-child(1) .inner .logo {
    background-position: -55px center;
}
.big-numbers .inner .logo {
    background-image: url("bootstrap/img/dashboard_icons.png");
    height: 60px;
    margin: 0 auto;
    width: 60px;
}

.big-numbers.active .number {
    color: #333;
    text-shadow: 0 1px #fff;
}
.widget-content .big-numbers .number {
    margin-top: 1px;
}
.big-numbers .number {
    color: #666;
    font: 30px Oswald;
    text-shadow: 0 1px #fff;
}
element.style {
    background-image: url("bootstrap/img/utrend.png");
}
.widget-content .big-numbers .trend {
    height: 9px;
    position: absolute;
    right: 7%;
    top: 11px;
    width: 12px;
}

.big-numbers.active .change {
    color: #333;
}
.widget-content .big-numbers .change {
    color: #999;
    font: 10px Ubuntu,Helvetica,sans-serif;
    position: absolute;
    right: 15px;
    text-shadow: 0 1px #fff;
    top: -1px;
    white-space: nowrap;
}

.big-numbers .spark {
    margin-top: 11px;
}
.big-numbers .spark {
    margin-top: 11px;
}

.big-numbers.active .select {
    /*background-color: #2b2b2b;
    background-image: linear-gradient(to bottom, #484848 20%, #2b2b2b 100%);*/
    background-color: #3c8dbc;
    background-image: linear-gradient(to bottom, #3c8dbc 20%, #2b2b2b 100%);
    border-bottom: 1px solid #616161;
    border-top-color: #404040;
    bottom: 0;
    box-shadow: 0 1px 0 #5e5e5e inset;
    color: #eee;
    text-shadow: 0 -1px #333;
}
.widget-content .big-numbers .select {
    border-top: 1px solid #a1a2a2;
    bottom: 1px;
    cursor: pointer;
}
#analyticsUser .big-numbers .select {
    background-color: #d6d6d6;
    background-image: linear-gradient(to bottom, #e1e1e1 20%, #d6d6d6 100%);
    box-shadow: 0 1px 0 #f6f6f6 inset;
    color: #5b5b5b;
    font: 13px Ubuntu,Helvetica,sans-serif;
    height: 28px;
    padding-top: 7px;
    position: absolute;
    text-shadow: 0 1px #fff;
    width: 100%;
    text-align: center;
    border-right: 1px solid #c9c9c9;
}

.big-numbers.active .arrow {
    display: block;
}
.big-numbers .arrow {
    background-image: url("bootstrap/img/dashboard-arrow.png");
    bottom: -6px;
    display: none;
    height: 8px;
    left: 50%;
    margin-left: -9px;
    position: absolute;
    width: 18px;
    z-index: 2;
}

.dashboard-summary .item {
    float: left;
    font-family: Oswald;
}
.dashboard-summary .inner {
    background-color: #3c3c3c;
    background-image: linear-gradient(to bottom, #3c3c3c 10%, #2a2a2a 100%);
    border-radius: 5px;
    margin-left: 5px;
    padding: 10px;
}

.dashboard-summary .item .title {
    color: #fff;
    font: 12px Ubuntu,Helvetica,sans-serif;
    text-align: center;
}
.dashboard-summary .item .bar {
    background-color: transparent;
    border-radius: 5px;
    height: 6px;
    margin: 5px auto;
    overflow: auto;
    padding-bottom: 2px;
    padding-top: 2px;
    width: 80%;
}
.dashboard-summary .item .bar .bar-inner:first-child {
    border-bottom-left-radius: 5px;
    border-top-left-radius: 5px;
    box-shadow: none;
}
.dashboard-summary .item .bar .bar-inner {
    background-color: #6bb96e;
    box-shadow: 2px 0 0 0 #333 inset;
    cursor: pointer;
    float: left;
    height: 6px;
    width: 40%;
}
.dashboard-summary .item .bar .bar-inner:nth-child(2) {
    background-color: #86cbdd;
    width: 42%;
}
.dashboard-summary .item .bar .bar-inner:last-child {
    border-bottom-right-radius: 5px;
    border-top-right-radius: 5px;
}
.dashboard-summary .item .bar .bar-inner {
    background-color: #6bb96e;
    box-shadow: 2px 0 0 0 #333 inset;
    cursor: pointer;
    float: left;
    height: 6px;
    width: 40%;
}

.dashboard-summary .item .number {
    color: #6bb96e;
    font-size: 26px;
    line-height: 33px;
    overflow: hidden;
    text-align: center;
    text-overflow: ellipsis;
    white-space: nowrap;
}
.active-header-btn,.btn-default:hover
{
  background: #2b2b2b!important;
  border: 1px solid #2b2b2b!important;
  color: white!important;
}

element.style {
    background-image: url("/images/dashboard/dtrend.png");
}
.big-numbers .trend {
    height: 9px;
    position: absolute;
    right: 20px;
    top: 62px;
    width: 12px;
}
 .daterangepicker .calendar th, .daterangepicker .calendar td
{
    min-width:0px!important;
    font-size: 12px!important;
}
.table-condensed > tbody > tr > td, .table-condensed > tbody > tr > th, .table-condensed > tfoot > tr > td, .table-condensed > tfoot > tr > th, .table-condensed > thead > tr > td, .table-condensed > thead > tr > th
{
padding:3px!important;
}
.daterangepicker_start_input, .daterangepicker_end_input{
display : none!important;
}
.daterangepicker .calendar-date
{
    padding: 0px!important;
}
</style>    
  