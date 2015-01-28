<style>
      .controls {
        margin-top: 16px;
        border: 1px solid transparent;
        border-radius: 2px 0 0 2px;
        box-sizing: border-box;
        -moz-box-sizing: border-box;
        height: 32px;
        outline: none;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
      }

      #pac-input {
        background-color: #fff;
        padding: 0 11px 0 13px;
        width: 400px;
        font-family: Roboto;
        font-size: 15px;
        font-weight: 300;
        text-overflow: ellipsis;
      }

      #pac-input:focus {
        border-color: #4d90fe;
        margin-left: -1px;
        padding-left: 14px;  /* Regular padding-left + 1. */
        width: 401px;
      }

      .pac-container {
        font-family: Roboto;
      }

      #type-selector {
        color: #fff;
        background-color: #4d90fe;
        padding: 5px 11px 0px 11px;
      }

      #type-selector label {
        font-family: Roboto;
        font-size: 13px;
        font-weight: 300;
      }

</style>
<!--script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script-->
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places"></script>


<link href="<?php echo base_url() ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url() ?>assets/css/bootstrap-dialog.min.css" rel="stylesheet" type="text/css" />




<script type="text/javascript"> 
//<![CDATA[

     // global "map" variable
      var map = null;
      var marker = null;
      var json_data_address = null;

var infowindow = new google.maps.InfoWindow(
  { 
    size: new google.maps.Size(150,50)
  });

// A function to create the marker and set up the event window function 
function createMarker(latlng, name, html) {
    var contentString = html;
    var marker = new google.maps.Marker({
        position: latlng,
        map: map,
        zIndex: Math.round(latlng.lat()*-100000)<<5
        });

    google.maps.event.addListener(marker, 'click', function() {
        infowindow.setContent(contentString); 
        infowindow.open(map,marker);
        });
    google.maps.event.trigger(marker, 'click');    
    return marker;
}

 

function initialize() {
    var search_markers = [];
  // create the map
  var myOptions = {
    zoom: 4,
    center: new google.maps.LatLng(21.0000,78.0000),
    mapTypeControl: true,
    mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DROPDOWN_MENU},
    navigationControl: true,
    mapTypeId: google.maps.MapTypeId.ROADMAP
  }
  map = new google.maps.Map(document.getElementById("map_canvas"),
                                myOptions);
   var locations = [];
   <?php foreach ($this->data['adsLocationInfo'] as $key => $item) { ?>
    locations.push(["<?php echo $item->formatted_address; ?>", "<?php echo $item->latitude; ?>", "<?php echo $item->longitude; ?>"]);
   <?php } ?>
       
     /*var beaches = [
        ['Title1', 18.729501999072138, 79.013671875, 4],
        ['Title2', 17.727758609852284, 78.22265625, 5],
        ['Title3', 27.9361805667694, 73.212890625, 3],
        ['Title4', 30.315987718557867, 76.9921875, 2],
        ['Title5', 27.137368359795584, 80.13427734375, 1]
      ];*/
      
      for (var i = 0; i < locations.length; i++) {
        var beach = locations[i];
        var myLatLng = new google.maps.LatLng(beach[1], beach[2]);
        /*var marker = new google.maps.Marker({
            position: myLatLng,
            map: map,
            title: beach[0],
            zIndex: beach[3]
        });*/
        marker = createMarker(myLatLng, "name", "<b>Location</b><br>"+beach[0]);
        infowindow.close();
      }
 
  google.maps.event.addListener(map, 'click', function() {
        infowindow.close();
        });

  google.maps.event.addListener(map, 'click', function(event) {
	//call function to create marker
         /*if (marker) {
            marker.setMap(null);
            marker = null;
         }*/
	 
         var datan  = event.latLng;
         
         var the_url = "http://maps.googleapis.com/maps/api/geocode/json?latlng="+datan.k+","+datan.D+"&sensor=true";
          
         BootstrapDialog.show({
            title: 'Confirmation',
            message: 'Are you sure you want to save this location?',
            buttons: [{
                label: 'Yes',
                cssClass: 'btn-primary',
                action: function(dialogItself){
                    
                    $.ajax({ 
                    type: 'GET', 
                    url: the_url, 
                    data: { get_param: 'value' }, 
                    dataType: 'json',
                    success: function (data) { 
                        json_data_address = data;

                        $.post( "<?php echo base_url(); ?>ads/save_location_data", {ads_id: "<?php echo $this->data['ads_id']; ?>", latitude: datan.k,longitude: datan.D,json_data_address: json_data_address},function( data ) {
                                var foo = JSON.parse(data);
                                //console.log(foo.ads_id)

                                var return_url = " '"+"<?php echo base_url() . 'ads/deleteAdsLocation' ?>"+"' ";
                                var current_url = " '"+"<?php echo current_full_url(); ?>"+"' ";
                                var button = '<button class="btn btn-danger btn-sm" data-toggle="modal" data-target=".bs-example-modal-sm" >';
                                var onClick =  'onclick="return delete_adsLocation('+foo.last_insert_id+', '+return_url+', '+current_url+')";'
                                var Cclass = 'class="confirm"';
                                var del = '<a '+Cclass+' '+onClick+'  href="" >'+button+'Delete</button></a>';
                                $("#example2").prepend("<tr id='"+foo.last_insert_id+"'> \n\
                                                <td>"+foo.formatted_address+"</td> \n\
                                                <td>"+foo.latitude+"</td>\n\
                                                 <td>"+foo.longitude+"</td>\n\
                                                <td width='150'>&nbsp; \n\
                                    "+del+"\n\
                                    </td> </tr>"
                                )
                                 //location.hash = "#"+foo.last_insert_id;
                                 $(document.body).animate({
                                    'scrollDown':   $('#'+foo.last_insert_id).offset().top
                                }, 2000);
                        });

                      marker = createMarker(event.latLng, "name", "<b>Location</b><br>"+json_data_address['results'][0]['formatted_address']);

                    }
                  });                       
                 dialogItself.close();
                    
                }
            }, {
                label: 'No',
                action: function(dialogItself){
                    dialogItself.close();
                }
            }]
        }); 
         
  });
  
  
  // Create the search box and link it to the UI element.
    var input = /** @type {HTMLInputElement} */(
        document.getElementById('pac-input'));
    map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
  
    var searchBox = new google.maps.places.SearchBox(
      /** @type {HTMLInputElement} */(input));
  
    // [START region_getplaces]
    // Listen for the event fired when the user selects an item from the
    // pick list. Retrieve the matching places for that item.
    google.maps.event.addListener(searchBox, 'places_changed', function() {
      var places = searchBox.getPlaces();
  
      if (places.length == 0) {
        return;
      }
      for (var i = 0, marker; marker = search_markers[i]; i++) {
        marker.setMap(null);
      }
  
      // For each place, get the icon, place name, and location.
      search_markers = [];
      var bounds = new google.maps.LatLngBounds();
      for (var i = 0, place; place = places[i]; i++) {
        var image = {
          url: place.icon,
          size: new google.maps.Size(71, 71),
          origin: new google.maps.Point(0, 0),
          anchor: new google.maps.Point(17, 34),
          scaledSize: new google.maps.Size(25, 25)
        };
  
        // Create a marker for each place.
        var marker = new google.maps.Marker({
          map: map,
          icon: image,
          title: place.name,
          position: place.geometry.location
        });
  
        search_markers.push(marker);
  
        bounds.extend(place.geometry.location);
      }
  
      map.fitBounds(bounds);
    });
    // [END region_getplaces]
  
    // Bias the SearchBox results towards places that are within the bounds of the
    // current map's viewport.
    google.maps.event.addListener(map, 'bounds_changed', function() {
      var bounds = map.getBounds();
      searchBox.setBounds(bounds);
    });
  

}

