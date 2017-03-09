@extends('_layouts.app')

@section('body')
<style type="text/css">
	.glyphicon-share-alt {
		-webkit-transform: rotate(-90deg);
		-moz-transform: rotate(-90deg);
		-ms-transform: rotate(-90deg);
		-o-transform: rotate(-90deg);
		filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=3);
	}
</style>
<h3>
	@if($id != 0)
	<a href="/vineyard"><span class="glyphicon glyphicon-share-alt" style=""></span></a> {{$vineyard->name}} - 
	@endif
	Wines

	<div class="btn-group pull-right">
		<a href="/wine/{{$id}}/create" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span> Add</a>
	</div>
</h3>

<div class="row">
	<div class="col-sm-12">
		
	</div>
</div>
<table class="table table-stripped table-hover">
	<thead>
		<tr>
			<th>Name</th>
			<th>Year</th>
			<th>Vineyard</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		@foreach($data as $d)
		<tr>
			<td>{{$d->name}}</td>
			<td>{{$d->year}}</td>
			<td><a href="/wine/{{$d->vineyard_id}}"><span class="glyphicon glyphicon-share-alt" style=""></span> {{$d->vineyard->name}}</a></td>
			<td>
				<form method="POST" action="/wine/{{$d->vineyard_id}}/{{$d->id}}" onsubmit="return confirmDelete()">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<input type="hidden" name="_method" value="DELETE">

					<div class="btn-group pull-right">
						<a href="/wine/{{$d->vineyard_id}}/{{$d->id}}/edit" class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-pencil"></span> Edit</a>
						<button class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-remove"></span> Delete</button>
					</div>
				</form>
			</td>
		</tr>
		@endforeach
	</tbody>
</table>
@stop