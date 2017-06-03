<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function edit(Request $request)
    {
    	$user = $request->user();
        return view('user/edit', compact('user'));
    }


    public function update(Request $request, $id)
    {
        //

        $user = User::findOrFail($id);
        $password = Hash::make($request['password']);;

        $user-> update($request->all());
        $user->password = $password;
        $user->save();

       return redirect('/home');
    }

    public function destroy($id)
    {
        //
    }

    public function getMatches(Request $request){
		$matches = $request->user()->matches()->withPivot('role');

		if ($request->has('sort')) {
			$matches = $matches->orderBy($request->input('sort'));
		} else {
			$matches = $matches->latest();
		}

		if ($request->has('filter')) {
			$filterVal = "%{$request->input('filter')}%";
			$matches->where(function ($query) use ($filterVal) {
				$query->where('name', 'like', $filterVal);
			})->orWherePivot('role','like',$filterVal);
		}

		return $matches->paginate($request->input('per_page'));
	}
}
