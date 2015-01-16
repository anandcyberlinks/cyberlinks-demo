<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
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
                        $("#example2").append("<tr id='"+foo.last_insert_id+"'> \n\
                                        <td>"+foo.formatted_address+"</td> \n\
                                        <td>"+foo.latitude+"</td>\n\
                                         <td>"+foo.longitude+"</td>\n\
                                        <td width='150'>&nbsp; \n\
                            "+del+"\n\
                            </td> </tr>"
                        )
                });
              
              marker = createMarker(event.latLng, "name", "<b>Location</b><br>"+json_data_address['results'][0]['formatted_address']);
            
            }
          });
         
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
                         <div id="map_canvas" style="width: 950px; height: 450px"></div> 
                      </td> 
                    </tr> 
                </table>
                
            </div><!-- box box-solid -->
        </div><!-- col-md-12 -->
    </div><!-- row -->
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