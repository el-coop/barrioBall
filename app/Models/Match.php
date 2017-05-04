<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Match extends Model
{

	protected $appends = ['url'];

	public function getDateAttribute($value){
		$date = new Carbon($value);
		return $date->format('d/m/y');
	}


	public function getTimeAttribute($value){
		$date = new Carbon($value);
		return $date->format('H:i');
	}

	public function users() {
		return $this->belongsToMany(User::class)
			->withPivot('role');
	}

	public function addUser(User $user, bool $manager = false){
		$this->users()->attach($user,[
			'role' => $manager ? 'manager' : 'player'
		]);

	}

	public function addManager(User $user){
		$this->addUser($user,true);
	}

	public function managers() {
		return $this->belongsToMany(User::class)
			->wherePivot('role','manager');
	}

    public function registeredPlayers(){
		return $this->belongsToMany(User::class)
			->wherePivot('role','player');
	}

    public function getUrlAttribute(){
		return action('Match\MatchController@showMatch', $this);
	}
}
