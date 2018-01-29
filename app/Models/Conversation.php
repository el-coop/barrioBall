<?php

namespace App\Models;

use Auth;
use Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Conversation extends Model {

	/**
	 * @param Message $message
	 */
	public function addMessage(Message $message): void {
		$this->messages()->save($message);
		$this->touch();
		$this->users->filter(function ($value) use($message){
			return $value->id != $message->user_id;
		})->each(function ($notified) {
			$this->markAsUnread($notified);
			Cache::forget(sha1("{$notified->id}_unread_count"));
		});
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function messages(): HasMany {
		return $this->hasMany(Message::class);
	}

	/**
	 * @param User $user
	 *
	 * @return int
	 */
	public function markAsUnread(User $user = null): int {
		if (!$user) {
			$user = Auth::user();
		}

		return $this->users()->updateExistingPivot($user->id, ['read' => false]);
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function users(): BelongsToMany {
		return $this->belongsToMany(User::class)->withPivot('read');
	}

	/**
	 * @param User $user
	 *
	 * @return int
	 */
	public function markAsRead(User $user = null): int {
		if (!$user) {
			$user = Auth::user();
		}
		Cache::forget(sha1("{$user->id}_unread_count"));

		return $this->users()->updateExistingPivot($user->id, ['read' => true]);
	}

}
