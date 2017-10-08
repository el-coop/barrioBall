<?php

namespace App\Models\Errors;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class PhpError extends Model {
	public $errorSort = 'id';
	public $sortDir = 'asc';

	protected $casts = [
		'exception' => 'array',
		'request' => 'array',
	];

	/**
	 * @return MorphOne
	 */
	public function error(): MorphOne {
		return $this->morphOne(Error::class, 'errorable')->orderBy($this->errorSort, $this->sortDir);
	}

	/**
	 * @return bool|null
	 */
	public function delete(): ?bool {
		$this->error->delete();

		return parent::delete();
	}
}
