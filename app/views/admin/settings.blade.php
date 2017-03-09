@extends('_layouts.app')

@section('body')
<style type="text/css">
	#map {
		height: 400px;
	}
</style>
{{ Form::model($options, array('url' => '/admin/settings', 'class' => 'form-horizontal')) }}

<h4>Global Settings</h4>

<div class="form-group">
	{{ Form::label('Map', 'Map', ['class' => 'col-sm-2 control-label']) }}
	<div class="col-sm-10">
		{{Form::hidden('map_center_lat', null, ['id' => 'map_center_lat'])}}
		{{Form::hidden('map_center_lng', null, ['id' => 'map_center_lng'])}}
		{{Form::hidden('map_center_zoom', null, ['id' => 'map_center_zoom'])}}

		<div id="map"></div>
	</div>
</div>

<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
    	<button type="submit" class="btn btn-primary">Save</button>
    </div>
</div>

{{ Form::close() }}
@stop

@section('scripts')
<script src="/js/markerclusterer.js"></script>

<script>
	var markersToAdd = {{json_encode($markers)}};
	var map, markers = [], mc;
	function initMap() {
		map = new google.maps.Map(document.getElementById('map'), {
			center: {lat: parseFloat('{{$options['map_center_lat']}}'), lng: parseFloat('{{$options['map_center_lng']}}')},
			zoom: parseInt('{{$options['map_center_zoom']}}')
		});

		map.addListener('idle', function() {
			$('#map_center_lat').val(map.getCenter().lat());
			$('#map_center_lng').val(map.getCenter().lng());
			$('#map_center_zoom').val(map.getZoom());
		});

		for(var i = 0; i < markersToAdd.length; ++i){
			addMarker(markersToAdd[i].id, markersToAdd[i].lat, markersToAdd[i].lng, markersToAdd[i].name);
		}

		mc = new MarkerClusterer(map, markers, {gridSize: 50, maxZoom: 15});
	}

	function addMarker(id, lat, lng, title){
		var marker = new google.maps.Marker({
			position: {lat: parseFloat(lat), lng: parseFloat(lng)},
			title: title,
			cId: id
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