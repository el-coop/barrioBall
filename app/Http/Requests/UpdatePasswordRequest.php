<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePasswordRequest extends FormRequest
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
            'password' => 'required|min:6|confirmed'
        ];
    }


    public function commit() {

        $this->user()->password = bcrypt($this->input('password'));
        $this->user()->save();
    }
}
