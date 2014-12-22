 <script type='text/javascript' src='https://www.google.com/jsapi'></script>   
  <script type='text/javascript'>
     google.load('visualization', '1', {'packages': ['geochart']});
     google.setOnLoadCallback(drawMarkersMap);

      function drawMarkersMap() {
      var data = google.visualization.arrayToDataTable([
       ['Region', 'Hits'],
       <?php foreach($geomap as $row){
        if($row->state !=''){
        ?>
        ['<?php echo $row->state;?>', <?php echo $row->total_hits;?>],
        <?php }}?>       
      ]);

      var options = {
       width: '200px',
        region: '<?php echo $country_code;?>',
        displayMode: 'markers',
        colorAxis: {
         colors: ['green', 'blue'],
        }
      };

      var chart = new google.visualization.GeoChart(document.getElementById('map_canvas'));
      chart.draw(data, options);
    };
    </script>
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
            <li class="active"><a href="<?php echo base_url();?>analytics/geographic?c=1"><?php echo $welcome->loadPo('Location') ?></a></li>
            <li class="active"><?php echo $country_name[0]->country; ?></li>
        </ol>
    </section>
	<?php $search = $this->session->userdata('search_form');?>
    <!-- Main content -->
    <section class="content">
     
     <form  method="post" action="" id="searchIndexForm" name="searchIndexForm" accept-charset="utf-8">
	    <div class="row">
		 <div class="box-body">
		<!-- form start -->                
                <div class="form-group col-lg-4">
		    <div class="input text">
		    <label for="url"><?php echo $welcome->loadPo('Start Date') ?></label>
		    <input type="text" class="form-control datepicker"  id="startdate" name="startdate" placeholder="<?php echo $welcome->loadPo('Start Date') ?>" value="<?php echo (isset($search['startdate'])) ? $search['startdate'] : ''; ?>" >											
		    </div>
                </div>
                <div class="form-group col-lg-4">
                    <div class="input text">
                    <label for="url"><?php echo $welcome->loadPo('End Date') ?></label>
                    <input type="text" class="form-control datepicker"  id="enddate" name="enddate" placeholder="<?php echo $welcome->loadPo('End Date') ?>" value="<?php echo (isset($search['enddate'])) ? $search['enddate'] : ''; ?>">
                    </div>
                </div>
		 
            </div>
	 </div>
	<div class="row">
		 <div class="form-group col-lg-4">
		    <div class="input text">		   
		    <button type="submit" name="search" value="Search"class="btn btn-primary"><?php echo $welcome->loadPo('Search') ?></button>
                    <button type="submit" name="reset" value="Reset"class="btn btn-primary"><?php echo $welcome->loadPo('Reset') ?></button>
		    </div>
                </div>
	</div>
	</form>
       
	<div class="row">
         <div class="col-lg-8">
            Export
        <a target='_blank' href='<?php echo base_url()?>analytics/export/r/pdf/<?php echo $sort_i;?>/<?php echo $sort_by;?>/type/region/country/<?php echo $country_code;?>'  title='pdf'><i class="fa fa-fw fa-file-text-o"></i></a>
        <a target='_blank' href='<?php echo base_url()?>analytics/export/r/csv/<?php echo $sort_i;?>/<?php echo $sort_by;?>/type/region/country/<?php echo $country_code;?>' title='csv'><i class="fa fa-fw fa-list-alt"></i></a>
        </div>  
	    <section class="col-lg-8"> 
		<!-- Box (with bar chart) -->
		<div class="box box-danger">
		    <div class="box-header">
			<!-- tools box -->
			<div class="pull-right box-tools">
			    <button class="btn btn-danger btn-sm" data-widget='collapse' data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
			</div><!-- /. tools -->						
		    <h3 class="box-title">Location <?php echo $country_name[0]->country; ?></h3>
		    </div><!-- /.box-header -->
		    <div class="box-body no-padding">
			<div class="row">
			    <div class="col-sm-12">
				<div id='map_canvas' style='width:550px;'></div>
			    </div>
			</div><!-- /.row - inside box -->
		    </div><!-- /.box-body -->
		</div><!-- /.box -->
                 
                <div class="box box-primary">
                                <div class="box-header">
                                    
                                    <h3 class="box-title">Region</h3>
                                </div>
                                <div class="table-responsive">
                                        <!-- .table - Uses sparkline charts-->
                                        <table class="table table-striped">
                                            <tr>
                                                <th>Region</th>
                                                <th>Hits</th>
                                                <th>Time Watched</th>                                                
                                            </tr>
					    <?php foreach($geomap as $row){
                                             if($row->state !=''){
                                             ?>
                                            <tr>
                                                <td><?php echo $row->state;?></td>
                                                <td><?php echo $row->total_hits;?></td>
						<td><?php echo time_from_seconds($row->total_watched_time);?></td>
                                            </tr>
					    <?php } }?>
                                            
                                        </table><!-- /.table -->
                                    </div>
                            </div><!-- /.box -->
	    </section><!-- /.Left col -->
                   
            <section class="col-lg-3"> 
                           <div class="box box-danger">
                                <div class="box-header">
                                    
                                    <h3 class="box-title">Country</h3>
                                </div>
                                <div class="table-responsive">
                                        <!-- .table - Uses sparkline charts-->
                                        <table class="table table-striped">
                                            <tr>
                                                <th>Country</th>                                                                                          
                                            </tr>
					    <?php foreach($country as $row){
                                             if($row->country !=''){
                                             ?>
                                            <tr>
                                                <td><a href="<?php echo base_url();?>analytics/geographic?country=<?php echo $row->code;?>"><?php echo $row->country;?></a></td>
                                            </tr>
					    <?php } }?>
                                            
                                        </table><!-- /.table -->
                                    </div>
                            </div><!-- /.box --> 
            </section>
         </div>
    </section>
  
 <script>

  $(function(){
     $( ".datepicker" ).datepicker({
  dateFormat: 'dd-mm-yy',
  numberOfMonths: 1,
});
  });
 
  </script>