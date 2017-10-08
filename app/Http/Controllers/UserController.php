<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\DeleteUserRequest;
use App\Http\Requests\User\UpdateEmailRequest;
use App\Http\Requests\User\UpdateLanguageRequest;
use App\Http\Requests\User\UpdatePasswordRequest;
use App\Http\Requests\User\UpdateUsernameRequest;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;


class UserController extends Controller {

	/**
	 * @param Request $request
	 *
	 * @return View
	 */
	public function show(Request $request): View {
		return view('user.profile');
	}

	/**
	 * @param UpdateUsernameRequest $request
	 *
	 * @return RedirectResponse
	 */
	public function updateUsername(UpdateUsernameRequest $request): RedirectResponse {
		$request->commit();
		$message = __('profile/page.updatedUsername');

		return back()->with('alert', $message);
	}

	/**
	 * @param UpdatePasswordRequest $request
	 *
	 * @return RedirectResponse
	 */
	public function updatePassword(UpdatePasswordRequest $request): RedirectResponse {

		$request->commit();
		$message = __('profile/page.updatedPassword');

		return back()->with('alert', $message);
	}

	/**
	 * @param UpdateEmailRequest $request
	 *
	 * @return RedirectResponse
	 */
	public function updateEmail(UpdateEmailRequest $request): RedirectResponse {

		$request->commit();
		$message = __('profile/page.updatedEmail');

		return back()->with('alert', $message);
	}

	/**
	 * @param UpdateLanguageRequest $request
	 *
	 * @return RedirectResponse
	 */
	public function updateLanguage(UpdateLanguageRequest $request): RedirectResponse {

		$request->commit();
		$message = __('profile/page.updatedLanguage',[], $request->get('language'));

		return back()->with('alert', $message);
	}

	/**
	 * @param DeleteUserRequest $request
	 *
	 * @return RedirectResponse
	 */
	public function deleteUser(DeleteUserRequest $request): RedirectResponse {
		$request->commit();

		return redirect('/home');
	}

	/**
	 * @param Request $request
	 *
	 * @return LengthAwarePaginator
	 */
	public function getMatches(Request $request): LengthAwarePaginator{
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
