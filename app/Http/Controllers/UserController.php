<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateEmailRequest;
use App\Http\Requests\UpdateLanguageRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UpdateUsernameRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller {

	public function edit(Request $request) {
		return view('user.profile');
	}


	public function updateUsername(UpdateUsernameRequest $request) {
		$user = $request->commit($request->user());
		$message = __('profile/update.updatedUsername');

		return back()->with('alert', $message);
	}

	public function updatePassword(UpdatePasswordRequest $request) {


		$user = $request->commit($request->user());
		$message = __('profile/update.updatedPassword');

		return back()->with('alert', $message);
	}

	public function updateEmail(UpdateEmailRequest $request) {

		$user = $request->commit($request->user());
		$message = __('profile/update.updatedEmail');

		return back()->with('alert', $message);
	}

	public function updateLanguage(UpdateLanguageRequest $request) {

		$user = $request->commit($request->user());
		$message = __('profile/update.updatedLanguage');

		return back()->with('alert', $message);
	}

	public function destroy() {
		$user = Auth::user();
		$user->delete();

		return redirect('/home');
	}

	public function getMatches(Request $request) {
		$matches = $request->user()->matches()->withPivot('role');

		if ($request->filled('sort')) {
			$matches = $matches->orderBy($request->input('sort'));
		} else {
			$matches = $matches->latest();
		}

		if ($request->filled('filter')) {
			$filterVal = "%{$request->input('filter')}%";
			$matches->where(function ($query) use ($filterVal) {
				$query->where('name', 'like', $filterVal);
			})->orWherePivot('role', 'like', $filterVal);
		}

		return $matches->paginate($request->input('per_page'));
	}
}
