<link href="<?=base_url()?>assets/css/morris/morris.css" rel="stylesheet" type="text/css" />
<!--[if lte IE 8]> <script src="<?=base_url()?>assets/js/plugins/flot/excanvas.min.js"></script><![endif]-->
<script src="<?=base_url()?>assets/js/jquery.js"></script>
<script src="<?=base_url()?>assets/js/plugins/flot/jquery.flot.js"></script>
<script src="<?=base_url()?>assets/js/plugins/flot/jquery.flot.pie.js"></script>
<style>
    
    .placeholder {
            width: 250px;
            height: 250px;
    }
    .map_placeholder {
            width: 350px;
            height: 325px;
    }
	
</style>
    
<aside class="right-side">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> 
            <?php echo $welcome->loadPo('Ads Analytics'); ?>
            <small><?php echo $welcome->loadPo('Control panel'); ?></small>
        </h1>
		
        <ol class="breadcrumb">
            <i class="fa fa-dashboard"></i>
            <li class="active"><?php echo $welcome->loadPo('Ads Analytics') ?></li>
        </ol>
    </section>
	<div>
            <div id="msg_div">
                <?php echo $this->session->flashdata('message');?>
            </div>	
	</div>

    <!-- Main content -->
    <section class="content">
        <!-- Small boxes (Stat box) -->
	
        <div class="row">
            
            <div class="col-lg-2 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-light-blue">
                    <div class="inner">
                        <h3>
                            <?php echo $summary->total_hits;?>	
                        </h3>
                        <p>
                           <?php echo $welcome->loadPo('Total Hits'); ?> 
                        </p>
                    </div>
                   
                    <!--a href="<?php //echo base_url() ?>video" class="small-box-footer">
                        <?php //echo $welcome->loadPo('More info'); ?> <i class="fa fa-arrow-circle-right"></i>
                    </a>-->
                </div>
            </div>
	    <div class="col-lg-2 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3>
                            <?php echo $summary->unique_hits;?>	
                        </h3>
                        <p>
                           <?php echo $welcome->loadPo('Unique Users'); ?> 
                        </p>
                    </div>
                   
                    <!--a href="<?php //echo base_url() ?>video" class="small-box-footer">
                        <?php //echo $welcome->loadPo('More info'); ?> <i class="fa fa-arrow-circle-right"></i>
                    </a>-->
                </div>
            </div>
            <div class="col-lg-2 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-fuchsia">
                    <div class="inner">
                        <h3>
                            <?php echo ($summary->total_partial == '')? 0 : $summary->total_partial ;?>
                        </h3>
                        <p>
                           <?php echo $welcome->loadPo('Partial Play'); ?> 
                        </p>
                    </div>
                  
                    <!--a href="<?php //echo base_url() ?>video" class="small-box-footer">
                        <?php //echo $welcome->loadPo('More info'); ?> <i class="fa fa-arrow-circle-right"></i>
                    </a>-->
                </div>
            </div>
            <div class="col-lg-2 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-red">
                    <div class="inner">
                        <h3>
                           <?php echo ($summary->total_complete=='') ? 0 : $summary->total_complete ;?>
                        </h3>
                        <p>
                            <?php echo $welcome->loadPo('Complete Play'); ?>
                        </p>
                    </div>
                   
                    <!--a href="<?php //echo base_url() ?>video/video_status" class="small-box-footer">
                        <?php //echo $welcome->loadPo('More info'); ?> <i class="fa fa-arrow-circle-right"></i>
		    </a>-->
                </div>
            </div><!-- ./col -->
            
            <div class="col-lg-2 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-purple">
                    <div class="inner">
                        <h3>
                            <?php echo time_from_seconds($summary->total_watched_time); //-- common helper ?>
                        </h3>
                        <p>
                            <?php echo $welcome->loadPo('Total Time Watched'); ?>
                        </p>
                    </div>
                   
                    <!--a href="#" class="small-box-footer">
                        <?php //echo $welcome->loadPo('More info'); ?> <i class="fa fa-arrow-circle-right"></i>
                    </a>-->
                </div>
            </div><!-- ./col -->
        </div><!-- /.row -->
		<div class='row'>
            <div class="box box-danger">
                <div class="box-header">
                    <?php  
                        $last_month_class  = "btn btn-info btn-md";
                        $last_week_class  = "btn btn-info btn-md";
                        $yesterday_class  = "btn btn-info btn-md";
                        $today_class  = "btn btn-info btn-md";
                        if($_GET['range']=='today'){
                            $today_class  = "btn btn-danger btn-md";
                        }else if($_GET['range']=='yesterday'){
                            $yesterday_class  = "btn btn-danger btn-md";
                        }else if($_GET['range']=='lastweek'){
                            $last_week_class  = "btn btn-danger btn-md";
                        }else if($_GET['range']=='lastmonth'){
                            $last_month_class  = "btn btn-danger btn-md";
                        }
                    
                    ?>
				<!--div class="pull-right box-tools">
			    <a class='<?php echo $last_month_class?>' href="?range=lastmonth">Last Month</a>
			</div>				
				<div class="pull-right box-tools">
			   <a class='<?php echo $last_week_class?>' href="?range=lastweek">Last Week</a>
			</div>				
				<div class="pull-right box-tools">
			   <a class='<?php echo $yesterday_class?>' href="?range=yesterday">Yesterday</a>
			</div>
				<div class="pull-right box-tools">
			   <a class='<?php echo $today_class?>' href="?range=today">Today</a>
			</div-->
                        <form  method="post" action="<?php echo base_url(); ?>ads_analytics/report" onsubmit="return date_check();" id="searchIndexForm" name="searchIndexForm" accept-charset="utf-8">        
                        <div class="form-group col-lg-3">
                            <div class="input select">
                                <label for="searchBy"><?php echo $welcome->loadPo('Search By') ?></label>
                                <select name="searchby" class="form-control" placeholder="<?php echo $welcome->loadPo('Search By') ?>" id="searchby" onchange="toggle_searching();">
                                    <!--option value=""><?php echo $welcome->loadPo('Select') ?></option-->
                                    <option value="today" <?php if(isset($_POST['searchby']) && $_POST['searchby']=='today') { ?> selected="selected" <?php } ?>><?php echo $welcome->loadPo('Today') ?></option>
                                    <option value="date" <?php if(isset($_POST['searchby']) && $_POST['searchby']=='date') { ?> selected="selected" <?php } ?>><?php echo $welcome->loadPo('Specific Date') ?></option>
                                </select>
                            </div>
                        </div>
                            
                        <div class="form-group col-lg-3">
                            <div class="input text">
                                <label for="url"><?php echo $welcome->loadPo('Start Date') ?></label>
                                <input type="text" class="form-control"  id="datepickerstart" name="datepickerstart" placeholder="<?php echo $welcome->loadPo('Start Date') ?>" value="<?php echo (isset($_POST['datepickerstart'])) ? $_POST['datepickerstart'] : ''; ?>" >											
                            </div>
                        </div>
                        <div class="form-group col-lg-3">
                            <div class="input text">
                                <label for="url"><?php echo $welcome->loadPo('End Date') ?></label>
                                <input type="text" class="form-control"  id="datepickerend" name="datepickerend" placeholder="<?php echo $welcome->loadPo('End Date') ?>" value="<?php echo (isset($_POST['datepickerend'])) ? $_POST['datepickerend'] : ''; ?>">
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
        <div class="row">
	    <section class="col-lg-6"> 
		<!-- Box (with bar chart) -->
		<div class="box box-danger">
		    <div class="box-header">
                        
                        
                        
                        
                        
                        
			<!-- tools box -->
			<div class="pull-right box-tools">
			    <button class="btn btn-danger btn-sm" data-widget='collapse' data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
			</div><!-- /. tools -->
			<h3 class="box-title"><a href=''>In Stream Stitching Report</a></h3>
		    </div><!-- /.box-header -->
		    <div class="box-body no-padding">
			<div class="table-responsive">
                            <!-- .table - Uses sparkline charts-->
                            <table class="table table-striped">
				<tbody><tr>
				<th>Creative</th>
				<th>Channel</th>
				<th>Duration</th>
				<th>Impression</th>				
				<th>DateTime</th>				
				</tr>
			     <?php $i=0; foreach($stitchingReport as $row){ $i++;?>
				<tr>
                                <td>
                                    <?php if(isset($row->ad_title)) { ?>
                                        <a href="<?php echo base_url();?>ads/detail/<?php echo $row->ads_id; ?>"><?php echo $row->ad_title; ?></a>
                                    <?php } else { echo $row->Commercial; }?>    
                                </td>
                                <td><?php echo $row->channel; ?></td>    
				<td><?php echo $row->Duration;?></td>
				<td><?php echo $row->UserCount;?></td>				
				<td><?php echo $row->StartTime;?></td>				
				</tr>
			    <?php }?>
                            </tbody></table><!-- /.table -->
                        </div>
		    </div><!-- /.box-body -->
		    <div><a href='<?php echo base_url()?>ads_analytics/allStitchingReports' style="float:right;">View All</a></div>
		</div><!-- /.box -->
                
                <div class="box box-danger">
		    <div class="box-header">
			<!-- tools box -->
			<div class="pull-right box-tools">
			    <button class="btn btn-danger btn-sm" data-widget='collapse' data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
			</div><!-- /. tools -->
			<h3 class="box-title"><a href=''>Ad Wise Report</a></h3>
		    </div><!-- /.box-header -->
		    <div class="box-body no-padding">
			<div class="table-responsive">
                            <!-- .table - Uses sparkline charts-->
                            <table class="table table-striped">
				<tbody><tr>
				<th>Content</th>
				<th>Total Hits</th>
				<th>Total Time Watched</th>				
				</tr>
			     <?php $i=0; foreach($content as $row){ $i++;?>
				<tr>
				<td><a class='element' href="javascript:void()" onclick="if( $('#sub_table_<?php echo $i;?>').hasClass( 'hidden' ) ) $('#sub_table_<?php echo $i;?>').removeClass('hidden'); else $('#sub_table_<?php echo $i;?>').addClass('hidden');"><?php echo $row->ad_title;?></a>
				<table class="table table-striped hidden" id='sub_table_<?php echo $i?>' >
				    <tr><td>Completed</td><td>Partial</td><td>Replay</td></tr>
				    <tr></td><td><?php echo $row->complete;?></td><td><?php echo $row->partial;?></td><td><?php echo $row->replay;?></td></tr>
				</table>
				</td>
				<td><?php echo $row->total_hits;?></td>
				<td><?php echo time_from_seconds($row->total_watched_time);?></td>				
				</tr>
			    <?php }?>
                            </tbody></table><!-- /.table -->
                        </div>
		    </div><!-- /.box-body -->
		    <div><a href='<?php echo base_url()?>ads_analytics/content' style="float:right;">View All</a></div>
		</div><!-- /.box -->
		
		<!-- Box (with bar chart) -->
		<div class="box box-danger">
		    <div class="box-header">
			<!-- tools box -->
			<div class="pull-right box-tools">
			    <button class="btn btn-danger btn-sm" data-widget='collapse' data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
			</div><!-- /. tools -->
			<h3 class="box-title">User Wise Report</h3>
		    </div><!-- /.box-header -->
		    <div class="box-body no-padding">
			<div class="table-responsive">
                            <!-- .table - Uses sparkline charts-->
                            <table class="table table-striped">
				<tbody><tr>
				<th>User</th>
				<th>Total Hits</th>
				<th>Total Time Watched</th>				
				</tr>
			     <?php $i=0; foreach($customer as $row){ $i++;?>
				<tr>
				<td><?php echo ($row->name !=''? $row->name:'guest');?></td>
				<td><?php echo $row->total_hits;?></td>
				<td><?php echo time_from_seconds($row->total_watched_time);?></td>				
				</tr>
			    <?php }?>
                            </tbody></table><!-- /.table -->
                        </div>
		    </div><!-- /.box-body -->
		    <div><a href='<?php echo base_url()?>ads_analytics/user' style="float:right;">View All</a></div>
		</div><!-- /.box -->
	   <!-- </section> /.Left col 
	     <section class="col-lg-6"> -->
		<!-- Box (with bar chart) -->
                
		<div class="box box-danger">
		    <div class="box-header">
			<!-- tools box -->
			<div class="pull-right box-tools">
			    <button class="btn btn-danger btn-sm" data-widget='collapse' data-toggle="tooltipss" title="Collapse"><i class="fa fa-minus"></i></button>
			</div><!-- /. tools -->
			<h3 class="box-title">User Agent</h3>
		    </div><!-- /.box-header -->
		    <div class="box-body no-padding">
			
			   
				<div class="table-responsive">
                            <!-- .table - Uses sparkline charts-->
                            <!--table class="table table-striped">
				<tbody><tr>
				<th>OS</th>
				<th>Browser</th>
				<th>Hits</th>
				<th>Time Watched</th>				
				</tr>
			     <?php $i=0; foreach($useragent as $row){ $i++;?>
				<tr>
				<td><?php echo $row->platform;?></td>
				<td><?php echo $row->browser;?></td>
				<td><?php echo $row->total_hits;?></td>
				<td><?php echo time_from_seconds($row->total_watched_time);?></td>				
				</tr>
			    <?php }?>
                            </tbody></table--><!-- /.table -->
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <td><strong>Platform</strong></td>
                                        <td><strong>Browser</strong></td>
                                    </tr>
                                    <tr>
                                        <td><div id="os_chart" class="placeholder"></div></td>
                                        <td><div id="browser_chart" class="placeholder"></div></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>			    
		    </div><!-- /.box-body -->
		    <div><a href='<?php echo base_url()?>ads_analytics/device'' style="float:right;">View All</a></div>
		</div><!-- /.box -->
                
                
                
		<!--Content Provider -->
		<div class="box box-danger">
		    <div class="box-header">
			<!-- tools box -->
			<div class="pull-right box-tools">
			    <button class="btn btn-danger btn-sm" data-widget='collapse' data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
			</div><!-- /. tools -->
			<h3 class="box-title">Content Provider</h3>
		    </div><!-- /.box-header -->
		    <div class="box-body no-padding">
			
			   
				<div class="table-responsive">
                            <!-- .table - Uses sparkline charts-->
                            <table class="table table-striped">
				<tbody><tr>
				<th>Content Provider</th>				
				<th>Hits</th>
				<th>Time Watched</th>				
				</tr>
			     <?php $i=0; foreach($content_provider as $row){ $i++;?>
				<tr>
				<td><?php echo $row->name;?></td>				
				<td><?php echo $row->total_hits;?></td>
				<td><?php echo time_from_seconds($row->total_watched_time);?></td>				
				</tr>
			    <?php }?>
                            </tbody></table><!-- /.table -->
                        </div>
			    
			
		    </div><!-- /.box-body -->
		</div><!-- /.box -->
	    </section> <!--/.Left col -->
	   
	    
        <!--</div> /.row (main row) 
	
	<div class="row">-->
	    <section class="col-lg-6"> 
		<!-- Box (with bar chart) -->
		<div class="box box-danger">
		    <div class="box-header">
			<!-- tools box -->
			<div class="pull-right box-tools">
			    <button class="btn btn-danger btn-sm" data-widget='collapse' data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
			</div><!-- /. tools -->
			<h3 class="box-title">Map </h3>
		    </div><!-- /.box-header -->
		    
		    <div class="box-body no-padding">
			 <!--div id="regions_div" style="width: 500px;"></div-->
                         <div id="map_chart" class="map_placeholder"></div>
		</div>
			    
			
		    </div><!-- /.box-body -->
		
	    <!--</section> /.Left col 
	    <section class="col-lg-6">-->
		<!-- Box (with bar chart) -->
		<div class="box box-danger">
		    <div class="box-header">
			<!-- tools box -->
			<div class="pull-right box-tools">
			    <button class="btn btn-danger btn-sm" data-widget='collapse' data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
			</div><!-- /. tools -->
			<h3 class="box-title">Country </h3>
		    </div><!-- /.box-header -->
		    <div class="box-body no-padding">
			     <div class="table-responsive">
                                        <!-- .table - Uses sparkline charts-->
                                        <table class="table table-striped">
                                            <tr>
                                                <th>Location</th>
                                                <th>Hits</th>
                                                <th>Time Watched</th>                                                
                                            </tr>
					    <?php foreach($country as $row){?>
                                            <tr>
                                                <td><!--<a href="<?php echo base_url()?>ads_analytics/geographic?country=<?php echo $row->code;?>">--><?php echo $row->city;?></a></td>
                                                <td><?php echo $row->total_hits;?></td>
						<td><?php echo time_from_seconds($row->total_watched_time);?></td>
                                            </tr>
					    <?php }?>
                                            
                                        </table><!-- /.table -->
                                    </div>
			
		    </div><!-- /.box-body -->
		    <div><a href='<?php echo base_url()?>ads_analytics/geographic?c=1' style="float:right;">View All</a></div>
		</div><!-- /.box -->
		
		<div class="box box-primary">
                                <div class="box-header">
                                   <!-- tools box -->
                                    <div class="pull-right box-tools">
                                        <button class="btn btn-danger btn-sm" data-widget='collapse' data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                                    </div><!-- /. tools -->
                                    <h3 class="box-title">Top Ad viewed</h3>
                                </div>
                                <div class="box-body">                                    
				    <table id="example2" class="table table-bordered table-striped">                                    
                                    <thead>
				    <tr>
					<th>Sl.</th>
					<th>Title</th>
					<th>Hits</th>
					<th>Watched time</th>
				    </tr>				    
				</thead>
				    <tbody>
                                        <?php $i=0; foreach ($topcontent as $value) { $i++;?>
                                        <tr id="<?php echo $value->id ?>">
					<td><?php echo $i; ?></td>  
                                                <td  width="70%"><?php echo $value->ad_title; ?></td>                                               
                                                 <td><?php echo $value->total_hits; ?></td>
                                                <td><?php echo time_from_seconds($value->total_watched_time); ?></td>                                                                                        
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                                </div><!-- /.box-body-->
				<div><a href='<?php echo base_url()?>ads_analytics/top'' style="float:right;">View All</a></div>
                            </div><!-- /.box -->
	    </section><!-- /.Left col -->
	    
        </div><!-- /.row (main row) -->
	<?php /*
	    <section class="col-lg-12"> 
		<!-- Box (with bar chart) -->
		<div class="box box-danger">
		    <div class="box-header">
			<!-- tools box -->
			<div class="pull-right box-tools">
			    <button class="btn btn-danger btn-sm" data-widget='collapse' data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
			</div><!-- /. tools -->
			
			<div class="pull-right box-tools">
			    <select class="form-control onchange" id="month">
				<?php
				    foreach($months as $key=>$val){
					if($key == date('m'))
					echo sprintf('<option value="%s" selected="selected">%s</option>',$key,$val);
					else
					echo sprintf('<option value="%s">%s</option>',$key,$val);
				    }
				?>
			    </select>
			</div>
			
		    <h3 class="box-title">Daily Videos</h3>
		    </div><!-- /.box-header -->
		    <div class="box-body no-padding">
			<div class="row">
			    <div class="col-sm-12">
				<div class="chart" id="dailyvideo-chart-div" style="height: 250px;"></div>
			    </div>
			</div><!-- /.row - inside box -->
		    </div><!-- /.box-body -->
		</div><!-- /.box -->
	    </section><!-- /.Left col -->*/?>
	</div>
	
	<script>
	    //-- get contents by user id --//
	    function getContent(id) {
		//code
		$.ajax({
		    url: "<?php echo base_url();?>ads_analytics/ajax",
		    data: {
			user_id: id
		    }
		})
		.done(function(data){
		    var strhtml='';
		    if( $('#customer_'+id).hasClass( 'hidden' ) ){
			
		    }else{
			$('#customer_'+id).addClass('hidden');
			exit;
		    }
		    $.each($.parseJSON(data), function (name,obj ){			
		    var total_watched = second_to_time(obj.total_watched_time);
		        strhtml += '<tr class=tr_'+id+'><td>'+obj.ad_title+'</td><td>'+obj.total_hits+'</td><td>'+total_watched+'</td></tr>';			
		    });
		       $('.tr_'+id ).remove();
		       $('#customer_'+id).append(strhtml);
		       $('#customer_'+id).removeClass('hidden');
		});
	    }
	    function second_to_time(pos)
	    {
		var seconds = pos;
		var hours = parseInt( seconds / 3600 ); // 3,600 seconds in 1 hour
		seconds = seconds % 3600;
		
		 var minutes = parseInt( seconds / 60 ); // 60 seconds in 1 minute
		// 4- Keep only seconds not extracted to minutes:
		seconds = seconds % 60;
		return hours+":"+minutes+":"+seconds;
	    }
	    
	    
	</script>
	
	<script>
	    //-- map values ---//	    
	  /*  var visitorsData = {
		<?php //foreach($map as $country){?>
		"<?php //echo $country->code;?>": <?php echo $country->total_hits;?>, //Saudi Arabia
		<?php //}?>
		
	    };*/
	    
	</script>
		
	<script type='text/javascript' src='https://www.google.com/jsapi'></script>	
	 
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["geomap"]});
      //google.setOnLoadCallback(drawMap);

      function drawMap() {
        var data = google.visualization.arrayToDataTable([
          ['Country', 'Hits'],
	  <?php foreach($map as $country){
	    if($country->country != ''){
	    ?>
		['<?php echo $country->country;?>', <?php echo $country->total_hits;?>],
	<?php } }?>          
        ]);
        console.log(data);

        var options = {};
        options['dataMode'] = 'regions';
	options['width'] = '500px';
        var container = document.getElementById('regions_div');
        var geomap = new google.visualization.GeoMap(container);

        geomap.draw(data, options);
        google.visualization.events.addListener(geomap, 'regionClick', function(e) {
        var country = e['region'];	
	 window.location='<?php echo base_url();?>ads_analytics/geographic?country='+country;
	     
      });
      };
    </script>
	
	<script>
	    
	     //World map by jvectormap
	/*$('#world-map').vectorMap({	    
      map: 'world_mill_en',
	//map: 'us_aea_en',
	
        backgroundColor: "#fff",
        regionStyle: {
            initial: {
                fill: '#e4e4e4',
                "fill-opacity": 1,
                stroke: 'none',
                "stroke-width": 0,
                "stroke-opacity": 1
            }
        },
        series: {
            regions: [{
                    values: visitorsData,
                    scale: ["#3c8dbc", "#2D79A6"], //['#3E5E6B', '#A6BAC2'],
                    normalizeFunction: 'polynomial'
                }]
        },
        onRegionLabelShow: function(e, el, code) {
            if (typeof visitorsData[code] != "undefined")
                el.html(el.html() + ': ' + visitorsData[code] + ' hits');
        }
    });	    */
	</script>
        
        <script>
            
            $(function() {

                /*** PIE CHART FOR OS DATA ****/ 
                
		// OS Data
                var os_data = [
               <?php $i=1; foreach($os as $row){ $i++;?>
                {label: "<?php echo ($row->platform!='') ? $row->platform.'<br/>'.$row->total_hits : 'Anonymus'.'<br/>'.$row->total_hits;?>", data: "<?php echo $row->total_hits;?>"},
               <?php }?>
            ];

		var os_chart_placeholder = $("#os_chart");

		os_chart_placeholder.unbind();

		$.plot(os_chart_placeholder, os_data, {
			series: {
				pie: { 
					show: true,
					combine: {
						color: "#999",
						threshold: 0.05
					},
                                        label: {
                                                show: "auto",
                                                formatter: function(label, slice) {
                                                        return "<div style='font-size:small;text-align:center;padding:2px;color:" + slice.color + ";'>" + label + "</div>";
                                                },	// formatter function
                                                radius: 1,	// radius at which to place the labels (based on full calculated radius if <=1, or hard pixel value)
                                                threshold: 0	// percentage at which to hide the label (i.e. the slice is too narrow)
                                        }
				}
			},
			legend: {
				show: false
			}
		});
                
            
           /*** PIE CHART FOR BROWSER DATA ****/ 
           
           var browser_data = [
               <?php $i=1; foreach($browser as $row){ $i++;?>
                {label: "<?php echo ($row->browser!='') ? $row->browser.'<br/>'.$row->total_hits : 'Anonymus'.'<br/>'.$row->total_hits;?>", data: "<?php echo $row->total_hits;?>"},
               <?php }?>
            ];
            
            var browser_chart_placeholder = $("#browser_chart");

		browser_chart_placeholder.unbind();

		$.plot(browser_chart_placeholder, browser_data, {
			series: {
				pie: { 
					show: true,
					combine: {
						color: "#999",
						threshold: 0.05
					},
                                        label: {
                                                show: "auto",
                                                formatter: function(label, slice) {
                                                        return "<div style='font-size:small;text-align:center;padding:2px;color:" + slice.color + ";'>" + label + "</div>";
                                                },	// formatter function
                                                radius: 1,	// radius at which to place the labels (based on full calculated radius if <=1, or hard pixel value)
                                                threshold: 0	// percentage at which to hide the label (i.e. the slice is too narrow)
                                        }
				}
			},
			legend: {
				show: false
			}
		});
                
               /*** PIE CHART FOR MAP ****/ 
                
                var map_data = [
               <?php $i=1; foreach($city as $row){ $i++;?>
                {label: "<?php echo ($row->city!='') ? $row->city.'<br/>'.$row->total_hits : 'Unknown'.'<br/>'.$row->total_hits;?>", data: "<?php echo $row->total_hits;?>"},
               <?php }?>
            ];
            
            var map_chart_placeholder = $("#map_chart");

		map_chart_placeholder.unbind();

		$.plot(map_chart_placeholder, map_data, {
			series: {
				pie: { 
					show: true,
					combine: {
						color: "#999",
						threshold: 0.05
					},
                                        label: {
                                                show: "auto",
                                                formatter: function(label, slice) {
                                                        return "<div style='font-size:small;text-align:center;padding:2px;color:" + slice.color + ";'>" + label + "</div>";
                                                },	// formatter function
                                                radius: 1,	// radius at which to place the labels (based on full calculated radius if <=1, or hard pixel value)
                                                threshold: 0	// percentage at which to hide the label (i.e. the slice is too narrow)
                                        }
				}
			},
			legend: {
				show: false
			}
		});
                
		
	});
        function toggle_searching()
        {
           /* var form_search_method = "<?php echo $_POST['searchby']; ?>"
            if(form_search_method==''){
                var searching_method = $('#searchby').val();
            }else{
                var searching_method = form_search_method;
                 //$("#searchby").val(searching_method);
            } */
            var searching_method = $('#searchby').val();
            
           // alert(searching_method);
            if(searching_method=='today'){
                
                var myDate = new Date();
                var todayDate = myDate.getDate()+ '/' + (myDate.getMonth()+1) + '/' + myDate.getFullYear();
                $("#datepickerstart").val(todayDate);
                $('#datepickerstart').attr('readonly', true);
                $('#datepickerstart').attr('disabled', 'disabled');
                
                $("#datepickerend").val(todayDate);
                $('#datepickerend').attr('readonly', true);
                $('#datepickerend').attr('disabled', 'disabled');
            }else if(searching_method=='date'){
                
                $("#datepickerstart").val("<?php echo $_POST['datepickerstart']; ?>");
                $('#datepickerstart').removeAttr('readonly');
                $('#datepickerstart').removeAttr('disabled');
                
                $("#datepickerend").val("<?php echo $_POST['datepickerend']; ?>");
                $('#datepickerend').removeAttr('readonly');
                $('#datepickerend').removeAttr('disabled');
                
            }
        }
        toggle_searching();
            
        </script>


    </section><!-- /.content -->
</aside><!-- /.right-side -->