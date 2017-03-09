@extends('_layouts.app')

@section('styles')
<link rel="stylesheet" type="text/css" href="/css/map.css">
@stop

@section('body')
<div class="filters">
	<div class="onlyUserVineyards form-control">
		<label><input type="checkbox" id="onlyUserVineyards"> Show only my vineyards</label>
	</div>
</div>
<div id="map"></div>

<div id="vineyardDetails" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"></h4>
      </div>
      <div class="modal-body">
        
      </div>
    </div>
  </div>
</div>
@stop

@section('scripts')
<script src="/js/markerclusterer.js"></script>

<script>
	var markersToAdd = {{json_encode($markers)}};
	var onlyUserVineyards = {{json_encode($onlyUserVineyards)}};

	var map, markers = [], mc;
	function initMap() {
		$('#map').height(window.innerHeight-$('.navbar').height()-$('.filters').height()-2);

		map = new google.maps.Map(document.getElementById('map'), {
			center: {lat: parseFloat('{{getConfig('map_center_lat', 0)}}'), lng: parseFloat('{{getConfig('map_center_lng', 0)}}')},
			zoom: parseInt('{{getConfig('map_center_zoom', 3)}}')
		});

		for(var i = 0; i < markersToAdd.length; ++i){
			addMarker(markersToAdd[i].id, markersToAdd[i].lat, markersToAdd[i].lng, markersToAdd[i].name);
		}

		mc = new MarkerClusterer(map, markers, {gridSize: 50, maxZoom: 15});

		$('#onlyUserVineyards').change(function(){
			mc.clearMarkers();
			// mc.setMap(null);

			for(var i in markers){
				markers[i].setMap(null);
			}

			markers = [];

			if($('#onlyUserVineyards').prop('checked')){
				for(var i = 0; i < onlyUserVineyards.length; ++i){
					addMarker(onlyUserVineyards[i].id, onlyUserVineyards[i].lat, onlyUserVineyards[i].lng, onlyUserVineyards[i].name);
				}
			} else {
				for(var i = 0; i < markersToAdd.length; ++i){
					addMarker(markersToAdd[i].id, markersToAdd[i].lat, markersToAdd[i].lng, markersToAdd[i].name);
				}
			}

			mc = new MarkerClusterer(map, markers, {gridSize: 50, maxZoom: 15});
		});
	}

	function addMarker(id, lat, lng, title){
		var marker = new google.maps.Marker({
			position: {lat: parseFloat(lat), lng: parseFloat(lng)},
			title: title,
			cId: id
		});

		marker.addListener('click', function(){
			showInfo(this.cId);
		});

		markers.push(marker);
	}

	function showInfo(id){
		$.getJSON('/vineyard_details/'+id, function(data){
			if(data.status == 'success'){
				$('#vineyardDetails .modal-title').html(data.title);
				$('#vineyardDetails .modal-body').html(data.body);
				$('#vineyardDetails').modal('show');
			}
		});
		
	}
</script>

<script src="https://maps.googleapis.com/maps/api/js?sensor=false&callback=initMap"></script>
@stop