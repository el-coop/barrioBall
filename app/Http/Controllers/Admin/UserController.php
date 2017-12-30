<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\AddAdminRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller {

	/**
	 * @param AddAdminRequest $request
	 * @param User $user
	 *
	 * @return Redirect
	 */
	public function addAdmin(AddAdminRequest $request, User $user): RedirectResponse {
		$request->commit();

		return back()->with('alert', __('global.success'));
	}
}
