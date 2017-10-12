<?php

namespace App\Models;

use App\Notifications\User\ResetPassword;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
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

	/**
	 * @return MorphTo
	 */
	public function user(): MorphTo {
		return $this->morphTo();
	}

	/**
	 * @param Match $match
	 *
	 * @return bool
	 */
	public function isManager(Match $match): bool {
		return $this->managedMatches()->exists($match);
	}

	/**
	 * @return BelongsToMany
	 */
	public function managedMatches(): BelongsToMany {
		return $this->matches()->wherePivot('role', 'manager');
	}

	/**
	 * @return BelongsToMany
	 */
	public function matches(): BelongsToMany {
		return $this->belongsToMany(Match::class);
	}

	/**
	 * @return bool
	 */
	public function isAdmin(): bool {
		return $this->user_type == "Admin";
	}

	/**
	 * @param string $token
	 */
	public function sendPasswordResetNotification($token): void {
		$this->notify(new ResetPassword($token, $this));
	}

	/**
	 * @param Match $match
	 *
	 * @return bool
	 */
	public function canJoin(Match $match): bool {
		return !$this->inMatch($match) && !$this->sentRequest($match) && !$match->isFull();
	}

	/**
	 * @param Match $match
	 *
	 * @return bool
	 */
	public function inMatch(Match $match): bool {
		return $this->playedMatches()->exists($match);
	}

	/**
	 * @return BelongsToMany
	 */
	public function playedMatches(): BelongsToMany {
		return $this->matches()->wherePivot('role', 'player');
	}

	/**
	 * @param Match $match
	 *
	 * @return bool
	 */
	public function sentRequest(Match $match): bool {
		return $this->joinRequests()->exists($match);
	}

	/**
	 * @return BelongsToMany
	 */
	public function joinRequests(): BelongsToMany {
		return $this->belongsToMany(Match::class, 'join_match_requests')
			->withTimestamps();
	}

	/**
	 * @return bool|null
	 */
	public function delete(): ?bool {
		$this->user->delete();

		return parent::delete();
	}
}
