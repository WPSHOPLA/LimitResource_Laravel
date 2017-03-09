<?php

class AuthController extends Controller {

	public function login()
	{
		if(Auth::check())
		{
			return Redirect::to('/');
		}

		return View::make('auth.login');
	}

	public function auth()
	{
		if(Auth::check())
		{
			return Redirect::to('/');
		}

		$user = [
            'email'		=> Input::get('email'),
            'password'	=> Input::get('password')
        ];

        $validator = Validator::make($user, [
	        'email'		=> 'required|email',
	        'password'	=> 'required|min:6',
		], [], [
			'email'	=> 'email address',
		]);

		if($validator->fails())
		{
			return Redirect::back()->withInput(Input::except('password'))->withErrors($validator);
		}
        
        if (Auth::attempt($user)) 
        {
        	if(Auth::user()->confirmed == 0){
        		Auth::logout();
				return Redirect::to('/login')->withMsg(['status' => 'info', 'msg' => 'Your account has to be confirmed by administrator']);
        	}

        	User::where('id', Auth::user()->id)->update([
        		'last_login' => date('Y-m-d H:i:s', time())
        	]);

        	return Redirect::to('/');
        }

		return Redirect::back()->withInput(Input::except('password'))->withMsg(['status' => 'danger', 'msg' => 'Email address or password is incorrect']);
	}

	public function logout()
	{
		Auth::logout();

		return Redirect::to('/login')->withMsg(['status' => 'success', 'msg' => 'Successfully logout']);
	}

	public function signup()
	{
		if(Auth::check())
		{
			return Redirect::to('/');
		}

		return View::make('auth.register');
	}

	public function register()
	{
        $validator = Validator::make(Input::except('_token'), [
	        'email' => 'required|email|unique:users',
	        'password' => 'required|min:6',
	        'conf_password' => 'required|same:password'
		], [], [
			'email' => 'email address',
			'conf_password' => 'confirm password'
		]);

		if($validator->fails())
		{
			return Redirect::back()->withInput(Input::only('email'))->withErrors($validator);
		}

		User::create([
			'email'		=> Input::get('email'),
			'password'	=> Hash::make(Input::get('password')),
			'role'		=> 3
		]);

		return Redirect::to('/login')->withMsg(['status' => 'success', 'msg' => 'Account has been created']);
	}
}