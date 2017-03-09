@extends('_layouts.app')

@section('body')
<style type="text/css">
	#map {
		height: 300px;
		width: 100%;
	}
</style>
@if(isset($data))
{{ Form::model($data, array('url' => '/vineyard/'.$data->id, 'class' => 'form-horizontal')) }}
{{ Form::hidden('_method', 'PUT') }}
@else
{{ Form::open(array('url' => '/vineyard', 'class' => 'form-horizontal')) }}
@endif
{{ Form::token() }}

<div class="form-group">
	{{ Form::label('name', 'Name', ['class' => 'col-sm-2 control-label']) }}
	<div class="col-sm-10">
		{{ Form::text('name', null, ['class' => 'form-control']) }}
	</div>
</div>
<div class="form-group">
	{{ Form::label('notes', 'Notes', ['class' => 'col-sm-2 control-label']) }}
	<div class="col-sm-10">
		{{ Form::textarea('notes', null, ['class' => 'form-control']) }}
	</div>
</div>
<div class="form-group">
	{{ Form::label('grapes', 'Grapes', ['class' => 'col-sm-2 control-label']) }}
	<div class="col-sm-10">
		{{ Form::textarea('grapes', null, ['class' => 'form-control']) }}
	</div>
</div>

<div class="form-group">
	{{ Form::label('placeFilter', 'Google Place Search', ['class' => 'col-sm-2 control-label']) }}
	<div class="col-sm-10">
		{{ Form::text('placeFilter', null, ['class' => 'form-control']) }}
	</div>
</div>

<div class="form-group">
	{{ Form::label('city', 'City', ['class' => 'col-sm-2 control-label']) }}
	<div class="col-sm-10">
		{{ Form::text('city', null, ['class' => 'form-control', 'onchange' => 'geocode()']) }}
	</div>
</div>
<div class="form-group">
	{{ Form::label('region', 'Region', ['class' => 'col-sm-2 control-label']) }}
	<div class="col-sm-10">
		{{ Form::text('region', null, ['class' => 'form-control']) }}
	</div>
</div>
<div class="form-group">
	{{ Form::label('country', 'Country', ['class' => 'col-sm-2 control-label']) }}
	<div class="col-sm-10">
		{{ Form::text('country', null, ['class' => 'form-control', 'onchange' => 'geocode()']) }}
	</div>
</div>
<div class="form-group">
	{{ Form::label('lat', 'lat', ['class' => 'col-sm-2 control-label']) }}
	<div class="col-sm-10">
		{{ Form::text('lat', null, ['class' => 'form-control', 'onchange' => 'updateLatLng()']) }}
	</div>
</div>
<div class="form-group">
	{{ Form::label('lng', 'lng', ['class' => 'col-sm-2 control-label']) }}
	<div class="col-sm-10">
		{{ Form::text('lng', null, ['class' => 'form-control', 'onchange' => 'updateLatLng()']) }}
	</div>
</div>


<div class="form-group">
	<div class="col-sm-12">
		<div id="map"></div>
		<p>* You can drag & drop the marker to more precise</p>
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
<script>
	var map, marker, geocoder, autocomplete;
	function initMap() {
		var loc = {lat: $('#lat').val() == '' ? 0 : parseFloat($('#lat').val()), lng: $('#lng').val() == '' ? 0 : parseFloat($('#lng').val())};

		map = new google.maps.Map(document.getElementById('map'), {
			center: loc,
			zoom: 5
		});

		marker = new google.maps.Marker({
			position: loc,
			map: map,
			draggable: true
		});

		marker.addListener('dragend', function(e){
			$('#lat').val(e.latLng.lat());
			$('#lng').val(e.latLng.lng());

			map.setCenter(e.latLng);
		});

		geocoder = new google.maps.Geocoder();

		autocomplete = new google.maps.places.Autocomplete(document.getElementById('placeFilter'));
      	autocomplete.addListener('place_changed', function() {
      		var place = autocomplete.getPlace();

			for (var i = 0; i < place.address_components.length; i++) {
				console.log(place.address_components[i]);
				if (place.address_components[i].types[0] == 'locality') {
					$('#city').val(place.address_components[i]['long_name']);
					$('#lat').val(place.geometry.location.lat());
					$('#lng').val(place.geometry.location.lng());
				}

				if(place.address_components[i].types[0] == 'administrative_area_level_1'){
					$('#region').val(place.address_components[i]['long_name']);
				}

				if(place.address_components[i].types[0] == 'country'){
					$('#country').val(place.address_components[i]['long_name']);
				}
			}
      	});
	}

	function geocode(){
		var address = $('#city').val()+' '+$('#country').val();

		geocoder.geocode({address: address}, function(res, status){
			if(status == google.maps.GeocoderStatus.OK){
				var loc = {lat: res[0].geometry.location.lat(), lng: res[0].geometry.location.lng()};

				map.setCenter(loc);
				marker.setPosition(loc);

				$('#lat').val(loc.lat);
				$('#lng').val(loc.lng);
			}
		});
	}

	function updateLatLng() {
		var loc = {lat: $('#lat').val() == '' ? 0 : parseFloat($('#lat').val()), lng: $('#lng').val() == '' ? 0 : parseFloat($('#lng').val())};
		marker.setPosition(loc);
		map.setCenter(loc);
	}
</script>

<script src="https://maps.googleapis.com/maps/api/js?sensor=false&libraries=places&callback=initMap"></script>
@stop