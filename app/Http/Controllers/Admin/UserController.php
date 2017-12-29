<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\AddAdminRequest;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;

class UserController extends Controller {

	/**
	 * @param AddAdminRequest $request
	 * @param User $user
	 *
	 * @return Redirect
	 */
	public function addAdmin(AddAdminRequest $request, User $user): Redirect {
		$request->commit();

		return back()->with('alert', __('global.success'));
	}
}
