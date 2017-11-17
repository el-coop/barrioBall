<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Match extends Model
{

    protected $appends = ['url'];
    protected $dates = [
        'created_at',
        'updated_at',
        'date_time'
    ];

    /**
     *
     * @param string $value
     *
     * @return string
     */
    public function getDateAttribute(): string
    {
        return $this->date_time->format('d/m/y');
    }


    /**
     * @param string $value
     *
     * @return string
     */
    public function getTimeAttribute(): string
    {
        return $this->date_time->format('H:i');
    }

    /**
     * @param User $user
     */
    public function addManager(User $user): void
    {
        $this->addUser($user, true);
    }

    /**
     * @param User $user
     * @param bool $manager
     */
    public function addUser(User $user, bool $manager = false): void
    {
        $this->users()->attach($user, [
            'role' => $manager ? 'manager' : 'player',
        ]);
    }

    /**
     * @return BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot('role');
    }

    /**
     * @param User $user
     */
    public function removeManager(User $user): void
    {
        $this->managers()->detach($user);
    }

    /**
     * @return BelongsToMany
     */
    public function managers(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->wherePivot('role', 'manager');
    }

    /**
     * @param User $user
     */
    public function addPlayer(User $user): void
    {
        $this->addUser($user, false);
    }

    /**
     * @param User $user
     */
    public function removePlayer(User $user): void
    {
        $this->registeredPlayers()->detach($user);
    }

    /**
     * @return BelongsToMany
     */
    public function registeredPlayers(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->wherePivot('role', 'player');
    }

    /**
     * @return string
     */
    public function getUrlAttribute(): string
    {
        return action('Match\MatchController@showMatch', $this);
    }

    /**
     * @param User $user
     *
     * @return bool
     */
    public function hasJoinRequest(User $user): bool
    {
        return !! $this->joinRequests->where('id', $user->id)->count();
    }

    /**
     * @return BelongsToMany
     */
    public function joinRequests(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'join_match_requests')
            ->withTimestamps();
    }

    /**
     * @param User $user
     *
     * @return bool
     */
    public function hasPlayer(User $user): bool
    {
        return !! $this->registeredPlayers->where('id', $user->id)->count();
    }

	/**
	 * @param User $user
	 *
	 * @return bool
	 */
	public function hasManager(User $user): bool
	{
		return !! $this->managers->where('id', $user->id)->count();
	}

    /**
     * @param User $user
     */
    public function addJoinRequest(User $user): void
    {
        $this->joinRequests()->save($user);
    }

    /**
     * @param User $user
     */
    public function cancelJoinRequest(User $user): void
    {
        $this->joinRequests()->detach($user);
    }

    /**
     * @return bool
     */
    public function isFull(): bool
    {
        return $this->registeredPlayers->count() >= $this->players;
    }


    /**
     * @return bool
     */
    public function ended(): bool
    {
        return Carbon::now() > $this->date_time;
    }
}
