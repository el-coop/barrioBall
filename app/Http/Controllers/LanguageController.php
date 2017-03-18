<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Redirect;

class LanguageController extends Controller {
	/**
	 * @param $lang
	 *
	 * @return mixed
	 */
	public function switchLang(Request $request, $lang) {
		if (array_key_exists($lang, config('languages'))) {
			$request->session()->put('appLocale', $lang);
		}

		return back();
	}
}

