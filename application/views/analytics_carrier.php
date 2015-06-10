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
                    <div class="logo app-versions"></div>
                    <h3 class="timeHeading pull-left">Carrier</h3>
          <div class="mailbox-controls pull-right">
                    <!-- Check all button -->
                    <a class="btn btn-default btn-sm reservation" onclick="dateRange()"><i class="fa fa-calendar"></i></a>
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
                  <div style="width: 50%;float: left;border-right: 1px solid gray">
                      <div id="placeholder" style="margin: 10px 0px">
                      
                          <canvas height="250" id="pieChart1"  width="499"></canvas>
                      </div>
                  <div class='newUserGrid'>New Users</div></div>
                   <div style="width: 50%;float: left;border-right: 1px solid gray">
                       <div id="placeholder1" style="margin: 10px 0px">
                           <canvas height="250" id="pieChart2"  width="499"></canvas>
                       </div>
                  <div class='newUserGrid'>Total Users</div></div>
                  <div style="clear: both"></div>
              </div><!--/.col (left) -->
             
              <div class="box">
                <div class="box-body">
                  <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                    <div class="row"><div class="col-sm-12">
                    <table class="table table-bordered table-striped dataTable" id="example1" role="grid" aria-describedby="example1_info">
                    <thead>
                      <tr role="row"><th style="width: 167px;">Device</th><th>Total Sessions</th><th>Total Users</th><th>New Users</th></tr>
                    </thead>
                    <tbody>
                    <!-- <tr role="row" class="odd">
                        <td class="sorting_1">Galaxy S3</td>
                        <td>16,368</td>
                        <td>15,678</td>
                        <td>3,583</td>  
                    </tr>
                    <tr role="row" class="odd">
                        <td class="sorting_1">iPhone4</td>
                        <td>16,368</td>
                        <td>15,678</td>
                        <td>3,583</td>  
                    </tr>
                  <tr role="row" class="odd">
                        <td class="sorting_1">iPhone3GS</td>
                        <td>16,368</td>
                        <td>15,678</td>
                        <td>3,583</td>  
                    </tr>
                  <tr role="row" class="odd">
                        <td class="sorting_1">iPad 2</td>
                        <td>16,368</td>
                        <td>15,678</td>
                        <td>3,583</td>  
                    </tr>  -->         
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
     <!--<script src="<?php echo base_url() ?>assets/js/plugins/flot/jquery.flot.min.js" type="text/javascript"></script>-->
    <!-- FLOT RESIZE PLUGIN - allows the chart to redraw when the window is resized -->
    <!--<script src="<?php echo base_url() ?>assets/js/plugins/flot/jquery.flot.resize.min.js" type="text/javascript"></script>-->
    <!-- FLOT PIE PLUGIN - also used to draw donut charts -->
   <!-- <script src="<?php echo base_url() ?>assets/js/plugins/flot/jquery.flot.pie.min.js" type="text/javascript"></script>-->
    <!-- FLOT CATEGORIES PLUGIN - Used to draw bar charts -->
    <script src="<?php echo base_url() ?>assets/js/plugins/flot/jquery.flot.categories.min.js" type="text/javascript"></script>
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
   var myDoughnutChart1;
   var myDoughnutChart2;
  var drawdata = [{value:0,color:"#370bff",highlight:"#affcda",label:"Galaxy S3"},
            {value:2,color:"#e74e04",highlight:"#33f723",label:"testing"}];

   function parseJson(data)
                {        
                   $('#example1').dataTable({
                     data: data,
                     bFilter: false,
                     bLengthChange: false,
                     }); 
                 }
   	function ajaxCall(num){
			//str = $('.btn-group').find(".active-header-btn").text();
			
			//var num = +str.match(/-?\d+\.?\d*/);
			
                      	$(function()
                        { // start of doc ready.
                            $.ajax({ 	 	
                            dataType:'json',
                                  url: 'Carrier',
                                  data: {'daydiff': num}, // change this to send js object
                                  type: "post",
                                success: function(data){   
                                //   console.log(data.totalusergraph);
								  //  console.log(eval(data.totalusergraph));
                                $("#example1").dataTable().fnDestroy();
                                parseJson(data.grid);
                                
                                RemoveGraph();
                                DrawBarGraph(eval(data.totalusergraph),eval(data.newusergraph)) ;                                    
                                  }
                                     });
					});
				}
   
   
      $(document).ready(function(){
	  //  ajaxCall();
           $('#example1').dataTable({
              bFilter: false,
              bLengthChange: false,
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
		  
		  
        $(".reservation").daterangepicker({
            format: 'YYYY-MM-DD',
        });		
		
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
        
      	
        function labelFormatter(label, series) {
		return "<div style='font-size:8pt; text-align:center; padding:2px; color:white;'>" + label + "<br/>" + Math.round(series.percent) + "%</div>";
	}
        
            DrawBarGraph(drawdata,drawdata);
        });
      
      function dateRange($divs)
      {
        $("#"+$divs+" .reservation").daterangepicker('show');
      }
      
     function DrawBarGraph(drawdata1,drawdata2)
        {
         var options = {

                //Boolean - Whether we should show a stroke on each segment
          segmentShowStroke : true,
          
          responsive: true,

          //String - The colour of each segment stroke
          segmentStrokeColor : "#fff",

          //Number - The width of each segment stroke
          segmentStrokeWidth : 2,

          //Number - The percentage of the chart that we cut out of the middle
          percentageInnerCutout : 50, // This is 0 for Pie charts

          //Number - Amount of animation steps
          animationSteps : 100,

          //String - Animation easing effect
          animationEasing : "easeOutBounce",

          //Boolean - Whether we animate the rotation of the Doughnut
          animateRotate : true,

          //Boolean - Whether we animate scaling the Doughnut from the centre
          animateScale : false,

          //String - A legend template
          legendTemplate : "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<segments.length; i++){%><li><span style=\"background-color:<%=segments[i].fillColor%>\"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>"

        };
//ar myDoughnutChart = new Chart(ctx[1]).Doughnut(data,options);
             var ctx = document.getElementById("pieChart1").getContext("2d");
            
            myDoughnutChart1 = new Chart(ctx).Doughnut(drawdata1,options);
             //Chart.defaults.global.responsive = true;
             
             var ctx = document.getElementById("pieChart2").getContext("2d");
            
            myDoughnutChart2 = new Chart(ctx).Doughnut(drawdata2,options);
     } 

        function RemoveGraph()
        {
            myDoughnutChart1.destroy();
            myDoughnutChart2.destroy();

        } 
    </script>
<style>
  h3.timeHeading
  {
    margin-top : 4px !important
    
  }
  .widget-header .logo.app-versions {
    background-position: -266px center;
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
.dashboard-summary .item {
    float: left;
    font-family: Oswald;
    width: 33%;
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
.newUserGrid {
    background-color: #d6d6d6;
    background-image: linear-gradient(to bottom, #e1e1e1 20%, #d6d6d6 100%);
    border-right: 1px solid #c9c9c9;
    box-shadow: 0 1px 0 #f6f6f6 inset;
    color: #5b5b5b;
    font: 15px Ubuntu,Helvetica,sans-serif;
    height: 30px;
    padding-top: 8px;
    text-align: center;
    text-shadow: 0 1px #fff;
    width: 100%;
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
  