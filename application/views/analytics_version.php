
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
                    <div class="logo app-versions"></div>
                    <h3 class="timeHeading pull-left">APP VERSIONS</h3>
          <div class="mailbox-controls pull-right">
                    <!-- Check all button -->
                    <a class="btn btn-default btn-sm reservation" onclick="dateRange()"><i class="fa fa-calendar"></i></a>
                    <div class="btn-group ">
                      <a class="btn btn-default btn-sm year-class" value="365"><?php echo date("Y"); ?></a>
                      <a class="btn btn-default btn-sm active-header-btn today-class" value="1">Today</a>
                      <a class="btn btn-default btn-sm week-class" value="7">7 Days</a>
                      <a class="btn btn-default btn-sm month-class" value="30">30 Days</a>
                      <a class="btn btn-default btn-sm months2-class" value="60">60 Days</a>
                      <a class="btn btn-default btn-sm months3-class" value="90">90 Days</a>
                    </div><!-- /.btn-group -->
                  </div>
                </div>
                  <!--<div id="line-chart" style="height: 300px;"></div>-->
				   <div>
                      
                      <canvas id="line-chart" width="1250" height="250"></canvas>
                  </div> 
              </div><!--/.col (left) -->
            

              <div class="box">
                <div class="box-body">
                  <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                    <div class="row"><div class="col-sm-12">
                    <table class="table table-bordered table-striped dataTable" id="example1" role="grid" aria-describedby="example1_info">
                    <thead>
                      <tr role="row"><th style="width: 167px;">App Version</th><th>Total Sessions</th><th>Total Users</th><th>New Users</th></tr>
                    </thead>
                    <tbody>
                   <!-- <tr role="row" class="odd">
                        <td class="sorting_1">1.0</td>
                        <td>16,368</td>
                        <td>15,678</td>
                        <td>3,583</td>  
                    </tr>
                    <tr role="row" class="odd">
                        <td class="sorting_1">1.1</td>
                        <td>16,368</td>
                        <td>15,678</td>
                        <td>3,583</td>  
                    </tr>
                  <tr role="row" class="odd">
                        <td class="sorting_1">1.2</td>
                        <td>16,368</td>
                        <td>15,678</td>
                        <td>3,583</td>  
                    </tr>
                  <tr role="row" class="odd">
                        <td class="sorting_1">1.3</td>
                        <td>16,368</td>
                        <td>15,678</td>
                        <td>3,583</td>  
                    </tr>
                  <tr role="row" class="odd">
                        <td class="sorting_1">1.4</td>
                        <td>16,368</td>
                        <td>15,678</td>
                        <td>3,583</td>  
                    </tr>    -->              
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
    <!-- <script src="<?php //echo base_url() ?>assets/js/plugins/flot/jquery.flot.min.js" type="text/javascript"></script>
    <!-- FLOT RESIZE PLUGIN - allows the chart to redraw when the window is resized -->
    <!-- <script src="<?php //echo base_url() ?>assets/js/plugins/flot/jquery.flot.resize.min.js" type="text/javascript"></script>
    <!-- FLOT PIE PLUGIN - also used to draw donut charts -->
  <!--   <script src="<?php //echo base_url() ?>assets/js/plugins/flot/jquery.flot.pie.min.js" type="text/javascript"></script>
    <!-- FLOT CATEGORIES PLUGIN - Used to draw bar charts -->
    <!-- <script src="<?php //echo base_url() ?>assets/js/plugins/flot/jquery.flot.categories.min.js" type="text/javascript"></script> -->
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
      
	
	function dataprepare(resultset){
	
	drawdata = {
    //labels: ["January", "1", "March", "April", "May", "June", "July"],
    labels: resultset.label,
    datasets: [
        {
            label: "My First dataset",
            fillColor: "rgba(220,220,220,0.5)",
            strokeColor: "rgba(220,220,220,0.8)",
            highlightFill: "rgba(220,220,220,0.75)",
            highlightStroke: "rgba(220,220,220,1)",
            data: resultset.totaluser
        },
        {
            label: "My Second dataset",
            fillColor: "rgba(151,187,205,0.5)",
            strokeColor: "rgba(151,187,205,0.8)",
            highlightFill: "rgba(151,187,205,0.75)",
            highlightStroke: "rgba(151,187,205,1)",
            data: resultset.newuser
        }
    ]
}
	return drawdata;
};

