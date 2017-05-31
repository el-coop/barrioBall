<?php

namespace App\Http\Controllers\Match;

use App\Http\Requests\Match\CreateMatchRequest;
use App\Http\Requests\Match\DeleteMatchRequest;
use App\Http\Requests\Match\JoinMatchRequest;
use App\Http\Requests\Match\leaveMatchRequest;
use App\Http\Requests\Match\SearchRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Match;

class MatchController extends Controller
{
	public function showCreate(Request $request) {
		$user = $request->user();
		return view('match.create', compact('user'));
	}

	public function create(CreateMatchRequest $request) {
		$match = $request->commit($request->user());
		return redirect()->action('Match\PagesController@welcome');
	}

	public function showMatch(Request $request, Match $match){
		$user = $request->user();
		$canJoin = true;
		if(! $user || $match->registeredPlayers->count() == $match->players || $user->inMatch($match)){
			$canJoin = false;
		}

		return view('match.show', compact('match','canJoin', 'user'));
	}

	public function delete(DeleteMatchRequest $request, Match $match) {
		$request->commit();
		return redirect('/');
	}

	public function showSearch(Request $request) {
		$user = $request->user();
		return view('match.search',compact('user'));
	}

    public function search(SearchRequest $request){
		return $request->commit();
	}
}
