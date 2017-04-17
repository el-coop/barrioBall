<?php

namespace App\Http\Controllers\Match;

use App\Http\Requests\Match\CreateMatchRequest;
use App\Http\Requests\Match\SearchRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Match;

class MatchController extends Controller
{
    public function create(CreateMatchRequest $request) {
        $match = $request->commit();
        return redirect()->action('Match\PagesController@welcome');
    }


	public function showSearch() {
		return view('match.search');
	}

    public function search(SearchRequest $request){
		return $request->commit();
	}
}
