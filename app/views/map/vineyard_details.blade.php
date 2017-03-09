<h3>Wines</h3>
<ul class="wines_list">
	@foreach($wines as $wine)
	<?php
		$user_wine = false;
		if(Auth::user()->role == 1){
			$user_wine = true;
		} else {
			foreach ($wine->users as $u) {
				if($u->id == Auth::user()->id){
					$user_wine = true;
					break;
				}
			}
		}
	?>
	@if($user_wine)
	<li><a href="/wine/{{$wine->vineyard_id.'/'.$wine->id}}/edit">{{$wine->name.' (<i>'.$wine->year.'</i>)'}}</a></li>
	@else
	<li>{{$wine->name.' (<i>'.$wine->year.'</i>)'}}</li>
	@endif
	@endforeach
</ul>

<h3>Vintages</h3>
<div>{{nl2br($vineyard->vintage)}}</div>

<h3>Notes</h3>
<div>{{nl2br($vineyard->notes)}}</div>

<h3>Grapes</h3>
<div>{{nl2br($vineyard->grapes)}}</div>