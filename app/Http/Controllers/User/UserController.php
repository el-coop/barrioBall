<?php

namespace App\Http\Controllers\User;

use App\Http\Requests\User\DeleteUserRequest;
use App\Http\Requests\User\GetConversationMessagesRequest;
use App\Http\Requests\User\SendMessageRequest;
use App\Http\Requests\User\UpdateEmailRequest;
use App\Http\Requests\User\UpdateLanguageRequest;
use App\Http\Requests\User\UpdatePasswordRequest;
use App\Http\Requests\User\UpdateUsernameRequest;
use App\Models\Conversation;
use App\Models\User;
use Auth;
use Cache;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class UserController extends Controller {

	/**
	 * @param Request $request
	 *
	 * @return View
	 */
	public function show(Request $request): View {

		$hasManagedMatches = Cache::rememberForever(sha1("{$request->user()->username}_hasManagedMatches"), function () use ($request) {
			return !! $request->user()->managedMatches()->count();
		});

		return view('user.profile',compact('hasManagedMatches'));
	}

	/**
	 * @param UpdateUsernameRequest $request
	 *
	 * @return RedirectResponse
	 */
	public function updateUsername(UpdateUsernameRequest $request): RedirectResponse {
		$request->commit();
		$message = __('profile/page.updatedUsername');

		return back()->with('alert', $message);
	}

	/**
	 * @param UpdatePasswordRequest $request
	 *
	 * @return RedirectResponse
	 */
	public function updatePassword(UpdatePasswordRequest $request): RedirectResponse {

		$request->commit();
		$message = __('profile/page.updatedPassword');

		return back()->with('alert', $message);
	}

	/**
	 * @param UpdateEmailRequest $request
	 *
	 * @return RedirectResponse
	 */
	public function updateEmail(UpdateEmailRequest $request): RedirectResponse {

		$request->commit();
		$message = __('profile/page.updatedEmail');

		return back()->with('alert', $message);
	}

	/**
	 * @param UpdateLanguageRequest $request
	 *
	 * @return RedirectResponse
	 */
	public function updateLanguage(UpdateLanguageRequest $request): RedirectResponse {

		$request->commit();
		$message = __('profile/page.updatedLanguage', [], $request->get('language'));

		return back()->with('alert', $message);
	}

	/**
	 * @param DeleteUserRequest $request
	 *
	 * @return RedirectResponse
	 */
	public function delete(DeleteUserRequest $request): RedirectResponse {
		$request->commit();

		return redirect('/home');
	}

	/**
	 * @param Request $request
	 *
	 * @return LengthAwarePaginator
	 */
	public function getMatches(Request $request): LengthAwarePaginator {
		$tagType = $request->has('managed') ? 'managed' : 'played';

		return Cache::tags("{$request->user()->username}_{$tagType}")
			->remember(sha1($request->fullUrl()),5, function () use ($request) {
				if ($request->has('managed')) {
					$matches = $request->user()->managedMatches()->withCount('joinRequests');
				} else {
					$matches = $request->user()->playedMatches()->where('date_time', '>', Carbon::today('Pacific/Pago_Pago'));
				}

				if ($request->filled('sort')) {
					$sort = explode('|', $request->input('sort'));
					$matches = $matches->orderBy($sort[0], $sort[1]);

				} else {
					$matches = $matches->latest();
				}

				if ($request->filled('filter')) {
					$filterVal = "%{$request->input('filter')}%";
					$matches->where('name', 'like', $filterVal);
				}

				return $matches->paginate($request->input('per_page'));
			});
	}

    public function showConversations(Request $request){


        $conversations = $request->user()->conversations()->with(['users', 'messages'])->get();
        if ($conversation = $conversations->pop()){
        	$conversation->markAsRead($request->user());
        	$conversation->pivot->read = 1;
        	$conversations->prepend($conversation);
		}
        return view('user.conversations.show', compact('conversations'));
    }

    public function getConversationMessages(Conversation $conversation, Request $request){
            $conversation->markAsRead($request->user());
            return $conversation->messages;
    }

    public function sendMessage(SendMessageRequest $request,Conversation $conversation){
        $request->commit();

        return [
			'action_type' => null,
			'action_match' => null,
			'action_match_id' => null,
			'text' => $request->input('message'),
			'user_id' => $request->user()->id,
			'date' => Carbon::now()->format('d/m/y'),
			'time' => Carbon::now()->format('H:i'),
		];
    }
}
