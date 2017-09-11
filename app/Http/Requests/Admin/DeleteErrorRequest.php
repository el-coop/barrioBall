<?php

namespace App\Http\Requests\Admin;

use App\Models\Errors\Error;
use Illuminate\Foundation\Http\FormRequest;

class DeleteErrorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
    	if($this->user()->isAdmin()){
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
        ];
    }

    public function commit(){
    	$error = $this->route('error');
    	$error->errorable->delete();
	}
}
