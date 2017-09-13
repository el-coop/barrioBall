<?php

namespace App\Models;

use App\Notifications\User\ResetPassword;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable {
	use Notifiable;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'language', 'email', 'password',
	];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'password', 'remember_token',
	];

	public function user() {
		return $this->morphTo();
	}

	public function inMatch(Match $match) {
		return !!$this->playedMatches()->where('id', $match->id)->count();
	}

	public function playedMatches() {
		return $this->matches()->wherePivot('role', 'player');
	}

	public function matches() {

		return $this->belongsToMany(Match::class);
	}

	public function isManager(Match $match) {
		return !!$this->managedMatches()->where('id', $match->id)->count();
	}

	public function managedMatches() {
		return $this->matches()->wherePivot('role', 'manager');
	}

	public function isAdmin() {
		return $this->user_type == "Admin";
	}

	public function sentRequest(Match $match) {
		return !!$this->joinRequests()->where('id', $match->id)->count();
	}

	public function joinRequests() {
		return $this->belongsToMany(Match::class, 'join_match_requests')
			->withTimestamps();
	}

	public function sendPasswordResetNotification($token) {
		$this->notify(new ResetPassword($token, $this));
	}
}
