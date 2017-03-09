@extends('_layouts.app')

@section('body')
{{ Form::model($data, array('url' => '/user/'.$data->id, 'class' => 'form-horizontal')) }}
{{ Form::token() }}
{{ Form::hidden('_method', 'PUT') }}

<div class="form-group">
	{{ Form::label('email', 'Email', ['class' => 'col-sm-2 control-label']) }}
	<div class="col-sm-10">
		{{ Form::text('email', null, ['class' => 'form-control']) }}
	</div>
</div>

<div class="form-group">
	{{ Form::label('password', 'Password', ['class' => 'col-sm-2 control-label']) }}
	<div class="col-sm-10">
		{{ Form::password('password', ['class' => 'form-control']) }}
	</div>
</div>
<div class="form-group">
	{{ Form::label('conf_password', 'Confirm password', ['class' => 'col-sm-2 control-label']) }}
	<div class="col-sm-10">
		{{ Form::password('conf_password', ['class' => 'form-control']) }}
	</div>
</div>

@if(Auth::user()->role == 1)
<div class="form-group">
	{{ Form::label('role', 'Role', ['class' => 'col-sm-2 control-label']) }}
	<div class="col-sm-10">
		{{ Form::select('role', Config::get('opts.user_roles'), null, ['class' => 'form-control']) }}
	</div>
</div>
<div class="form-group">
	{{ Form::label('confirmed', 'Confirm', ['class' => 'col-sm-2 control-label']) }}
	<div class="col-sm-10">
		{{ Form::select('confirmed', Config::get('opts.user_confirms'), null, ['class' => 'form-control']) }}
	</div>
</div>
@endif

<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
    	<button type="submit" class="btn btn-primary">Save</button>
    </div>
</div>

{{ Form::close() }}
@stop

@section('scripts')
<script type="text/javascript">
	$(function() {
		$('#password').val('');
	});
</script>
@stop