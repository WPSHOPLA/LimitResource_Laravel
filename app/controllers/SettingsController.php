<?php

class SettingsController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$markers = Vineyard::select('id', 'name', 'lat', 'lng')->where('confirmed', 1)->get()->toArray();
		$options = Option::whereNull('user_id')->lists('value', 'key');

		return View::make('admin.settings', compact('options', 'markers'));
	}

	public function update()
	{
		foreach(Input::all() as $key => $value){
			if(!in_array($key, ['_token'])){
				$data = [
					'user_id'	=> NULL,
					'key'		=> $key,
					'value'		=> $value
				];

				$option = Option::where('key', $key)->first();
				if(is_null($option)){
					Option::create($data);
				} else {
					$option->update($data);
				}
			}
		}

		return Redirect::back()->withMsg(['status' => 'success', 'msg' => 'Settings has been updated']);
	}

	public static function getOptions()
	{
		$global = Option::whereNull('user_id')->lists('value', 'key');
		$user 	= Auth::user()->options()->lists('value', 'key');

		return array_merge($global, $user);
	}
}