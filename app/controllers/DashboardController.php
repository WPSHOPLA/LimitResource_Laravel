<?php

class DashboardController extends Controller
{
	public function index()
	{
		if(Auth::user()->role == 1){
			$stats = [
				'wines'		=> Wine::where('confirmed', 1)->count(),
				'vineyards'	=> Vineyard::where('confirmed', 1)->count(),
				'cities'	=> count(DB::select('SELECT DISTINCT(city) FROM vineyards WHERE city != "";')),
				'countries'	=> count(DB::select('SELECT DISTINCT(country) FROM vineyards WHERE country != "";')),
			];
		} else {
			$stats = [
				'wines'		=> Auth::user()->wines()->where('confirmed', 1)->count(),
				'vineyards'	=> Vineyard::where('user_id', Auth::user()->id)->where('confirmed', 1)->count(),
				'cities'	=> count(DB::select('SELECT DISTINCT(city) FROM vineyards WHERE city != "" AND user_id = '.Auth::user()->id.';')),
				'countries'	=> count(DB::select('SELECT DISTINCT(country) FROM vineyards WHERE country != "" AND user_id = '.Auth::user()->id.';')),
			];
		}

		return View::make('dashboard.index', compact('stats'));
	}
}