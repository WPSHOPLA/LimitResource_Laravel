<?php

class WineController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index($id)
	{
		if(Auth::user()->role == 1){
			$data = Wine::select('*');
			if($id != 0){
				$data = $data->where('vineyard_id', $id);
			}

			$data = $data->with('vineyard')->orderBy('name')->get();
		} else {
			$data = Auth::user()->wines()->with('vineyard')->orderBy('name')->get();
		}

		if($id != 0){
			$vineyard = Vineyard::find($id);
		}

		return View::make('wine.index', compact('data', 'vineyard', 'id'));
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create($id)
	{
		$vineyards = $this->getVineyardList();
		$users = [];

		return View::make('wine.create', compact('id', 'users', 'vineyards'));
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store($id)
	{
		$validator = Validator::make(Input::except('_token'), [
			'name' => 'required'
		]);

		if($validator->fails())
		{
			return Redirect::back()->withInput()->withErrors($validator);
		}

		$wine = Wine::create([
			'user_id'	=> Auth::user()->id,
			'vineyard_id' => Input::get('vineyard_id'),
			'name'		=> Input::get('name'),
			'year'		=> Input::get('year'),
			'grapes'	=> Input::get('grapes'),
			'vintage'	=> Input::get('vintage'),
			'confirmed'	=> 1
		]);

		// if(Auth::user()->role != 1){
		// 	$wine->users()->sync([Auth::user()->id]);
		// } else {
			$users = explode(',', Input::get('users'));
			if(count($users) == 0 || (count($users) == 1 && $users[0] == '')){
				$wine->users()->sync([]);
			} else {
				$wine->users()->sync($users);
			}
		// }

		return Redirect::to('/wine/0')->withMsg(['status' => 'success', 'msg' => 'Wine has been added']);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($vid, $id)
	{
		if(Auth::user()->role == 1){
			$data = Wine::find($id);
		} else {
			$data = Auth::user()->wines()->where('id', $id)->first();
			
			if(is_null($data)){
				return Redirect::to('/wine/0');
			}
		}

		$users = $data->users()->lists('email', 'id');

		$vineyards = $this->getVineyardList();

		return View::make('wine.create', compact('data', 'users', 'vineyards'));
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($vid, $id)
	{
		$validator = Validator::make(Input::except('_token'), [
			'name' => 'required'
		]);

		if($validator->fails())
		{
			return Redirect::back()->withInput()->withErrors($validator);
		}

		$wine = Wine::find($id);
		$wine->update([
			'vineyard_id' => Input::get('vineyard_id'),
			'name'		=> Input::get('name'),
			'year'		=> Input::get('year'),
			'grapes'	=> Input::get('grapes'),
			'vintage'	=> Input::get('vintage'),
		]);

		// if(Auth::user()->role == 1){
			$users = explode(',', Input::get('users'));
			if(count($users) == 0 || (count($users) == 1 && $users[0] == '')){
				$wine->users()->sync([]);
			} else {
				$wine->users()->sync($users);
			}
		// }

		return Redirect::to('/wine/0')->withMsg(['status' => 'success', 'msg' => 'Wine has been updated']);
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($vid, $id)
	{
		Wine::where('id', $id)->delete();
		return Redirect::to('/wine/'.$vid)->withMsg(['status' => 'success', 'msg' => 'Wine has been deleted']);
	}


	public function getVineyardList(){
		$shared = Auth::user()->wines()->lists('vineyard_id');
		$vineyards = Vineyard::select('*');

		if(Auth::user()->role != 1){
			if(count($shared) == 0){
				$vineyards = $vineyards->where('user_id', Auth::user()->id);
			} else {
				$vineyards = $vineyards->whereRaw(' (user_id = '.Auth::user()->id.' OR id IN ('.implode(',', $shared).'))');
			}
		}

		return $vineyards->lists('name', 'id');
	}
	
}
