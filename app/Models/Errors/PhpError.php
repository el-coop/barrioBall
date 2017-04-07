<?php

namespace App\Models\Errors;

use App\Models\Error;
use Illuminate\Database\Eloquent\Model;

class PhpError extends Model
{
	protected $casts = [
		'exception' => 'array',
		'request' => 'array',
	];

    public function error(){
    	return $this->morphOne(Error::class, 'errorable');
	}
}
