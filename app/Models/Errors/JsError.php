<?php

namespace App\Models\Errors;

use Illuminate\Database\Eloquent\Model;

class JsError extends Model
{
	public function error(){
		return $this->morphOne(Error::class, 'errorable');
	}
}
