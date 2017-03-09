@extends('_layouts.app')

@section('body')
<h3>
	Vineyards
	<div class="btn-group pull-right">
		<a href="/vineyard/create" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span> Add</a>
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
			<th>City</th>
			<th>Country</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		@foreach($data as $d)
		<tr>
			<td>
				{{$d->name}}
				@if(Auth::user()->id != $d->user_id)
				<small>(Shared)</small>
				@endif
			</td>
			<td>{{$d->city}}</td>
			<td>{{$d->country}}</td>
			<td>
				<form method="POST" action="/vineyard/{{$d->id}}" onsubmit="return confirmDelete()">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<input type="hidden" name="_method" value="DELETE">

					<div class="btn-group pull-right">
						@if(Auth::user()->id == $d->user_id)
						<a href="/vineyard/{{$d->id}}/edit" class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-pencil"></span> Edit</a>
						@endif
						<a href="/wine/{{$d->id}}" class="btn btn-success btn-xs"><span class="glyphicon glyphicon-glass"></span> Wines</a>
						@if(Auth::user()->id == $d->user_id)
						<button class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-remove"></span> Delete</button>
						@endif
					</div>
				</form>
			</td>
		</tr>
		@endforeach
	</tbody>
</table>
@stop