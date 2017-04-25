<?php

namespace App\Http\Requests\Match;

use App\Models\Match;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class SearchRequest extends FormRequest {
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize() {
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules() {
		return [
			'date' => 'required|date_format:d/m/y',
			'from' => 'required|date_format:H:i',
			'to' => 'required|date_format:H:i',
			'north' => 'required|numeric',
			'east' => 'required|numeric',
			'west' => 'required|numeric',
			'south' => 'required|numeric'
		];
	}

	public function commit() {
		$date = Carbon::createFromFormat('d/m/y',$this->input('date'))->format('Y-m-d');
		$startTime = Carbon::createFromFormat('H:i',$this->input('from'))->subSecond()->format('H:i:s');
		$endTime = Carbon::createFromFormat('H:i',$this->input('to'))->addSecond()->format('H:i:s');

		$matches = Match::whereBetween('lng', [$this->input('west'), $this->input('east')])
			->whereBetween('lat', [$this->input('south'), $this->input('north')])
			->whereBetween('time',[$startTime,$endTime])
			->where('date',$date)
			->paginate(20);

		return $matches;
	}
}
