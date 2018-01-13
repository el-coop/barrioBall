<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model {

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function messages() {
		return $this->hasMany(Message::class);
	}

	/**
	 * @param User $user
	 *
	 * @return int
	 */
	public function markAsRead(User $user = null): int {
		if(! $user){
			$user = Auth::user();
		}
		return $this->users()->updateExistingPivot($user->id, ['read' => true]);
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function users() {
		return $this->belongsToMany(User::class)->withPivot('read');
	}

	/**
	 * @param User $user
	 *
	 * @return int
	 */
	public function markAsUnread(User $user = null): int {
		if(! $user){
			$user = Auth::user();
		}
		return $this->users()->updateExistingPivot($user->id, ['read' => false]);
	}
}
