<?php

namespace App\Http\Controllers\Match;

use App\Http\Requests\Match\CreateMatchRequest;
use App\Http\Requests\Match\DeleteMatchRequest;
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
	public function showMatch(Request $request, Match $match): View {

		return view('match.show', compact('match'));
	}

	/**
	 * @param DeleteMatchRequest $request
	 * @param Match $match
	 *
	 * @return RedirectResponse
	 */
	public function delete(DeleteMatchRequest $request, Match $match): RedirectResponse {
		$request->commit();

		return redirect('/');
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
}
