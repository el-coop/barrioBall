<?php

namespace App\Http\Controllers\Match;

use App\Http\Requests\Match\CreateMatchRequest;
use App\Http\Requests\Match\SearchRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Match;

class MatchController extends Controller
{
	public function showCreate() {
		return view('match.create');
	}

    public function create(CreateMatchRequest $request) {
        $match = $request->commit($request->user());
        return redirect()->action('Match\PagesController@welcome');
    }

	public function showMatch(Match $match){
		return view('match.show', compact('match'));
	}

	public function showSearch() {
		return view('match.search');
	}

    public function search(SearchRequest $request){
		return $request->commit();
	}
}
