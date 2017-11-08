<?php

namespace App\Http\Controllers\Match;

use App\Http\Requests\Match\AcceptJoinRequest;
use App\Http\Requests\Match\CancelJoinRequest;
use App\Http\Requests\Match\InviteMangersRequest;
use App\Http\Requests\Match\JoinMatchRequest;
use App\Http\Requests\Match\LeaveMatchRequest;
use App\Http\Requests\Match\RejectJoinRequest;
use App\Http\Requests\Match\RemovePlayerRequest;
use App\Http\Requests\Match\StopManagingRequest;
use App\Models\Match;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MatchUsersController extends Controller {

	/**
	 * @param JoinMatchRequest $request
	 * @param Match $match
	 *
	 * @return RedirectResponse
	 */
	public function joinMatch(JoinMatchRequest $request, Match $match): RedirectResponse {
		$message = $request->commit();

		return back()->with('alert', $message);
	}

    /**
     * @param CancelJoinRequest $request
     * @param Match $match
     * @return RedirectResponse
     */
    public function cancelJoin(CancelJoinRequest $request, Match $match): RedirectResponse {

	    $message = $request->commit();

        return back()->with('alert', $message);
    }

	/**
	 * @param LeaveMatchRequest $request
	 * @param Match $match
	 *
	 * @return RedirectResponse
	 */
	public function leaveMatch(LeaveMatchRequest $request, Match $match): RedirectResponse {
		$request->commit();

		return back()->with('alert', __('match/show.left'));
	}

	public function searchUsers(Request $request, Match $match) {
		return User::select('id', 'username')
			->where('username', 'LIKE', "%{$request->get('query')}%")
			->whereDoesntHave('managedMatches', function ($query) use ($match) {
				return $query->where('id', $match->id);
			})->get();
	}

	/**
	 * @param InviteMangersRequest $request
	 * @param Match $match
	 *
	 * @return RedirectResponse
	 */
	public function inviteManagers(InviteMangersRequest $request, Match $match): RedirectResponse {
		$request->commit();

		return back()->with('alert', __('match/show.invitationSent'));
	}

	/**
	 * @param StopManagingRequest $request
	 * @param Match $match
	 *
	 * @return RedirectResponse
	 */
	public function stopManaging(StopManagingRequest $request, Match $match): RedirectResponse {
		$request->commit();

		return back()->with('alert', __('match/show.managementLeft'));
	}

	/**
	 * @param AcceptJoinRequest $request
	 * @param Match $match
	 *
	 * @return RedirectResponse
	 */
	public function acceptJoin(AcceptJoinRequest $request, Match $match): RedirectResponse {
		$request->commit();

		return back()->with('alert', __('match/requests.accepted'));


	}

	/**
	 * @param RejectJoinRequest $request
	 * @param Match $match
	 *
	 * @return RedirectResponse
	 */
	public function rejectJoin(RejectJoinRequest $request, Match $match): RedirectResponse {
		$request->commit();

		return back()->with('alert', __('match/requests.rejected'));
	}

	/**
	 * @param RemovePlayerRequest $request
	 * @param Match $match
	 *
	 * @return RedirectResponse
	 */
	public function removePlayer(RemovePlayerRequest $request, Match $match): RedirectResponse {
		$request->commit();

		return back()->with('alert', __('match/removePlayer.removed'));
	}
}
