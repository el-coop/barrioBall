<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Errors\Error;
use Illuminate\Auth\Access\HandlesAuthorization;

class ErrorPolicy {
	use HandlesAuthorization;

	/**
	 * Determine whether the user can view the error.
	 *
	 * @param  \App\Models\User $user
	 * @param  \App\Models\Errors\Error $error
	 *
	 * @return bool
	 */
	public function view(User $user, Error $error): bool {
		return $user->isAdmin();
	}

	/**
	 * Determine whether the user can delete the error.
	 *
	 * @param  \App\Models\User $user
	 * @param  \App\Models\Errors\Error $error
	 *
	 * @return bool
	 */
	public function delete(User $user, Error $error): bool {
		return $user->isAdmin();
	}
}
