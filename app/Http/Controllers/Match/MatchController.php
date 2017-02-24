<?php

namespace App\Http\Controllers\Match;

use App\Http\Requests\Match\CreateMatchRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Match;

class MatchController extends Controller
{
    public function create(CreateMatchRequest $request) {
        $match = $request->commit();
        return redirect()->action('Match\PagesController@welcome');
    }
}
