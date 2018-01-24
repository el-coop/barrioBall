<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model {
	protected $dates = [
		'created_at',
		'updated_at',
	];

	/**
	 * @return string
	 */
	public function getDateAttribute(): string {
		return $this->created_at->format('d/m/y');
	}


	/**
	 * @return string
	 */
	public function getTimeAttribute(): string {
		return $this->created_at->format('H:i');
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function conversation(): BelongsTo {
		return $this->belongsTo(Conversation::class);
	}

	/**
	 * @return BelongsTo
	 */
	public function user(): BelongsTo {
		return $this->belongsTo(User::class);
	}
}
