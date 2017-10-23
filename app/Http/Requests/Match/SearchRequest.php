<?php

namespace App\Http\Requests\Match;

use App\Models\Match;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Foundation\Http\FormRequest;

class SearchRequest extends FormRequest {
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize(): bool {
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules(): array {
		return [
			'date' => 'required|date_format:d/m/y',
			'from' => 'required|date_format:H:i',
			'to' => 'required|date_format:H:i',
			'north' => 'required|numeric',
			'east' => 'required|numeric',
			'west' => 'required|numeric',
			'south' => 'required|numeric',
		];
	}

	public function commit(): LengthAwarePaginator {
		$startTime = Carbon::createFromFormat('d/m/y H:i',$this->input('date') . ' ' . $this->input('from'));
		$endTime = Carbon::createFromFormat('d/m/y H:i',$this->input('date') . ' ' . $this->input('to'));

		$matches = Match::whereBetween('lng', [$this->input('west'), $this->input('east')])
			->whereBetween('lat', [$this->input('south'), $this->input('north')])
			->whereBetween('date_time', [$startTime, $endTime])
			->paginate(20);

		return $matches;
	}
}
