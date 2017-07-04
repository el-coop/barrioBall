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

        $user = $request->commit();
        $message = __('profile/update.updatedEmail');
        return back()->with('alert', $message);
    }

	public function updateLanguage(UpdateLanguageRequest $request) {

        $user = $request->commit();
        $message = __('profile/update.updatedLanguage');
        return back()->with('alert', $message);
    }

	public function destroy() {
		$user = Auth::user();
		$user->delete();

		return redirect('/home');
	}
}
