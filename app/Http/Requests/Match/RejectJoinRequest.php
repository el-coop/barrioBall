<?php

namespace App\Http\Requests\Match;

use App\Events\Match\PlayerRejected;
use App\Models\User;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class RejectJoinRequest extends FormRequest {
	protected $match;

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize(): bool {
		$this->match = $this->route('match');
		if ($this->user() && $this->user()->isManager($this->match) && ! $this->match->ended()) {
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


	public function withValidator(Validator $validator): void {
		$this->user = User::find($this->input('user'));
		$validator->after(function ($validator) {
			if (!$this->match->hasJoinRequest($this->user)) {
				$validator->errors()->add('request', __('match/requests.requestNotExistent'));
			}
		});
	}

	public function commit():void {
		$this->match->joinRequests()->detach($this->user);
		event(new PlayerRejected($this->match, $this->user, $this->input('message')));
	}
}
