<?php

namespace App\Http\Requests\Match;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\Match;

class CreateMatchRequest extends FormRequest {
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize() {
		if ($this->user()->exists()) {
			return true;
		}

		return false;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules() {
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

	public function commit() {
		$match = new Match();
		$match->name = $this->input('name');
		$match->address = $this->input('address');
		$match->lat = $this->input('lat');
		$match->lng = $this->input('lng');
		$match->public = 1;
		$match->players = $this->input('players');
		$match->description = $this->input('description');
		$match->date = $this->input('date');
		$match->time = $this->input('time');
		$match->save();
		$match->addManager($this->user());

		return $match;
	}
}
