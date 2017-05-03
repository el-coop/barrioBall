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
        return $this->belongsToMany('App\Models\User')
            ->withPivot('role');
    }

    public function registeredPlayers(){
		return $this->belongsToMany('App\Models\User')
			->wherePivot('role','player');
	}

    public function getUrlAttribute(){
		return action('Match\MatchController@showMatch', $this);
	}
}
