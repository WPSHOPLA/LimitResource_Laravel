@extends('_layouts.app')

@section('body')
{{ Form::open(array('url' => '/user', 'class' => 'form-horizontal')) }}
{{ Form::token() }}

<div class="form-group">
	{{ Form::label('email', 'Email', ['class' => 'col-sm-2 control-label']) }}
	<div class="col-sm-10">
		{{ Form::text('email', null, ['class' => 'form-control']) }}
	</div>
</div>

<div class="form-group">
	{{ Form::label('password', 'Password', ['class' => 'col-sm-2 control-label']) }}
	<div class="col-sm-10">
		{{ Form::text('password', null, ['class' => 'form-control']) }}
	</div>
</div>

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
		if($('.alert-danger').length == 0){
			$('#email').val('');
			$('#password').val('');
		}
	});
</script>
@stop