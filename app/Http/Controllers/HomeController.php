<?php

namespace App\Http\Controllers;

use App\Http\Requests\Misc\ContactRequest;
use Cache;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class HomeController extends Controller {

	/**
	 * @return View
	 */
	public function index(Request $request): View {

		$nextMatch = Cache::remember(sha1("{$request->user()->username}_nextMatch"), 5, function () use ($request) {
			return $request->user()->playedMatches()->where('date_time', '>', Carbon::today('Pacific/Pago_Pago'))->orderBy('date_time')->first();
		});
		$requestsCount = Cache::remember(sha1("{$request->user()->username}_requests"), 5, function () use ($request) {
			return $request->user()->managedMatches()->where('date_time', '>', Carbon::today('Pacific/Pago_Pago'))->withCount('joinRequests')->get()->sum->join_requests_count;
		});

		return view('dashboard.dashboard', compact('nextMatch', 'requestsCount'));
	}

	/**
	 * @return View
	 */
	public function welcome(): View {
		return view('welcome.welcome');
	}

	/**
	 * @return View
	 */
	public function tech(): View {
		return view('tech.tech');
	}


	/**
	 * @return View
	 */
	public function showContactUs(): View {
		return view('contact.contact');
	}

	/**
	 * @return View
	 */
	public function contactUs(ContactRequest $request): RedirectResponse {
		$request->commit();
		return back()->with('alert',__('global.success'));
	}

}
