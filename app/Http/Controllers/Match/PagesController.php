<?php

namespace App\Http\Controllers\Match;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PagesController extends Controller
{
    public function welcome() {
        return(view('match.createMatch'));
    }
}
