<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Conversation;
use Illuminate\Auth\Access\HandlesAuthorization;

class ConversationPolicy {
	use HandlesAuthorization;

	/**
	 * Determine whether the user can update the conversation.
	 *
	 * @param  \App\Models\User $user
	 * @param  \App\Models\Conversation $conversation
	 *
	 * @return bool
	 */
	public function update(User $user, Conversation $conversation): bool {
		return $conversation->users()->where('users.id', $user->id)->exists();
	}


}
