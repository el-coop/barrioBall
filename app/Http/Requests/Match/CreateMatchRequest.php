<?php

namespace App\Http\Requests\Match;

use App\Models\User;
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
        if ($this->user()->exists()) {
            return true;
        }
        else {
            return false;
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'address' => 'required',
            'lat' => 'required',
            'lng' => 'required',
            'players' => 'required|int|min:8|max:22'
        ];
    }

    public function commit(User $user) {
        $match = new Match();
        $match->name = $this->input('name');
        $match->address = $this->input('address');
        $match->lat = $this->input('lat');
        $match->lng = $this->input('lng');
        $match->public = $this->has('public');
        $match->players = $this->input('players');
        $match->description = $this->input('description');
        $match->date = $this->input('date');
        $match->time = $this->input('time');
        $match->save();
        $match->users()->attach($user, ['role' => 'creator']);
    }
}
