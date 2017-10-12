<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class HomeController extends Controller
{

	/**
	 * @return View
	 */
	public function index(): View
	{
		return view('home');
	}

	/**
	 * @return View
	 */
	public function welcome(): View
	{
		return view('welcome.welcome');
	}
	
}
