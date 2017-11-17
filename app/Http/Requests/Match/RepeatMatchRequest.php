<?php

namespace App\Http\Requests\Match;

use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class RepeatMatchRequest extends FormRequest {
	protected $match;
	protected $dateTime;

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize() {
		$this->match = $this->route('match');

		return $this->user()->can('update', $this->match);
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules() {
		return [
			'date' => 'required|date_format:d/m/y',
			'time' => 'required|date_format:H:i',
		];
	}

	/**
	 * @param Validator $validator
	 */
	public function withValidator(Validator $validator): void {
		try {
			$this->dateTime = Carbon::createFromFormat('d/m/y H:i', $this->input('date') . ' ' . $this->input('time'));
			$validator->after(function ($validator) {
				if ($this->dateTime < Carbon::now()) {
					$validator->errors()->add('date', __('match/create.tooEarly'));
				}
				if (!$this->match->ended()) {
					$validator->errors()->add('match', __('match/requests.ended'));
				}
			});
		} catch (Exception $exception) {
			$validator->errors()->add('date', __('match/create.timeError'));
		}
	}

	public function commit() {
		$this->match->date_time = $this->dateTime;
		$this->match->save();
	}
}
