<?php

namespace App\Http\Requests\Match;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class RepeatMatchRequest extends FormRequest
{
	protected $match;

	/**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
		$this->match = $this->route('match');
		if($this->user()->isManager($this->match)){
			return true;
		}

		return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }

	/**
	 * @param Validator $validator
	 */
	public function withValidator(Validator $validator): void {
		$validator->after(function ($validator) {
			if (! $this->match->ended()) {
				$validator->errors()->add('match', __('match/requests.ended'));
			}
		});
	}
}
