<?php include('header.php');

include "db/config.php";

$startDate = date('Y-m-d').' 00:00:00';
$endDate =  date('Y-m-d').' 23:59:59';
$currentTime = date('Y-m-d H:i:s');
$InactiveTime = date("Y-m-d H:i:s",strtotime('-30 minutes',strtotime($currentTime)));
 ?>

<div id="content" class="app-content" role="main">
  <div class="app-content-body ">
    <div class="hbox hbox-auto-xs hbox-auto-sm" ng-init="

    app.settings.asideFolded = false; 

    app.settings.asideDock = false;

  "> 
      
      <!-- main -->
      
      <div class="col">
        <div class="wrapper-md" ng-controller="FlotChartDemoCtrl"> 
          
          <!-- stats -->
          
          <div class="row">
            
            <div class="col-md-12">
              <div class="panel wrapper">
                <label class="i-switch bg-warning pull-right" ng-init="showSpline=true">
                  <input type="checkbox" ng-model="showSpline">
                  <i></i> </label>
                <h2 class="">Google Map</h2>
              
                
                <!--<h4 class="font-thin m-t-none m-b text-muted">Google Map</h4>-->
                
                <div class="card card-map">
                  
                  <div class="map">  <div id="dvMap" style="width: 100%; height: 300px">
                  
                  </div>
                </div>
              </div>
            </div>
          </div>
          
        
          
          
        </div>
      </div>
      
      <!-- / main --> 
      
      <!-- right col -->
      
      
    </div>
 
  
  <!-- /content -->
  <div class="col w-md bg-white-only b-l bg-auto no-border-xs">
        <?php 
 $RecentBookingQ = "SELECT mst_surveyor.`SURVEYOR_NAME`,mst_surveyor.`SURVEYORID`,trn_surveylive.`CREATED_BYID`, trn_surveylive.`SERVEY_DATE`,trn_surveylive.`FIRST_NAME` ,trn_surveylive.`ADDRESS` ,trn_surveylive.`SOCITY` ,trn_surveylive.`CITY`,trn_surveylive.`LATI`,trn_surveylive.`LONGI` ,trn_surveylive.`CREATED_BYRID` ,trn_surveylive.`SURVEY_ID` FROM  mst_surveyor  left JOIN  `trn_surveylive` ON trn_surveylive.CREATED_BYID=mst_surveyor.SURVEYORID WHERE trn_surveylive.`CREATED_BYRID` ='5' AND trn_surveylive.`CREATED_BYID`='".$_REQUEST['SurveryId']."' ORDER BY trn_surveylive.`SURVEY_ID` DESC";

//echo $RecentBookingQ;die;
  
   

   ?>
        <div class="padder-md"> 
          
          <!-- streamline --><br>
          <div class="m-b text-md">Recent Survey</div>
          <div class="streamline b-l m-b">
            <?php $RecentBookingQu = mysqli_query($connection, $RecentBookingQ);
			      while($RecentBookingData = mysqli_fetch_assoc($RecentBookingQu)){ 
				 // print_r($RecentBookingData);die;
				  if(!empty($RecentBookingData['SERVEY_DATE'])){
				  ?>
            <div class="sl-item">
              <div class="m-l">
                <div class="text-muted"><?php echo $RecentBookingData['SERVEY_DATE']?></div>
                <p><?php echo $RecentBookingData['FIRST_NAME']?>, <?php echo $RecentBookingData['SOCITY'].','.$RecentBookingData['CITY']?>.</p>
              </div>
            </div>
            <?php } } ?>
          </div>
        </div>
        
        <!-- / right col --> 
        
      </div>
      </div></div> </div>
  <?php include('footer.php'); ?>
  

