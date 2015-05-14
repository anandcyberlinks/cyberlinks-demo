<!-- Morris chart -->
 <link href="<?php echo base_url() ?>assets/css/morris.css" rel="stylesheet" type="text/css" />
 <script src="http://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
 <script src="<?php echo base_url() ?>assets/js/AdminLTE/morris.min.js"></script>
<div class="content-wrapper" style="min-height: 281px;">
        <div class="container">
          <!-- Content Header (Page header) -->
          <section class="content-header">
            <h1>
              Push Analytics
            </h1>
            <!--ol class="breadcrumb">
              <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
              <li class="active">Dashboard</li>
            </ol-->
          </section>

          <!-- Main content -->
          <section class="content">
              <div class='row'>
                  <div class="col-md-12">
                    <div class="box box-danger">
                        <div class="box-header">


                                <form  method="post" action="<?php echo base_url(); ?>push_notification/push_analytics" id="searchIndexForm" name="searchIndexForm" accept-charset="utf-8">        
                                <div class="form-group col-lg-3">
                                    <div class="input select">
                                        <label for="searchBy"><?php echo $welcome->loadPo('Search By') ?></label>
                                        <select name="searchby" class="form-control" placeholder="<?php echo $welcome->loadPo('Search By') ?>" id="searchby" onchange="toggle_searching();">
                                            <!--option value=""><?php echo $welcome->loadPo('Select') ?></option-->
                                            <option value="today" <?php if(isset($_POST['searchby']) && $_POST['searchby']=='today') { ?> selected="selected" <?php } ?>><?php echo $welcome->loadPo('Today') ?></option>
                                            <option value="date" <?php if(isset($_POST['searchby']) && $_POST['searchby']=='date') { ?> selected="selected" <?php } ?>><?php echo $welcome->loadPo('Specific Date') ?></option>
                                            <option value="all" <?php if(isset($_POST['searchby']) && $_POST['searchby']=='all') { ?> selected="selected" <?php } ?>><?php echo $welcome->loadPo('All') ?></option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group col-lg-3">
                                    <div class="input text">
                                        <label for="url"><?php echo $welcome->loadPo('Specific Date') ?></label>
                                        <input type="text" class="form-control"  id="datepickerstart" name="datepickerstart" placeholder="<?php echo $welcome->loadPo('Specific Date') ?>" value="<?php echo (isset($_POST['datepickerstart'])) ? $_POST['datepickerstart'] : ''; ?>" >											
                                    </div>
                                </div>

                                      <div class="form-group col-lg-3">
                                          <div class="input text" style="margin-top:25px;">
                                       <label for="url"></label>
                                    <button type="submit" name="submit" value="Search"class="btn btn-primary"><?php echo $welcome->loadPo('Search') ?></button>        
                                    </div>
                                </div>


                                </form>    
                        </div>
                    </div>
                  </div>
                </div>
          <form role="form">
          <div class="row">
            
            <!-- left column -->
            <div class="col-md-12">
              <!-- Form Element sizes -->
              <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">Analytics Data</h3>
                </div>
                <div id="conditionalDiv1" class="box-body">
                  <div class="row">
                    <div class="col-xs-3">
                        <div style="border: 1px solid #DD4B39!important" class="box box-success box-solid">
                          <div style="background: #DD4B39!important;text-align: center" class="box-header with-border">
                          <h3 class="box-title">Total pushes sent</h3>
                        </div><!-- /.box-header -->
                        <div style="color: #DD4B39!important;font-size: 18px;text-align: center" class="box-body">
                           <?php echo number_format($total_sent_notification); ?>
                        </div><!-- /.box-body -->
                    </div>                     
                  </div>

                    <div class="col-xs-3">
                        <div style="border: 1px solid #F76B0F!important" class="box box-success box-solid">
                          <div style="background: #F76B0F!important;text-align: center" class="box-header with-border">
                          <h3 class="box-title">Total direct opens</h3>
                        </div><!-- /.box-header -->
                        <div style="color: #F76B0F!important;font-size: 18px;text-align: center" class="box-body">
                           <?php echo number_format($total_open_notification); ?>
                        </div><!-- /.box-body -->
                    </div>                     
                  </div>                  
                    
                    <div class="col-xs-3">
                        <div class="box box-success box-solid">
                          <div style="text-align: center" class="box-header with-border">
                          <h3 class="box-title">Direct open rate</h3>
                        </div><!-- /.box-header -->
                        <div style="color: #00A65A!important;font-size: 18px;text-align: center" class="box-body">
                           <?php echo $direct_open_rate; ?>%
                        </div><!-- /.box-body -->
                    </div>                     
                  </div>
                    <div class="col-xs-3">
                        <div style="border: 1px solid #C234C5!important" class="box box-success box-solid">
                          <div style="background: #C234C5!important;text-align: center" class="box-header with-border">
                          <h3 class="box-title">Average open rate</h3>
                        </div><!-- /.box-header -->
                        <div style="color: #C234C5!important;font-size: 18px;text-align: center" class="box-body">
                           2.68% 
                        </div><!-- /.box-body -->
                    </div>                     
                  </div>                    
                </div>
                    <br>
                </div><!-- /.box-body -->
              

               
            </div><!--/.col (left) -->
            <!-- right column -->
          </div>   <!-- /.row -->
          
        </div></form>
              
              
          <div class="row">
            
            <div class="col-md-12">
              <!-- LINE CHART -->
              <div class="box box-info">
                <div class="box-header with-border">
                  <h3 class="box-title">Line Chart</h3>
                  <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div>
                </div>
                <div class="box-body chart-responsive">
                  <div class="chart" id="line-chart" style="height: 300px;"></div>
                </div><!-- /.box-body -->
              </div><!-- /.box -->

            </div><!-- /.col (RIGHT) -->
          </div><!-- /.row -->
          <div class="box box-default">
              
              <div class="box-body" style="text-align: center;">
                <h4 class="box-title">Push open on <?php if($date!='all_dates'){ echo date("F jS, Y", strtotime($date)); }else{ echo 'All Dates';} ?></h4>
              </div><!-- /.box-body -->
            </div>
          
          </section>
        </div><!-- /.container -->
      </div>

