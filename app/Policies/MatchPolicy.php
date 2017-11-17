<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Match;
use Illuminate\Auth\Access\HandlesAuthorization;

class MatchPolicy {
	use HandlesAuthorization;

	/**
	 * Determine whether the user can create matches.
	 *
	 * @param  \App\Models\User $user
	 *
	 * @return bool
	 */
	public function create(User $user): bool {
		return !!$user;
	}

	/**
	 * Determine whether the user can update the match.
	 *
	 * @param  \App\Models\User $user
	 * @param Match $match
	 *
	 * @return bool
	 */
	public function update(User $user, Match $match): bool {
		return $user->isManager($match);
	}

	/**
	 * Determine whether the user can delete the match.
	 *
	 * @param  \App\Models\User $user
	 *
	 * @param Match $match
	 *
	 * @return bool
	 */
	public function delete(User $user, Match $match): bool {
		return $user->isManager($match);
	}

	/**
	 * @param User $user
	 * @param Match $match
	 *
	 * @return bool
	 */
	public function manage(User $user, Match $match): bool {
		return $match->hasManager($user);
	}


	/**
	 * @param User $user
	 * @param Match $match
	 *
	 * @return bool
	 */
	public function leave(User $user, Match $match): bool {
		return $user->inMatch($match);
	}

	/**
	 * @param User $user
	 * @param Match $match
	 *
	 * @return bool
	 */
	public function cancelRequest(User $user, Match $match): bool {
		return $user->sentRequest($match);
	}

	/**
	 * @param User $user
	 * @param Match $match
	 *
	 * @return bool
	 */
	public function join(User $user, Match $match): bool {
		return !!$user;
	}
}
