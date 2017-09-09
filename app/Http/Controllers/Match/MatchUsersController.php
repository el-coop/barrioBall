<?php

namespace App\Http\Controllers\Match;

use App\Http\Requests\Match\AcceptJoinRequest;
use App\Http\Requests\Match\InviteMangersRequest;
use App\Http\Requests\Match\JoinMatchRequest;
use App\Http\Requests\Match\LeaveMatchRequest;
use App\Http\Requests\Match\RejectJoinRequest;
use App\Http\Requests\Match\StopManagingRequest;
use App\Models\Match;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MatchUsersController extends Controller
{
	public function joinMatch(JoinMatchRequest $request, Match $match){
		$message = $request->commit();
		return back()->with('alert', $message);
	}

	public function leaveMatch(LeaveMatchRequest $request, Match $match){
		$request->commit();
		return back()->with('alert', __('match/show.left'));
	}

	public function searchUsers(Request $request, Match $match){
		return User::select('id','username')
			->where('username','LIKE',"%{$request->get('query')}%")
			->whereDoesntHave('managedMatches',function($query) use ($match){
				return $query->where('id',$match->id);
			})->get();
	}

	public function inviteManagers(InviteMangersRequest $request, Match $match){
		$request->commit();
		return back()->with('alert', __('match/show.invitationSent'));
	}

	public function stopManaging(StopManagingRequest $request, Match $match){
		$request->commit();
		return back()->with('alert', __('match/show.managementLeft'));
	}

	public function acceptJoin(AcceptJoinRequest $request, Match $match){
		if($message = $request->commit()){
			return back()->with('alert', $message);
		}
		return back()->with('error', "The join request was canceled/rejected/accepted before you accepted it");
	}

	public function rejectJoin(RejectJoinRequest $request, Match $match){
		if($request->commit()){
			return back()->with('alert', __('match/requests.rejected'));
		}

		return back()->with('error',__('match/requests.requestNotExistent'));
	}
}
