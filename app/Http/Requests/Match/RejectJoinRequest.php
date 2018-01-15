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

		return $this->user()->can('update', $this->match);
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
			if (!$this->user->sentRequest($this->match)) {
				$validator->errors()->add('request', __('match/requests.requestNotExistent'));
			}
			if ($this->match->ended()) {
				$validator->errors()->add('ended', __('match/requests.ended'));
			}
		});
	}

	public function commit():void {
		$this->match->joinRequests()->detach($this->user);
		event(new PlayerRejected($this->match, $this->user(), $this->user, $this->input('message')));
	}
}
