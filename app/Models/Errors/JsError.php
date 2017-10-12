<?php

namespace App\Models\Errors;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class JsError extends Model {
	protected $casts = [
		'exception' => 'array',
		'vm' => 'array',
	];

	/**
	 * @return MorphOne
	 */
	public function error(): MorphOne {
		return $this->morphOne(Error::class, 'errorable');
	}

	/**
	 * @return bool|null
	 */
	public function delete(): ?bool {
		$this->error->delete();

		return parent::delete();
	}
}
