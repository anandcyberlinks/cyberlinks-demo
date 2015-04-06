 <script type='text/javascript' src='https://www.google.com/jsapi'></script>
   <script type="text/javascript">
      google.load("visualization", "1", {packages:["geomap"]});
      google.setOnLoadCallback(drawMap);

      function drawMap() {
        var data = google.visualization.arrayToDataTable([
          ['Country', 'Hits'],
	  <?php foreach($geomap as $country){
	    //if($country->country != ''){
	    ?>
		['<?php echo ($country->city!='') ? $country->city : 'Unknown';?>', <?php echo $country->total_hits;?>],
	<?php  //}
		  }?>          
        ]);

        var options = {};
        options['dataMode'] = 'regions';
	options['width'] = '500px';
        var container = document.getElementById('map_canvas');
        var geomap = new google.visualization.GeoMap(container);

        geomap.draw(data, options);
	
	 google.visualization.events.addListener(geomap, 'regionClick', function(e) {
        var country = e['region'];	
	 window.location='<?php echo base_url();?>analytics/geographic?country='+country;
	     
      });
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
            <li><a href="<?php echo base_url()?>analytics/report"><?php echo $welcome->loadPo('Analytics') ?></a></li>
            <li class="active"><?php echo $welcome->loadPo('Location') ?></li>          
        </ol>
    </section>
	<?php $search = $this->session->userdata('search_form');?>
    <!-- Main content -->
    <section class="content">
     <div class="box box-primary collapsed-box">
            <div class="box-header">
                <!-- tools box -->
                <div class="pull-right box-tools">
                    <button class="btn btn-danger btn-sm" data-widget='collapse' data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                </div><!-- /. tools -->
                <h3 class="box-title">Search</h3>
            </div>
     <form  method="post" action="" id="searchIndexForm" name="searchIndexForm" accept-charset="utf-8">
	    <div class="row">
		 <div class="box-body" style="display:none;">
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
	
		 <div class="box-footer" style="display:none;">
		    <div class="input text">		   
                        <button type="submit" id="submit" name="search" value="Search"class="btn btn-primary"><?php echo $welcome->loadPo('Search') ?></button>
                        <button type="submit" name="reset" value="Reset"class="btn btn-primary"><?php echo $welcome->loadPo('Reset') ?></button>
                        <span id="error_msg" style="color: red"></span>
                    </div>
                </div>
	
	</form>
     </div>
        <script>
                $("#submit").click(function(data){
                    var startdate = $.trim($("#startdate").val());
                    var enddate = $.trim($("#enddate").val());
                    if(
                            startdate === "" 
                            && enddate === ""
                            ){
                        $("#error_msg").html('One on the above is required');
                        return false;
                    }
                });
                </script>
       
	<div class="row">
         <div class="col-lg-8">
            Export
        <a target='_blank' href='<?php echo base_url()?>analytics/export/r/pdf/<?php echo $sort_i;?>/<?php echo $sort_by;?>/type/country'  title='pdf'><i class="fa fa-fw fa-file-text-o"></i></a>
        <a target='_blank' href='<?php echo base_url()?>analytics/export/r/csv/<?php echo $sort_i;?>/<?php echo $sort_by;?>/type/country' title='csv'><i class="fa fa-fw fa-list-alt"></i></a>
        </div>  
	    <section class="col-lg-8"> 
		<!-- Box (with bar chart) -->
		<div class="box box-danger">
		    <div class="box-header">
			<!-- tools box -->
			<div class="pull-right box-tools">
			    <button class="btn btn-danger btn-sm" data-widget='collapse' data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
			</div><!-- /. tools -->								  
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
                                    
                                    <h3 class="box-title">Country</h3>
                                </div>
                                <div class="table-responsive">
                                        <!-- .table - Uses sparkline charts-->
                                        <table class="table table-striped">
                                            <tr>
                                                <th>Location</th>
												<th>Country</th>
                                                <th>Hits</th>
                                                <th>Time Watched</th>                                                
                                            </tr>
					    <?php foreach($geomap as $row){
                                             //if($row->country !=''){
                                             ?>
                                            <tr>
                                                <td><a href="<?php echo base_url();?>analytics/geographic?country=<?php echo $row->code;?>"><?php echo ($row->city!='') ? $row->city : 'Unknown';?></a></td>
                                                <td><?php echo $row->country;?></td>
												<td><?php echo $row->total_hits;?></td>
						<td><?php echo time_from_seconds($row->total_watched_time);?></td>
                                            </tr>
					    <?php  }?>
                                            
                                        </table><!-- /.table -->
                                        <!-- Pagination start --->
                                        <?php
                                        if ($this->pagination->total_rows == '0') {
                                            echo "<tr><td colspan=\"7\"><h4>" . $welcome->loadPo('No Record Found') . "</td></tr></h4>";
                                        } else {
                                            ?>
                                            </table>

                                            <div class="row pull-left">
                                                <div class="dataTables_info" id="example2_info"><br>
                                                    <?php
                                                    $param = $this->pagination->cur_page * $this->pagination->per_page;
                                                    if ($param > $this->pagination->total_rows) {
                                                        $param = $this->pagination->total_rows;
                                                    }
                                                    if ($this->pagination->cur_page == '0') {
                                                        $param = $this->pagination->total_rows;
                                                    }
                                                    $off = $this->pagination->cur_page;
                                                    if ($this->pagination->cur_page > '1') {
                                                        $off = (($this->pagination->cur_page * '10') - 9);
                                                    }
                                                    echo "&nbsp;&nbsp;Showing <b>" . $off . "-" . $param . "</b> of <b>" . $this->pagination->total_rows . "</b> total results";
                                                }
                                                ?>
                                            </div>
                                        </div>	
                                        <div class="row pull-right">
                                            <div class="col-xs-12">
                                                <div class="dataTables_paginate paging_bootstrap">
                                                    <ul class="pagination"><li><?php echo $welcome->loadPo($links); ?></li></ul> 
                                                </div>
                                            </div>
                                        </div>
                                    </div>		
                                    <!-- Pagination end -->
                                    </div>
                            </div><!-- /.box -->
	    </section><!-- /.Left col -->
                   
            
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