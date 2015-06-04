
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
                    <h3 class="timeHeading pull-left">SESSIONS</h3>
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
                  <div id="line-chart" style="height: 300px;"></div>           
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
                      <tr role="row"><th style="width: 167px;">Date</th><th>Total Session</th><th>New Session</th><th>Unique Session</th></tr>
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
    <script src="<?php echo base_url() ?>assets/js/plugins/flot/jquery.flot.min.js" type="text/javascript"></script>
    <!-- FLOT RESIZE PLUGIN - allows the chart to redraw when the window is resized -->
    <script src="<?php echo base_url() ?>assets/js/plugins/flot/jquery.flot.resize.min.js" type="text/javascript"></script>
    <!-- FLOT PIE PLUGIN - also used to draw donut charts -->
    <script src="<?php echo base_url() ?>assets/js/plugins/flot/jquery.flot.pie.min.js" type="text/javascript"></script>
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
         
         ajaxCall();   //  Bydefault Load Today Data 
         //drawDataGrid(data);
                
                var data_today = [
                  ['21May',2666],
                  ['22May',2777],
                  ['23May',1000],
                  ['24May',2000],
                  ['25May',2500],
                  ['26May',8900],
                  ['27May',15000],
                  ['28May',5600],
                  ['29May',5892],
                  ['30May',1478],
                  ['31May',2356],
                  ['1Jun',500],
                ];
                var data_today1 = [
                  ['21 May',100],
                  ['22 May',27],
                  ['23 May',14500],
                  ['24 May',20060],
                  ['25 May',250],
                  ['26 May',800],
                  ['27 May',5000],
                  ['28 May',600],
                  ['29 May',592],
                  ['30 May',14780],
                  ['31 May',14780],
                  ['1 Jun',23561],
                ];
                var data_today2 = [
                  ['21 May',266],
                  ['22 May',2007],
                  ['23 May',1550],
                  ['24 May',1000],
                  ['25 May',2030],
                  ['26 May',7600],
                  ['27 May',7000],
                  ['28 May',8600],
                  ['29 May',5002],
                  ['30 May',1508],
                  ['31 May',2006],
                  ['1 Jun',3400],
                ];
          $('.mailbox-controls .btn-default').click(function () {
            $('.btn-default').removeClass('active-header-btn');
            $(this).addClass('active-header-btn'); 
            ajaxCall();
          });
        $(".reservation").daterangepicker();
        var gdpData = {
            "AF": 16.63,
            "AL": 11.58,
            "DZ": 158.97,
        };       
    
        drawGraph(data_today);
        });
      
        function drawGraph(data_today)
        {
            $.plot("#line-chart", [data_today], {
          grid: {
            hoverable: true,
            borderColor: "#f3f3f3",
            borderWidth: 1,
            tickColor: "#f3f3f3"
          },
          series: {
            shadowSize: 0,
            lines: {
              show: true
            },
            points: {
              show: true
            }
          },
          lines: {
            fill: false,
            color: ["#3c8dbc", "#f56954"]
          },
          yaxis: {
            show: true,
          },
          xaxis: {
             show: true
          }
        });
    }    
    
      function dateRange($divs) {
        $("#"+$divs+" .reservation").daterangepicker('show');
      }  
		 function ajaxCall(){
			var num = $('.btn-group').find(".active-header-btn").attr('value')
                      	$(function()
                        { // start of doc ready.
                            $.ajax({ 	 	
                            dataType:'json',
                                  url: 'Sessions',
                                  data: {'daydiff': num}, // change this to send js object
                                  type: "post",

                                  success: function(data){                                    
                                    
                                    //Draw Grid
                                   // console.log(data.total[0]);
                                    $('#total').html(data.total[0]);
                                    $('#new').html(data.total[1]);
                                    $('#unique').html(data.total[2]);
                                    $("#example1").dataTable().fnDestroy();
                                    drawDataGrid(data.grid);                                  
                                  }
                            });
					 
			});
			//console.log($('.btn-group').find(".active-header-btn").text());
		//console.log($(".big-numbers.active").find(".select").text());
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
  