function DrawLineChart(drawdata){
 
	 var areaChartOptions = {
    //Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
    scaleBeginAtZero : true,

    //Boolean - Whether grid lines are shown across the chart
    scaleShowGridLines : true,

    //String - Colour of the grid lines
    scaleGridLineColor : "rgba(0,0,0,.05)",

    //Number - Width of the grid lines
    scaleGridLineWidth : 1,

    //Boolean - Whether to show horizontal lines (except X axis)
    scaleShowHorizontalLines: true,

    //Boolean - Whether to show vertical lines (except Y axis)
    scaleShowVerticalLines: true,

    //Boolean - If there is a stroke on each bar
    barShowStroke : true,

    //Number - Pixel width of the bar stroke
    barStrokeWidth : 2,

    //Number - Spacing between each of the X value sets
    barValueSpacing : 5,

    //Number - Spacing between data sets within X values
    barDatasetSpacing : 1,
	
	responsive: true,

    //String - A legend template
    legendTemplate : "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].fillColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>"

}


 var ctx = document.getElementById("line-chart").getContext("2d");
 var myBarChart = new Chart(ctx).Bar(drawdata, areaChartOptions);
        //var myNewChart = new Chart(ctx).PolarArea(data);
        //lineChartOptions.datasetFill = false;
        // var lineChartOptions =areaChartOptions;
       // lineChartOptions.datasetFill = false;
        //lineChartOptions.responsive = true;
       // myLineChart = new Chart(ctx).Line(drawdata,lineChartOptions);
        //Chart.defaults.global.responsive = true;
		/* 	
		new Chart(ctx).Bar(drawdata, {
				barShowStroke: false
			});
	   */

 } 
 
	
	  
	 
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
		//	var num = +str.match(/-?\d+\.?\d*/);
			$(function()
			{ // start of doc ready.
				$.ajax({ 	 	
				dataType:'json',
					  url: 'Versions',
					  data: {'daydiff': num}, // change this to send js object
					  type: "post",
					  success: function(data){   
				   // console.log(data.graph);
					$("#example1").dataTable().fnDestroy();
					parseJson(data.grid);
					d=dataprepare(data.graph);
					//RemoveGraph();
					DrawLineChart(d);							
				 }
			});
		});
	}
	  
	  
	  
	  $(document).ready(function(){	
            $('.mailbox-controls .btn-default').click(function () {
            $('.btn-default').removeClass('active-header-btn');
            $(this).addClass('active-header-btn'); 
		
             var num = $('.btn-group').find(".active-header-btn").attr('value');           
             if(!(num ===undefined))
             {
                 ajaxCall(num);
             } 
			
          });
		  ajaxCall('Today');
      //  $(".reservation").daterangepicker();
		 $(".reservation").daterangepicker({
            format: 'YYYY-MM-DD',
        });	
		
			 function dateRange() {
				$(".reservation").daterangepicker('show');
			  }
  
		
		 $('.applyBtn').on('click',function(){
			console.log("----------");
            num =[]; 
            strdt = $('input[name="daterangepicker_start"]').val();
            enddt = $('input[name="daterangepicker_end"]').val();
            num[0] =strdt;
            num[1] =enddt;
            console.log(strdt);
           
            //alert($('input[name="daterangepicker_start"]').val());
             ajaxCall(num);
            });
		
		
     });
      
 
  
  
  
  
  
  
  
	function RemoveGraph()
	{
		myBarChart.destroy();           
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
  