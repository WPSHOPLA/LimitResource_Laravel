<?php

class MapController extends Controller
{
	public function index()
	{
		$markers = Vineyard::select('id', 'name', 'lat', 'lng')->where('confirmed', 1)->get()->toArray();
		
		$onlyUserVineyards = [];
		$onlyUserVineyards = Vineyard::select('id', 'name', 'lat', 'lng');
		if(Auth::user()->role != 1){
			$shared = Auth::user()->wines()->lists('vineyard_id');
			
			if(count($shared) == 0){
				$onlyUserVineyards = $onlyUserVineyards->where('user_id', Auth::user()->id);
			} else {
				$onlyUserVineyards = $onlyUserVineyards->whereRaw(' (user_id = '.Auth::user()->id.' OR id IN ('.implode(',', $shared).'))');
			}
		}

		$onlyUserVineyards = $onlyUserVineyards->orderBy('name')->get();

		return View::make('map.index', compact('markers', 'onlyUserVineyards'));
	}

	public function tasting_planner()
	{
		$vineyards = Vineyard::select('id', 'name as value', 'lat', 'lng')->where('confirmed', 1)->get()->toArray();
		return View::make('map.tasting_planner', compact('vineyards'));
	}

	public function vineyard_details($id)
	{
		$vineyard = Vineyard::find($id);
		$wines = Wine::where('vineyard_id', $id)->with('users')->get();

		return ['status' => 'success', 'title' => $vineyard->name, 'body' => View::make('map.vineyard_details', compact('vineyard', 'wines'))->render()];
	}
}