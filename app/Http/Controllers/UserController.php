<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\DeleteUserRequest;
use App\Http\Requests\User\UpdateEmailRequest;
use App\Http\Requests\User\UpdateLanguageRequest;
use App\Http\Requests\User\UpdatePasswordRequest;
use App\Http\Requests\User\UpdateUsernameRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller {

	public function show(Request $request) {
		return view('user.profile');
	}

	public function updateUsername(UpdateUsernameRequest $request) {
		$request->commit();
		$message = __('profile/page.updatedUsername');

		return back()->with('alert', $message);
	}

	public function updatePassword(UpdatePasswordRequest $request) {

		$request->commit();
		$message = __('profile/page.updatedPassword');

		return back()->with('alert', $message);
	}

	public function updateEmail(UpdateEmailRequest $request) {

		$request->commit();
		$message = __('profile/page.updatedEmail');

		return back()->with('alert', $message);
	}

	public function updateLanguage(UpdateLanguageRequest $request) {

		$request->commit();
		$message = __('profile/page.updatedLanguage');

		return back()->with('alert', $message);
	}

	public function deleteUser(DeleteUserRequest $request) {
		$request->commit();

		return redirect('/home');
	}

	public function getMatches(Request $request){
		if($request->has('managed')){
			$matches = $request->user()->managedMatches()->withCount('joinRequests');
		} else {
			$matches = $request->user()->playedMatches();
		}

		if ($request->filled('sort')) {
			$sort = explode('|', $request->input('sort'));
			$matches = $matches->orderBy($sort[0], $sort[1]);

		} else {
			$matches = $matches->latest();
		}

		if ($request->filled('filter')) {
			$filterVal = "%{$request->input('filter')}%";
			$matches->where('name','like',$filterVal);
		}

		return $matches->paginate($request->input('per_page'));

	}
}
