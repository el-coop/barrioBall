<?php

namespace App\Http\Requests\Match;

use App\Events\Match\MatchDeleted;
use Illuminate\Foundation\Http\FormRequest;

class DeleteMatchRequest extends FormRequest {
	protected $match;

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize(): bool {
		$this->match = $this->route('match');
		if ($this->user() && $this->user()->isManager($this->match)) {
			return true;
		}

		return false;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules(): array {
		return [
			//
		];
	}

	/**
	 *
	 */
	public function commit(): void {
		event(new MatchDeleted($this->user(), $this->match));
		$this->match->delete();
	}
}
