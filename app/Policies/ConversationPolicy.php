<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Conversation;
use Cache;
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
		return Cache::rememberForever(sha1("{$user->id}_participates_{$conversation->id}"),function() use ($conversation, $user){
			return $conversation->users()->where('users.id', $user->id)->exists();
		});
	}


}
