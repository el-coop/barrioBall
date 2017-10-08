<?php

namespace App\Models\Errors;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Error extends Model {

	/**
	 * @return BelongsTo
	 */
	public function user(): BelongsTo {
		return $this->belongsTo(User::class);
	}

	/**
	 * @return MorphTo
	 */
	public function errorable(): MorphTo {
		return $this->morphTo();
	}

}