<?php 
//echo "<pre>";
 $startDate = date('Y-m-d').' 00:00:00';
 $endDate =  date('Y-m-d').' 23:59:59';
 $MapQuery = "SELECT mst_surveyor.`SURVEYOR_NAME`,mst_surveyor.`SURVEYORID`,trn_surveylive.`CREATED_BYID`, trn_surveylive.`SERVEY_DATE`,trn_surveylive.`ADDRESS` ,trn_surveylive.`LATI`,trn_surveylive.`LONGI`  FROM  trn_surveylive  left JOIN  `mst_surveyor` ON mst_surveyor.SURVEYORID=trn_surveylive.CREATED_BYID
	                     WHERE trn_surveylive.`CREATED_BYRID` ='5' AND trn_surveylive.`CREATED_BYID`='".$_REQUEST['SurveryId']."'
                         ORDER BY trn_surveylive.`SURVEY_ID` DESC";
						 
			
						
							  
//echo $MapQuery;							   
							 
?>
 <!--  Google Maps Plugin   -->
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCajg2gehjcPV6DEmPRoKOtoXo4y4QOdY0&angular.noop=initMap"></script>

<script type="text/javascript">
        var markers = [
		<?php  $MapData12 = mysqli_query($connection, $MapQuery);
			    while($MapData = mysqli_fetch_assoc($MapData12))
				 { if(!empty($MapData['SERVEY_DATE'])){ 
				  if(!empty($MapData['LATI']) && $MapData['LATI']!='0.0' && !empty($MapData['LONGI']) && $MapData['LONGI']!='0.0'){
				  ?>
            {
                "title": '<?php echo $MapData['ADDRESS']?> ' + '<?php echo $MapData['SERVEY_DATE']?>',
                "lat": '<?php echo $MapData['LATI']?>',
                "lng": '<?php echo $MapData['LONGI']?>',
                "description": 'Alibaug is a coastal town and a municipal council in Raigad District in the Konkan region of Maharashtra, India.'
            }
        ,
            <?php } }}?>
];

        window.onload = function () {
            var mapOptions = {
                center: new google.maps.LatLng(markers[0].lat, markers[0].lng),
                zoom: 10,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };
            var map = new google.maps.Map(document.getElementById("dvMap"), mapOptions);
            var infoWindow = new google.maps.InfoWindow();
            var lat_lng = new Array();
            var latlngbounds = new google.maps.LatLngBounds();
            var j = 1;
            for (i = 0; i < markers.length; i++) {
                
                var data = markers[i]
                var myLatlng = new google.maps.LatLng(data.lat, data.lng);
                lat_lng.push(myLatlng);
                var marker = new google.maps.Marker({
                    position: myLatlng,
                    map: map,
                    label : j.toString(),
                    title: data.title
                    
                });
                latlngbounds.extend(marker.position);
                (function (marker, data) {
                    google.maps.event.addListener(marker, "click", function (e) {
                        infoWindow.setContent(data.title);
                        infoWindow.open(map, marker);
                    });
                })(marker, data);
                j++;
            }
            map.setCenter(latlngbounds.getCenter());
            map.fitBounds(latlngbounds);

            //***********ROUTING****************//

            //Intialize the Path Array
            var path = new google.maps.MVCArray();

            //Intialize the Direction Service
            var service = new google.maps.DirectionsService();

            //Set the Path Stroke Color
            var poly = new google.maps.Polyline({ map: map, strokeColor: '#4986E7' });

            //Loop and Draw Path Route between the Points on MAP
            for (var i = 0; i < lat_lng.length; i++) {
                if ((i + 1) < lat_lng.length) {
                    var src = lat_lng[i];
                    var des = lat_lng[i + 1];
                    path.push(src);
                    poly.setPath(path);
                    service.route({
                        origin: src,
                        destination: des,
                        travelMode: google.maps.DirectionsTravelMode.DRIVING
                    }, function (result, status) {
                        if (status == google.maps.DirectionsStatus.OK) {
                            for (var i = 0, len = result.routes[0].overview_path.length; i < len; i++) {
                                path.push(result.routes[0].overview_path[i]);
                            }
                        }
                    });
                }
            }
        }
    </script>
