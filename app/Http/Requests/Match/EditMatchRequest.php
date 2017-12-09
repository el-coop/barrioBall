<?php

namespace App\Http\Requests\Match;

use App\Events\Match\Edited;
use Cache;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class EditMatchRequest extends FormRequest {
	protected $dateTime;
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
	 * @param Validator $validator
	 */
	public function withValidator(Validator $validator): void {
		try {
			$this->dateTime = Carbon::createFromFormat('d/m/y H:i', $this->input('date') . ' ' . $this->input('time'));
			$validator->after(function ($validator) {
				if ($this->dateTime < Carbon::now('Pacific/Pago_Pago')) {
					$validator->errors()->add('date', __('match/create.tooEarly'));
				}
			});
		} catch (Exception $exception) {
			$validator->errors()->add('date', __('match/create.timeError'));
		}
	}

	public function commit() {
		$this->match->name = $this->input('name');
		$this->match->address = $this->input('address');
		$this->match->lat = $this->input('lat');
		$this->match->lng = $this->input('lng');
		$this->match->date_time = $this->dateTime;
		$this->match->players = $this->input('players');
		$this->match->description = $this->input('description');

		$this->match->save();
		event(new Edited($this->match));
		Cache::forget(sha1("match_{$this->match->id}"));
	}

}
