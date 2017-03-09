@extends('_layouts.app')

@section('body')
<h3>
	Users
	<div class="btn-group pull-right">
		<a href="/user/create" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span> Add</a>
		<a href="/user/toConfirm" class="btn btn-primary"><span class="glyphicon glyphicon-eye-open"></span> To confirm</a>
	</div>
</h3>

<div class="row">
	<div class="col-sm-12">
		
	</div>
</div>
<table class="table table-stripped table-hover">
	<thead>
		<tr>
			<th>Email</th>
			<th>Role</th>
			<th>Confirm</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		@foreach($data as $d)
		<tr>
			<td>{{$d->email}}</td>
			<td>{{Config::get('opts.user_roles.'.$d->role)}}</td>
			<td>{{Config::get('opts.user_confirms.'.$d->confirmed)}}</td>
			<td>
				<form method="POST" action="/user/{{$d->id}}" onsubmit="return confirmDelete()">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<input type="hidden" name="_method" value="DELETE">

					<div class="btn-group pull-right">
						<a href="/user/{{$d->id}}/edit" class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-pencil"></span> Edit</a>
						<button class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-remove"></span> Delete</button>
					</div>
				</form>
			</td>
		</tr>
		@endforeach
	</tbody>
</table>
@stop