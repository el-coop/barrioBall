<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Conversation extends Model {

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function messages(): HasMany {
		return $this->hasMany(Message::class);
	}


	/**
	 * @param Message $message
	 */
	public function addMessage(Message $message): void {
		$this->messages()->save($message);
		$this->touch();
		$this->users()->where('users.id','!=',$message->user_id)->get()
			->each(function ($notified){
				$this->markAsUnread($notified);
			});
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
	public function users(): BelongsToMany {
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