function isInteger(x) {
        return x % 1 === 0;
 }
 
 
    

//]]>
google.maps.event.addDomListener(window, 'load', initialize);
</script> 


<div id="tab_4" class="tab-pane active">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-solid">				
                <table border="1"> 
                    <tr> 
                      <td>
                         <input id="pac-input" class="controls" type="text" placeholder="Search Box">
                         <div id="map_canvas" style="width: 950px; height: 450px"></div> 
                      </td> 
                    </tr> 
                </table>
                
            </div><!-- box box-solid -->
        </div><!-- col-md-12 -->
    </div><!-- row -->
    <?php $search = $this->session->userdata('search_form'); ?>
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="box box-primary">
                <!-- form start -->
                <form  method="post" action="<?php echo base_url().'ads/videoOpr/Location?action='.$this->session->userdata('location_vid'); ?>" id="searchIndexForm" name="searchIndexForm" accept-charset="utf-8">
                    <div style="display:none;"><input type="hidden" name="_method" value="POST"/></div>
                    <div class="box-body">
                        <div class="row">
                            <div class="form-group col-lg-4">
                                <div class="input text">
                                    <label for=""><?php echo $welcome->loadPo('Search') ?></label>
                                    <input type="text" name="formatted_address" id="formatted_address" class="form-control" value="<?php echo (isset($search['formatted_address'])) ? $search['formatted_address'] : ''; ?>" placeholder="<?php echo $welcome->loadPo('Address') ?>">
                                </div>
                            </div>
                            
                        </div>
                    </div><!-- /.box-body -->
                    <div class="box-footer">
                            <!--	<input type="text" id="hddstarddt" name="hddstarddt" value="<?php echo @$_POST['hddstarddt'] ?>"> -->
                        <button type="submit" name="submit" value="Search"class="btn btn-primary"><?php echo $welcome->loadPo('Search') ?></button>
                    <button type="submit" name="reset" value="Reset"class="btn btn-primary"><?php echo $welcome->loadPo('Reset') ?></button>
                    </div>
                </form>
            </div><!-- /.box -->
        </div><!--/.col (left) -->
    </div>
    
    <div class="row">
                    <div class="col-xs-12">
                        <div class="box">
                            <div class="box-body table-responsive">
                                <table id="example2" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th><?php echo $welcome->loadPo('Address') ?></th>
                                            <th><?php echo $welcome->loadPo('Latitude') ?></th>
                                            <th><?php echo $welcome->loadPo('Longitude') ?></th>
                                            <th align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $welcome->loadPo('Action') ?></th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php foreach ($this->data['adsLocationInfo'] as $key => $value) { ?>
                                        <tr id="<?php echo $value->id ?>">
                                                <td  width="450"><?php echo $value->formatted_address; ?></td>
                                                <td><?php echo $value->latitude; ?></td>
                                                <td><?php echo $value->longitude; ?></td>
                                                <td  width="150">
                                                    &nbsp;
                                                    <a class="confirm" onclick="return delete_adsLocation(<?php echo $value->id; ?>, '<?php echo base_url() . 'ads/deleteAdsLocation' ?>', '<?php echo current_full_url(); ?>');" href="" ><button class="btn btn-danger btn-sm" data-toggle="modal" data-target=".bs-example-modal-sm" ><?php echo $welcome->loadPo('Delete') ?></button></a>                            </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>


                                </table>
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
                            <!-- Pagination end -->
                        </div><!-- /.box-body -->
                    </div><!-- /.box -->
                </div>
</div><!-- /.tab-4 -->
</div><!-- /.tab-content -->
</div><!-- nav-tabs-custom -->
</div><!-- /.col -->
</div> <!-- /.row -->
</div>
</section>
</aside>
</div>