<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Match extends Model
{

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
}
