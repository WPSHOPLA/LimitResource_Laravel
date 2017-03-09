<?php

class UserController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$data = User::orderBy('role')->orderBy('email')->get();
		return View::make('user.index', compact('data'));
	}

	public function toConfirm()
	{
		$data = User::where('confirmed', array_search('Unconfirmed', Config::get('opts.user_confirms')))->orderBy('email')->get();
		return View::make('user.index', compact('data'));
	}

	public function profile()
	{
		return View::make('user.edit', ['data' => Auth::user()]);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('user.create');
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$validator = Validator::make(Input::except('_token'), [
				'email' => 'required|email|unique:users',
				'password' => 'required|min:6',
			], [], [
			'email' => 'email address'
		]);

		if($validator->fails())
		{
			return Redirect::back()->withInput()->withErrors($validator);
		}

		User::create([
			'email'		=> Input::get('email'),
			'password'	=> Hash::make(Input::get('password')),
			'role'		=> Input::get('role'),
			'confirmed'	=> Input::get('confirmed')
		]);

		return Redirect::to('/user')->withMsg(['status' => 'success', 'msg' => 'User has been added']);
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		return View::make('user.edit', ['data' => User::find($id)]);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$valid = ['email' => 'required|email|unique:users,email,'.$id.',id'];
		$data = ['email' => Input::get('email')];

		if(strlen(Input::get('password')) > 0){
			$valid = array_merge($valid, [
				'password' => 'required|min:6',
	        	'conf_password' => 'required|same:password'
			]);

			$data['password'] = Hash::make(Input::get('password'));
		}

		$validator = Validator::make(Input::except('_token'), $valid, [], [
			'email' => 'email address',
			'conf_password' => 'confirm password'
		]);

		if($validator->fails())
		{
			return Redirect::back()->withInput(Input::except(['password', 'conf_password']))->withErrors($validator);
		}
		
		if(Auth::user()->role == 1){
			$data['role'] = Input::get('role');
			$data['confirmed'] = Input::get('confirmed');
		}

		User::where('id', $id)->update($data);

		return Redirect::to('/user')->withMsg(['status' => 'success', 'msg' => 'Profile has been updated']);
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		User::where('id', $id)->delete();
		return Redirect::to('/user')->withMsg(['status' => 'success', 'msg' => 'User has been deleted']);
	}

	public function getUserList(){
		$return = [];

		if(Auth::user()->role == 1){
			$return = User::select(['id', 'email'])->where('role', '!=', 1)->where('email', 'LIKE', '%'.Input::get('term').'%')->get();
		} else {
			if(Input::has('email')) {
				$ret = User::select(['id'])->where('role', '!=', 1)->where('email', Input::get('email'))->first();
				
				if(!is_null($ret)){
					return ['status' => 'success', 'data' => $ret];
				}
			}
			if(Auth::user()->role != 1 && Input::get('term') === ''){
				$return = User::select(['id'])->where('role', '!=', 1)->get();
			}
		}

		return $return;
	}

	public function settings()
	{
		$markers = Vineyard::select('id', 'name', 'lat', 'lng')->where('confirmed', 1)->get()->toArray();
		$options = SettingsController::getOptions();

		return View::make('user.settings', compact('options', 'markers'));
	}

	public function updateSettings()
	{
		foreach(Input::all() as $key => $value){
			if(!in_array($key, ['_token'])){
				$data = [
					'key'		=> $key,
					'value'		=> $value
				];

				$option = Auth::user()->options()->where('key', $key)->first();
				if(is_null($option)){
					Auth::user()->options()->create($data);
				} else {
					$option->update($data);
				}
			}
		}

		return Redirect::back()->withMsg(['status' => 'success', 'msg' => 'Settings has been updated']);
	}


}
