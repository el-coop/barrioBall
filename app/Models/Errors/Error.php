<?php

namespace App\Models\Errors;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Error extends Model
{

	public function user(){
		return $this->belongsTo(User::class);
	}

	public function errorable()
	{
		return $this->morphTo();
	}

}
