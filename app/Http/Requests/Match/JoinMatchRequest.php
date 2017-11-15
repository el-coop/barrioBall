<?php

namespace App\Http\Requests\Match;

use App\Events\Match\JoinRequestSent;
use App\Events\Match\PlayerJoined;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class JoinMatchRequest extends FormRequest {
	protected $match;

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize(): bool {
		$this->match = $this->route('match');

		return $this->user()->can('join',$this->match);
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules(): array {
		return [
			'message' => 'string|max:500|nullable',
		];
	}

	/**
	 * @return string
	 */
	public function commit(): string {
		if ($this->user()->isManager($this->match)) {
			$this->match->addPlayer($this->user());
			$message = __('match/show.joined');
			event(new PlayerJoined($this->match, $this->user()));
		} else {
			$this->match->joinRequests()->save($this->user());
			$message = __('match/show.joinMatchSent');
			event(new JoinRequestSent($this->match, $this->user(), $this->input('message')));
		}

		return $message;
	}

	/**
	 * @param Validator $validator
	 */
	public function withValidator(Validator $validator): void {

		$validator->after(function ($validator) {
			if (! $this->user()->canJoin($this->match)) {
				$validator->errors()->add('request', __('match/requests.cantJoin'));
			}
			if ($this->match->ended()) {
				$validator->errors()->add('ended', __('match/requests.ended'));
			}
		});
	}
}
