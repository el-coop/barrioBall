<?php

namespace App\Http\Requests\Match;

use App\Events\Match\PlayerRemoved;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class RemovePlayerRequest extends FormRequest {

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
			'user' => 'required|numeric|exists:users,id',
			'message' => 'string|max:500|nullable',
		];
	}

	/**
	 *
	 */
	public function commit(): void {
		$user = User::find($this->input('user'));
		$this->match->removePlayer($user);

		event(new PlayerRemoved($user, $this->match, $this->input('message')));
	}
}
