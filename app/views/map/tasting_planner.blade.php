@extends('_layouts.app')

@section('styles')
<link rel="stylesheet" type="text/css" href="/css/tasting_planner.css">
<link rel="stylesheet" type="text/css" href="/js/jquery-ui/jquery-ui.min.css">
@stop

@section('body')
<div class="row">
	<div class="col-sm-2">
		<div class="left-list">
			<input type="text" id="vineyardName" class="form-control" placeholder="Vineyard name">
			<ul id="vineyards"></ul>

			<div class="stats">
				<table>
					<tr class="total">
						<td>Total Distance:</td>
						<td><span id="distance">-</span></td>
					</tr>
					<tr class="total">
						<td>Total Duration:</td>
						<td><span id="duration">-</span></td>
					</tr>
				</table>

				<button class="btn btn-primary" onclick="restart();">New tour</button>
			</div>
		</div>
	</div>
	<div class="col-sm-10">
		<div id="map"></div>
	</div>
</div>
@stop

@section('scripts')
<script type="text/javascript" src="/js/jquery-ui/jquery-ui.min.js"></script>

<script>
	var vineyards = {{json_encode($vineyards)}};
	var map, directionsService, directionsDisplay, stepDisplay, markerArray = [],
	locs = [];

	function initMap() {
		updateSort();

		$('#map').height(window.innerHeight-$('.navbar').height()-2);

		map = new google.maps.Map(document.getElementById('map'), {
			center: {lat: parseFloat('{{getConfig('map_center_lat', 0)}}'), lng: parseFloat('{{getConfig('map_center_lng', 0)}}')},
			zoom: parseInt('{{getConfig('map_center_zoom', 3)}}')
		});

		directionsDisplay = new google.maps.DirectionsRenderer({map: map});
		directionsService = new google.maps.DirectionsService;

		stepDisplay = new google.maps.InfoWindow;

		$('#vineyardName').autocomplete({
			source: vineyards,
			minLength: 0,
			select: function( event, ui ) {
				addLoc(ui.item, true);

				$('#vineyardName').val('');
				return false;
			}
		}).autocomplete( "instance" )._renderItem = function( ul, item ) {
			return $("<li>").append(item.value).appendTo( ul );
		};

		$('#vineyardName').click(function(){
			$(this).autocomplete('search', '');
		});

	}

	function calculateAndDisplayRoute(directionsDisplay, directionsService, markerArray, stepDisplay, map) {
		for (var i = 0; i < markerArray.length; i++) {
			markerArray[i].setMap(null);
		}

		var origin = locs[0].lat+','+locs[0].lng;
		var destination = locs[locs.length-1].lat+','+locs[locs.length-1].lng;
		var waypoints = [];
		for(var i = 1; i < locs.length-1; ++i){
			waypoints.push({
				location: locs[i].lat+','+locs[i].lng,
				stopover: true
			})
		}

		directionsService.route({
			origin: origin,
			destination: destination,
			waypoints: waypoints,
			travelMode: google.maps.TravelMode.DRIVING
		}, function(response, status) {
			if (status === google.maps.DirectionsStatus.OK) {
				// console.log('<b>' + response.routes[0].warnings + '</b>');
				directionsDisplay.setDirections(response);
				showSteps(response, markerArray, stepDisplay, map);
			} else {
				window.alert('Directions request failed due to ' + status);
			}
		});
	}

	function showSteps(directionResult, markerArray, stepDisplay, map) {
		var myRoute = directionResult.routes[0].legs;

		var totalDistance = 0;
		var totalDuration = 0;

		$('.stats .leg').remove();
		var stepsHtml = '';

		for(var i = 0; i < myRoute.length; ++i) {
		    totalDistance += myRoute[i].distance.value;
		    totalDuration += myRoute[i].duration.value;

		    stepsHtml += 
'<tr class="leg"><td>Distance from '+String.fromCharCode(65 + i)+ ' to '+String.fromCharCode(65 + i + 1)+':</td><td><span>'+((myRoute[i].distance.value/1000).toFixed(2))+' km</span></td></tr>'+
'<tr class="leg"><td>Duration from '+String.fromCharCode(65 + i)+ ' to '+String.fromCharCode(65 + i + 1)+':</td><td><span>'+myRoute[i].duration.value.toString().toHHMMSS()+'</span></td></tr>';
		}

		$('.stats table').prepend(stepsHtml);

		totalDistance = totalDistance/1000;


		$('#distance').html(totalDistance.toFixed(2)+' km');
		$('#duration').html(totalDuration.toString().toHHMMSS());

		console.log(myRoute);

		// for (var i = 0; i < myRoute.steps.length; i++) {
		// 	var marker = markerArray[i] = markerArray[i] || new google.maps.Marker;
		// 	marker.setMap(map);
		// 	marker.setPosition(myRoute.steps[i].start_location);
		// 	attachInstructionText(
		// 		stepDisplay, marker, myRoute.steps[i].instructions, map);
		// }
	}

	function attachInstructionText(stepDisplay, marker, text, map) {
		google.maps.event.addListener(marker, 'click', function() {
			stepDisplay.setContent(text);
			stepDisplay.open(map, marker);
		});
	}

	function restart(){
		locs = [];
		$('#vineyards').html('');
		$('#distance').html('');
		$('#duration').html('');
		$('.leg').remove();

		directionsDisplay.setMap(null);
		directionsService = new google.maps.DirectionsService;
	}

	function addLoc(loc, recalc) {
		for(var i in locs){
			if(locs[i].id == loc.id){
				return false;
			}
		}

		locs.push(loc);
		$('#vineyards').html('');

		for (var i in locs){
			$('#vineyards').append('<li data-id="'+locs[i].id+'"><span class="glyphicon glyphicon-th"></span><div class="unsortable">'+locs[i].value+' <span class="glyphicon glyphicon-remove" onclick="removeLoc('+locs[i].id+', this)"></span></div></li>')
		}

		if(recalc){
			recalculate();
		}
	}
	function removeLoc(id, el) {
		for(var i in locs){
			if(locs[i].id == id){
				locs.splice(i, 1);
				break;
			}
		}

		$(el).parent().parent().remove();
		
		recalculate();
	}

	function recalculate() {
		
		// console.log(locs);

		if(locs.length > 1){
			directionsService = new google.maps.DirectionsService;
			calculateAndDisplayRoute(directionsDisplay, directionsService, markerArray, stepDisplay, map);
		} else if(locs.length == 1) {
			directionsDisplay = new google.maps.DirectionsRenderer({map: map});
		}
	}

	function updateSort() {
		$("#vineyards").sortable({
			// helper: fixHelper,
			cancel: '.unsortable'
		})
		.on("sortupdate", function(event, ui){
			var ids = [];
			$(this).find('li').each(function(){
				ids.push($(this).data('id'));
			});

			console.log(ids);

			restart();
			directionsDisplay = new google.maps.DirectionsRenderer({map: map});

			for(var i in ids){
				for(var v in vineyards){
					if(vineyards[v].id == ids[i]){
						addLoc(vineyards[v], false);
					}
				}
			}

			console.log(vineyards);
	    
			recalculate();
		});
	}

	String.prototype.toHHMMSS = function () {
	    var sec_num = parseInt(this, 10); // don't forget the second param
	    var hours   = Math.floor(sec_num / 3600);
	    var minutes = Math.floor((sec_num - (hours * 3600)) / 60);
	    var seconds = sec_num - (hours * 3600) - (minutes * 60);

	    if (hours   < 10) {hours   = "0"+hours;}
	    if (minutes < 10) {minutes = "0"+minutes;}
	    if (seconds < 10) {seconds = "0"+seconds;}
	    var time    = hours+' h '+minutes+' min';
	    return time;
	}
</script>

<script src="https://maps.googleapis.com/maps/api/js?sensor=false&callback=initMap"></script>
@stop