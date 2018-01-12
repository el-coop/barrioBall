<?php

namespace App\Models;

use App\Notifications\User\ResetPassword;
use Cache;
use Exception;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
		if ($this->isManager($match) && $match->isFull()) {
			return false;
		}

		return !$this->inMatch($match) && !$this->sentRequest($match);
	}

	/**
	 * @param Match $match
	 *
	 * @return bool
	 */
	public function isManager(Match $match): bool {
		return Cache::rememberForever(sha1("{$this->id}_{$match->id}_manager"), function () use ($match) {
			return $this->managedMatches()->where('id', $match->id)->exists();
		});
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
	 * @param Match $match
	 *
	 * @return bool
	 */
	public function inMatch(Match $match): bool {

		return Cache::rememberForever(sha1("{$this->id}_{$match->id}_player"), function () use ($match) {
			return $this->playedMatches()->where('id', $match->id)->exists();
		});
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
		return Cache::rememberForever(sha1("{$this->id}_{$match->id}_joinRequest"), function () use ($match) {
			return $this->joinRequests()->where('id', $match->id)->exists();
		});
	}

	/**
	 * @return BelongsToMany
	 */
	public function joinRequests(): BelongsToMany {
		return $this->belongsToMany(Match::class, 'join_match_requests')
			->withTimestamps();
	}

	/**
	 * @param Match $match
	 */
	public function manageInvite(Match $match): void {
		$match->inviteManager($this);
	}

	/**
	 * @return BelongsToMany
	 */
	public function manageInvites(): BelongsToMany {
		return $this->belongsToMany(Match::class, 'manager_invites')
			->withTimestamps();
	}

	/**
	 * @param Match $match
	 *
	 * @return bool
	 */
	public function hasManageInvite(Match $match): bool {
		return Cache::rememberForever(sha1("{$this->id}_{$match->id}_managerInvitation"), function () use ($match) {
			return $this->manageInvites()->where('id', $match->id)->exists();
		});
	}

	/**
	 * @return bool|null
	 */
	public function delete(): ?bool {
		$this->user->delete();

		return parent::delete();
	}

	/**
	 * @throws Exception
	 */
	public function makeAdmin(): void {
		if($this->user_type == 'Player'){
			$oldUser = $this->user;
			$admin = new Admin;
			$admin->save();
			$admin->user()->save($this);
			$oldUser->delete();
		} else {
			throw new Exception('Already Admin');
		}
	}

    /**
     * @return BelongsToMany
     */
    public function conversations() : BelongsToMany{
	    return $this->belongsToMany(Conversation::class)->withPivot('read');
    }


    /**
     * @return HasMany
     */
    public function messages() : HasMany{
	    return $this->hasMany(Message::class);
    }


	/**
	 * @param User $user
	 *
	 * @return Conversation|null
	 */
    public function getConversationWith(User $user): ?Conversation{
        $conversations = $this->conversations()->with('users')->get();
        return $conversations->first(function($value) use ($user) {
            return $value->users->contains(function ($value) use ($user) {
                return $value->id == $user->id;
            });
        });
    }

	/**
	 * @return bool
	 */
	public function hasUnreadConversations(): bool{
		return $this->conversations()->wherePivot('read', '=', false)->exists();
	}

	/**
	 * @return int
	 */
	public function countUnreadConversations(): int{
		return $this->conversations()->wherePivot('read', '=', false)->count();
	}
}
