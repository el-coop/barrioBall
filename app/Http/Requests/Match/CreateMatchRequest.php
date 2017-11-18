<?php

namespace App\Http\Requests\Match;

use App\Events\Match\Created;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\Match;

class CreateMatchRequest extends FormRequest {
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize(): bool {
		return $this->user()->can('create',Match::class);
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules(): array {
		return [
			'name' => 'required|min:3',
			'address' => 'required|min:3',
			'description' => 'required|min:3',
			'lat' => 'required|numeric',
			'lng' => 'required|numeric',
			'players' => 'required|int|min:8|max:22',
			'date' => 'required|date_format:d/m/y',
			'time' => 'required|date_format:H:i',
		];
	}

	/**
	 * @return Match
	 */
	public function commit(): Match {
		$match = new Match();
		$match->name = $this->input('name');
		$match->address = $this->input('address');
		$match->lat = $this->input('lat');
		$match->lng = $this->input('lng');
		$match->public = 1;
		$match->players = $this->input('players');
		$match->description = $this->input('description');

		$match->date_time = Carbon::createFromFormat('d/m/y H:i', $this->input('date') . ' ' . $this->input('time'));
		$match->save();
		$match->addManager($this->user());

		event(new Created($match));
		return $match;
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
			});
		} catch (Exception $exception) {
			$validator->errors()->add('date', __('match/create.timeError'));
		}
	}
}
