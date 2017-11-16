<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class HomeController extends Controller
{

	/**
	 * @return View
	 */
	public function index(Request $request): View
	{
		$nextMatch = $request->user()->playedMatches()->orderBy('date_time')->first();
		$requestsCount = $request->user()->managedMatches()->withCount('joinRequests')->get()->sum->join_requests_count;
		return view('dashboard.dashboard',compact('nextMatch','requestsCount'));
	}

	/**
	 * @return View
	 */
	public function welcome(): View
	{
		return view('welcome.welcome');
	}
	
}
