<?php
	$msgType = 'success';
	$msgText = [];

	$validErrors = is_array(Session::get('errors', [])) ? [] :  Session::get('errors')->all();
	$msg = Session::get('msg', []);

	if(count($validErrors) > 0){
		$msgType = 'danger';
	} elseif(isset($msg['status'])) {
		$msgType = $msg['status'];
		$msgText = [$msg['msg']];
	}

	$messages = array_merge($validErrors, $msgText);
?>
@if(count($messages))
<div class="alert alert-{{$msgType}} alert-dismissable">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <span>
        <div>{{ implode('<br>', $messages) }}</div>
    </span>
</div>
@endif