<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
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

    public function user()
    {
        return $this->morphTo();
    }

    public function matches() {

        return $this->belongsToMany(Match::class);
    }

	public function managedMatches() {
		return $this->belongsToMany(Match::class)
			->wherePivot('role','manager');
	}

	public function inMatch(Match $match){
		return $match->registeredPlayers->contains($this);
	}

	public function isManager(Match $match){
		return $match->managers->contains($this);
	}
}
