@extends('_layouts.app')

@section('body')
<div class="page-header">
  <h2>Eliminate your limiting resource</h2>
</div>

<div class="row">
	<div class="col-sm-5">
		<table class="table table-bordered">
		  	<tr>
		  		<td>Username/email</td>
		  		<td>{{Auth::user()->email}}</td>
		  	</tr>
		  	<tr>
		  		<td>Last time logged in</td>
		  		<td>{{Auth::user()->last_login}}</td>
		  	</tr>
		  	<tr>
		  		<td colspan="2" class="empty"></td>
		  	</tr>
		  	<tr>
		  		<td>Wines</td>
		  		<td>{{$stats['wines']}}</td>
		  	</tr>
		  	<tr>
		  		<td>Vineyards</td>
		  		<td>{{$stats['vineyards']}}</td>
		  	</tr>
		  	<tr>
		  		<td>Cities</td>
		  		<td>{{$stats['cities']}}</td>
		  	</tr>
		  	<tr>
		  		<td>Countries</td>
		  		<td>{{$stats['countries']}}</td>
		  	</tr>
		  </table>

		  <a href="/user/settings" class="btn btn-primary" style="width: 100%;">Reset Map Starting Position</a>
	</div>

	<div class="col-sm-2">
		<a href="/map" class="btn btn-lg btn-success" style="width: 100%;">Go to My Map!</a>
	</div>
</div>
@stop