<script type="text/javascript">
    $(function() {
        toggle_searching();
        
        "use strict";
        
        // LINE CHART
        
        var data = [];
   <?php foreach ($hourly_open_over_time as $key => $count) { ?>
    data.push({hours:"<?php echo $key; ?>", a:"<?php echo $count; ?>"});
   <?php } ?>
       //console.log(data);
        var line = new Morris.Line({
          element: 'line-chart',
          resize: true,
          /*data: [
            {y: '2011 Q1', item1: 2666},
            {y: '2011 Q2', item1: 2778},
            {y: '2011 Q3', item1: 4912},
            {y: '2011 Q4', item1: 3767},
            {y: '2012 Q1', item1: 6810},
            {y: '2012 Q2', item1: 5670},
            {y: '2012 Q3', item1: 4820},
            {y: '2012 Q4', item1: 15073},
            {y: '2013 Q1', item1: 10687},
            {y: '2013 Q2', item1: 8432}
          ],
          data: [            
                { hours: '00:00 (UTC)', a: 793},
                { hours: '01:00 (UTC)', a: 524},
                { hours: '02:00 (UTC)', a: 337},
                { hours: '03:00 (UTC)', a: 272},
                { hours: '04:00', a: 176},
                { hours: '05:00', a: 174},
                { hours: '06:00', a: 212},
                { hours: '07:00', a: 341},
                { hours: '08:00', a: 551},
                { hours: '09:00', a: 724},
                { hours: '10:00', a: 880},
                { hours: '11:00', a: 955},
                { hours: '12:00', a: 1053},
                { hours: '13:00', a: 1063},
                { hours: '14:00', a: 92},
                { hours: '15:00', a: 0},
                { hours: '16:00', a: 0},
                { hours: '17:00', a: 0},
                { hours: '18:00', a: 0},
                { hours: '19:00', a: 0},
                { hours: '20:00', a: 0},
                { hours: '21:00', a: 0},
                { hours: '22:00', a: 0},
                { hours: '23:00', a: 0},
            ], */
          xkey: 'hours',
          ykeys: ['a'],
          labels: ['Push Count'],
          //gridIntegers: true,
          parseTime: false,
          lineColors: ['#3c8dbc'],
          hideHover: 'auto'
        });
         line.setData(myData());
    });
    
    function myData(){
        var data = [];
   <?php foreach ($hourly_open_over_time as $key => $count) { ?>
    data.push({hours:"<?php echo $key; ?>"+":00 (UTC)", a:"<?php echo $count; ?>"});
   <?php } ?>
       return data;
    }
    function toggle_searching()
        {
            var searching_method = $('#searchby').val();
            
           // alert(searching_method);
            if(searching_method=='today' || searching_method=='all'){
                
                var myDate = new Date();
                var todayDate = myDate.getDate()+ '/' + (myDate.getMonth()+1) + '/' +
                        myDate.getFullYear();
                $("#datepickerstart").val(todayDate);
                $('#datepickerstart').attr('readonly', true);
                $('#datepickerstart').attr('disabled', 'disabled');
                
            }else if(searching_method=='date'){
                
                $("#datepickerstart").val("<?php echo $_POST['datepickerstart']; ?>");
                $('#datepickerstart').removeAttr('readonly');
                $('#datepickerstart').removeAttr('disabled');
            }
        }
</script>