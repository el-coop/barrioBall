<?php

namespace App\Http\Requests\Match;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Match;

class CreateMatchRequest extends FormRequest
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
            //
        ];
    }

    public function commit() {
        $match = new Match();
        $match->name = $this->input('name');
        $match->address = $this->input('address');
        $match->lat = $this->input('lat');
        $match->lng = $this->input('lng');
        $match->public = $this->has('public');
        $match->recurring = $this->has('recurring');
        $match->players = $this->input('players');
        $match->description = $this->input('description');
        $match->save();
    }
}
