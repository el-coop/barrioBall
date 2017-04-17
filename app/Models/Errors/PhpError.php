<?php

namespace App\Models\Errors;

use Illuminate\Database\Eloquent\Model;

class PhpError extends Model
{
	public $errorSort = 'id';
	public $sortDir = 'asc';

	protected $casts = [
		'exception' => 'array',
		'request' => 'array',
	];

    public function error(){
    	return $this->morphOne(Error::class, 'errorable')->orderBy($this->errorSort,$this->sortDir);
	}

	public function delete() {
		$this->error->delete();
		return parent::delete(); // TODO: Change the autogenerated stub
	}
}
