@extends('_layouts.app')

@section('styles')
<link rel="stylesheet" type="text/css" href="/css/wine.css">
<link rel="stylesheet" type="text/css" href="/js/jquery-ui/jquery-ui.min.css">
@stop

@section('body')
@if(isset($data))
{{ Form::model($data, array('url' => '/wine/'.$data->vineyard_id.'/'.$data->id, 'class' => 'form-horizontal')) }}
{{ Form::hidden('_method', 'PUT') }}
<h3>Edit wine</h3>
@else
{{ Form::open(array('url' => '/wine/'.$id, 'class' => 'form-horizontal')) }}

<h3>Add wine</h3>
@endif
{{ Form::token() }}

<div class="form-group">
	{{ Form::label('name', 'Name', ['class' => 'col-sm-2 control-label']) }}
	<div class="col-sm-10">
		{{ Form::text('name', null, ['class' => 'form-control']) }}
	</div>
</div>
<div class="form-group">
	{{ Form::label('vineyard_id', 'Vineyard', ['class' => 'col-sm-2 control-label']) }}
	<div class="col-sm-8">
		{{ Form::select('vineyard_id', $vineyards, null, ['class' => 'form-control']) }}
	</div>
	<div class="col-sm-2">
		<a href="#" class="btn btn-primary" id="openBtn">Add vineyard</a>
	</div>
</div>
<div class="form-group">
	{{ Form::label('year', 'Year', ['class' => 'col-sm-2 control-label']) }}
	<div class="col-sm-10">
		{{ Form::text('year', null, ['class' => 'form-control']) }}
	</div>
</div>
<div class="form-group">
	{{ Form::label('grapes', 'Grapes', ['class' => 'col-sm-2 control-label']) }}
	<div class="col-sm-10">
		{{ Form::textarea('grapes', null, ['class' => 'form-control']) }}
	</div>
</div>
<div class="form-group">
	{{ Form::label('vintage', 'Notes', ['class' => 'col-sm-2 control-label']) }}
	<div class="col-sm-10">
		{{ Form::textarea('vintage', null, ['class' => 'form-control']) }}
	</div>
</div>

<div class="form-group">
	{{ Form::hidden('users', '', ['id' => 'users']) }}
	<hr>
	<div class="allow_users">
		<div class="col-sm-offset-2 col-sm-4">
			<h4>Allow access:</h4>

			<div class="row">
				<div class="col-sm-8">
					<input type="text" id="userList" class="form-control" />
					<button class="btn btn-primary addUserManually" type="button">Add</button>
				</div>
				<div class="col-sm-4">or <button class="btn btn-success" type="button" onclick="shareWithAllUsers()">Share with all</button></div>
			</div>
			
			@if(Auth::user()->role == 1)
			<ul id="allow_users">
				@foreach($users as $id => $user)
				<li>{{$user}} <span class="glyphicon glyphicon-remove" onclick="removeUser({{$id}}, this)"></span></li>
				@endforeach
			</ul>
			@endif
		</div>
	</div>
</div>

<div class="form-group">
	<hr>
    <div class="col-sm-offset-2 col-sm-10">
    	<button type="submit" class="btn btn-primary">Save</button>
    </div>
</div>

{{ Form::close() }}


<div id="addVineyard" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Add vineyard</h4>
      </div>
      <div class="modal-body">
        <iframe src="/vineyard/create?nonav=true" style="zoom:0.60" frameborder="0" height="100%" width="99.6%"></iframe>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="saveIframe()">Save</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
@stop

@section('scripts')
<script type="text/javascript" src="/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript">
var added = false;
var users = {{json_encode(array_keys($users))}};

$(function(){
	refreshUsersList();

	@if(Auth::user()->role == 1)
	$( "#userList" ).autocomplete({
		source: "/user/getList",
		minLength: 2,
		select: function( event, ui ) {
			addUser(ui.item.id, ui.item.email);
		}
	}).autocomplete( "instance" )._renderItem = function( ul, item ) {
		return $("<li>").append(item.email).appendTo( ul );
    };
    @else
    $('.addUserManually').click(function() {
    	$.getJSON('/user/getList', {
    		email: $('#userList').val()
    	}, function(data) {
    		if(data.status == 'success'){
    			alert('User has been allowed');
    			$('#userList').val('');
    			addUser(data.data.id, data.data.email);
    		} else {
    			alert('Email does not exists');
    		}
    	})
    });
    @endif
})

function addUser(id, name){
	id = parseInt(id);

	if(users.indexOf(id) === -1){
		users.push(id);
		$('#allow_users').append('<li>'+name+' <span class="glyphicon glyphicon-remove" onclick="removeUser('+id+', this)"></span></li>');
		refreshUsersList();
	}
}
function shareWithAllUsers(){
	$.getJSON('/user/getList', {term: ''}, function(data){
		for(var i in data){
			addUser(data[i].id, data[i].email);
		}

		@if(Auth::user()->role != 1)
		alert('All users has been allowed');
		@endif
	});
}
function removeUser(id, el){
	id = parseInt(id);

	var index = users.indexOf(id);

	if(index !== -1){
		users.splice(index, 1);
		$(el).parent().remove();
	}

	refreshUsersList();
}
function refreshUsersList(){
	$('#users').val(users.join(','));
}

$('#openBtn').click(function(){
	$('#addVineyard').on('hide.bs.modal', function() {
		$.getJSON('/wine/getVineyardList', function(data){
			var selected = $('#vineyard_id').val();
			$('#vineyard_id').html('');

			for(var i in data) {
			    $('#vineyard_id').append($('<option>', { 
			        value: i,
			        text : data[i]
			    }));
			};

			if(added){
				$('#vineyard_id option:last').attr('selected', 'selected');
			} else {
				$('#vineyard_id').val(selected);
			}
		});
	}).on('show.bs.modal', function() {
		added = false;

		var height = $(window).innerHeight()-(2*30);
		$('.modal-dialog').height(height);
		height -= (56+65+30);
		$('.modal-body').height(height);

    	$("iframe").load(function(){
    		if($('iframe').contents().find('.navbar').length > 0){
    			added = true;
    			$('#addVineyard').modal('hide');
    		}
    	});
    });

	$('#addVineyard').modal('show');
});

function saveIframe() {
	$('iframe').contents().find('form').submit();
}
</script>
@stop