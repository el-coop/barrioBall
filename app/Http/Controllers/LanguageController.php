<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LanguageController extends Controller {
	/**
	 * @param Request $request
	 * @param $lang
	 *
	 * @return RedirectResponse
	 */
	public function switchLang(Request $request, $lang): RedirectResponse {
		if (array_key_exists($lang, config('languages'))) {
			$request->session()->put('appLocale', $lang);
		}

		return back();
	}
}

