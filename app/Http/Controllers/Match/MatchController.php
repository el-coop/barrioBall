<?php

namespace App\Http\Controllers\Match;

use App\Http\Requests\Match\CreateMatchRequest;
use App\Http\Requests\Match\DeleteMatchRequest;
use App\Http\Requests\Match\EditMatchRequest;
use App\Http\Requests\Match\RepeatMatchRequest;
use App\Http\Requests\Match\SearchRequest;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Match;

class MatchController extends Controller {

	/**
	 * @return View
	 */
	public function showCreate(): View {
		return view('match.create');
	}

	/**
	 * @param CreateMatchRequest $request
	 *
	 * @return RedirectResponse
	 */
	public function create(CreateMatchRequest $request): RedirectResponse {
		$match = $request->commit();

		return redirect()->action('Match\MatchController@showMatch', $match);
	}

	/**
	 * @param Request $request
	 * @param Match $match
	 *
	 * @return View
	 */
	public function showMatch(Match $match): View {

		$joinRequests = \Cache::rememberForever(sha1("{$match->id}_joinRequests"), function () use ($match) {
			return $match->joinRequests;
		});
		$managers = \Cache::rememberForever(sha1("{$match->id}_managers"), function () use ($match) {
			return $match->managers;
		});
		$registeredPlayers = \Cache::rememberForever(sha1("{$match->id}_registeredPlayers"), function () use ($match) {
			return $match->registeredPlayers;
		});

		return view('match.show', compact('match', 'managers', 'joinRequests', 'registeredPlayers'));
	}

	/**
	 * @param Request $request
	 * @param Match $match
	 *
	 * @return View
	 */
	public function showEditForm(Match $match): View {

		return view('match.create', compact('match'));
	}


	/**
	 * @param EditMatchRequest $request
	 * @param Match $match
	 *
	 * @return RedirectResponse
	 */
	public function edit(EditMatchRequest $request, Match $match): RedirectResponse {

		$request->commit();

		return redirect()->action('Match\MatchController@showMatch',$match)->with('alert',__('global.success'));
	}

	/**
	 * @param DeleteMatchRequest $request
	 * @param Match $match
	 *
	 * @return RedirectResponse
	 */
	public function delete(DeleteMatchRequest $request, Match $match): RedirectResponse {
		$request->commit();

		return redirect()->action('HomeController@index');
	}

	/**
	 * @param Request $request
	 *
	 * @return View
	 */
	public function showSearch(Request $request): View {
		return view('match.search');
	}

	/**
	 * @param SearchRequest $request
	 *
	 * @return LengthAwarePaginator
	 */
	public function search(SearchRequest $request): LengthAwarePaginator {
		return $request->commit();
	}

	/**
	 * @param RepeatMatchRequest $request
	 *
	 * @return RedirectResponse
	 */
	public function repeatMatch(RepeatMatchRequest $request, Match $match): RedirectResponse {
		$request->commit();

		return back()->with('alert', __('global.success'));
	}
}
