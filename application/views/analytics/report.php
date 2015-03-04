<link href="<?=base_url()?>assets/css/morris/morris.css" rel="stylesheet" type="text/css" />
<aside class="right-side">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> 
            <?php echo $welcome->loadPo('Analytics'); ?>
            <small><?php echo $welcome->loadPo('Control panel'); ?></small>
        </h1>
        <ol class="breadcrumb">
            <i class="fa fa-dashboard"></i>
            <li class="active"><?php echo $welcome->loadPo('Analytics') ?></li>
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
                            <?php echo $summary->total_partial;?>
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
                           <?php echo $summary->total_complete;?>
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
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3>
                            <?php echo $summary->total_replay;?>
                        </h3>
                        <p>
                            <?php echo $welcome->loadPo('Replay Video'); ?>
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
	
        <div class="row">
	    <section class="col-lg-6"> 
		<!-- Box (with bar chart) -->
		<div class="box box-danger">
		    <div class="box-header">
			<!-- tools box -->
			<div class="pull-right box-tools">
			    <button class="btn btn-danger btn-sm" data-widget='collapse' data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
			</div><!-- /. tools -->
			<h3 class="box-title"><a href=''>Content Wise Report</a></h3>
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
				<td><a class='element' href="javascript:void()" onclick="if( $('#sub_table_<?php echo $i;?>').hasClass( 'hidden' ) ) $('#sub_table_<?php echo $i;?>').removeClass('hidden'); else $('#sub_table_<?php echo $i;?>').addClass('hidden');"><?php echo $row->title;?></a>
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
		    <div><a href='<?php echo base_url()?>analytics/content' style="float:right;">View All</a></div>
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
		    <div><a href='<?php echo base_url()?>analytics/user' style="float:right;">View All</a></div>
		</div><!-- /.box -->
	   <!-- </section> /.Left col 
	     <section class="col-lg-6"> -->
		<!-- Box (with bar chart) -->
		<div class="box box-danger">
		    <div class="box-header">
			<!-- tools box -->
			<div class="pull-right box-tools">
			    <button class="btn btn-danger btn-sm" data-widget='collapse' data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
			</div><!-- /. tools -->
			<h3 class="box-title">User Agent</h3>
		    </div><!-- /.box-header -->
		    <div class="box-body no-padding">
			
			   
				<div class="table-responsive">
                            <!-- .table - Uses sparkline charts-->
                            <table class="table table-striped">
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
                            </tbody></table><!-- /.table -->
                        </div>			    
		    </div><!-- /.box-body -->
		    <div><a href='<?php echo base_url()?>analytics/device'' style="float:right;">View All</a></div>
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
			 <div id="regions_div" style="width: 500px;"></div>
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
                                                <td><!--<a href="<?php echo base_url()?>analytics/geographic?country=<?php echo $row->code;?>">--><?php echo ($row->city!='') ? $row->city : 'Unknown';?></a></td>
                                                <td><?php echo $row->total_hits;?></td>
						<td><?php echo time_from_seconds($row->total_watched_time);?></td>
                                            </tr>
					    <?php }?>
                                            
                                        </table><!-- /.table -->
                                    </div>
			
		    </div><!-- /.box-body -->
		    <div><a href='<?php echo base_url()?>analytics/geographic?c=1' style="float:right;">View All</a></div>
		</div><!-- /.box -->
		
		<div class="box box-primary">
                                <div class="box-header">
                                   
                                    <h3 class="box-title">Top video viewed</h3>
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
                                                <td  width="70%"><?php echo $value->title; ?></td>                                               
                                                 <td><?php echo $value->total_hits; ?></td>
                                                <td><?php echo time_from_seconds($value->total_watched_time); ?></td>                                                                                        
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                                </div><!-- /.box-body-->
				<div><a href='<?php echo base_url()?>analytics/top'' style="float:right;">View All</a></div>
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
		    url: "<?php echo base_url();?>analytics/ajax",
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
		        strhtml += '<tr class=tr_'+id+'><td>'+obj.title+'</td><td>'+obj.total_hits+'</td><td>'+total_watched+'</td></tr>';			
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
      google.setOnLoadCallback(drawMap);

      function drawMap() {
        var data = google.visualization.arrayToDataTable([
          ['Country', 'Hits'],
	  <?php foreach($map as $country){
	    if($country->country != ''){
	    ?>
		['<?php echo $country->country;?>', <?php echo $country->total_hits;?>],
	<?php } }?>          
        ]);

        var options = {};
        options['dataMode'] = 'regions';
	options['width'] = '500px';
        var container = document.getElementById('regions_div');
        var geomap = new google.visualization.GeoMap(container);

        geomap.draw(data, options);
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

    </section><!-- /.content -->
</aside><!-- /.right-side -->