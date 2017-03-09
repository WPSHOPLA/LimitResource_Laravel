<?php

class VineyardController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$data = new Vineyard();
		if(Auth::user()->role != 1){
			$shared = Auth::user()->wines()->lists('vineyard_id');
			
			if(count($shared) == 0){
				$data = $data->where('user_id', Auth::user()->id);
			} else {
				$data = $data->whereRaw(' (user_id = '.Auth::user()->id.' OR id IN ('.implode(',', $shared).'))');
			}
		}

		$data = $data->orderBy('name')->get();

		return View::make('vineyard.index', compact('data'));
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('vineyard.create');
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$validator = Validator::make(Input::except('_token'), [
			'name' => 'required'
		]);

		if($validator->fails())
		{
			return Redirect::back()->withInput()->withErrors($validator);
		}

		Vineyard::create([
			'user_id'	=> Auth::user()->id,
			'name'		=> Input::get('name'),
			'notes'		=> Input::get('notes'),
			'grapes'	=> Input::get('grapes'),
			'city'		=> Input::get('city'),
			'region'	=> Input::get('region'),
			'country'	=> Input::get('country'),
			'lat'		=> Input::get('lat'),
			'lng'		=> Input::get('lng'),
			'confirmed'	=> 1
		]);

		return Redirect::to('/vineyard')->withMsg(['status' => 'success', 'msg' => 'Vineyard has been added']);
	}
	

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$data = Vineyard::where('user_id', Auth::user()->id)->where('id', $id)->first();
		if(is_null($data)){
			return Redirect::to('/vineyard');
		}
		return View::make('vineyard.create', compact('data'));
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$validator = Validator::make(Input::except('_token'), [
			'name' => 'required'
		]);

		if($validator->fails())
		{
			return Redirect::back()->withInput()->withErrors($validator);
		}

		Vineyard::where('id', $id)->update([
			'name'		=> Input::get('name'),
			'notes'		=> Input::get('notes'),
			'grapes'	=> Input::get('grapes'),
			'city'		=> Input::get('city'),
			'region'	=> Input::get('region'),
			'country'	=> Input::get('country'),
			'lat'		=> Input::get('lat'),
			'lng'		=> Input::get('lng'),
			'confirmed'	=> 1
		]);

		return Redirect::to('/vineyard')->withMsg(['status' => 'success', 'msg' => 'Vineyard has been updated']);
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		Vineyard::where('id', $id)->delete();
		return Redirect::to('/vineyard')->withMsg(['status' => 'success', 'msg' => 'Vineyard has been deleted']);
	}


}
