<?php

namespace App\Http\Requests\Match;

use Illuminate\Foundation\Http\FormRequest;

class SearchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
			'date' => 'required|date_format:d/m/y',
			'start_time' => 'required|date_format:H:i',
			'end_time' => 'required|date_format:H:i',
			'north' => 'required|numeric',
			'east' => 'required|numeric',
			'west' => 'required|numeric',
			'south' => 'required|numeric'
        ];
    }

    public function commit(){
		return collect([]);
	}
}
