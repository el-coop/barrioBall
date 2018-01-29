<?php

namespace App\Http\Controllers\User;

use App\Http\Requests\User\SendMessageRequest;
use App\Models\Conversation;
use Cache;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;

class ConversationController extends Controller {

	/**
	 * @param Request $request
	 *
	 * @return View
	 */
	public function showConversations(Request $request): View {
		$conversations = Cache::rememberForever(sha1("{$request->user()->username}_conversations"), function () use($request){
			$conversations = $request->user()->conversations()->whereHas('messages')->with(['users'])->orderBy('updated_at', 'desc')->get();
			if ($conversation = $conversations->shift()) {
				$conversation->markAsRead($request->user());
				$conversation->pivot->read = 1;
				$conversations->prepend($conversation);
			}

			return $conversations;
		});

		return view('user.conversations.show', compact('conversations'));
	}

	/**
	 * @param Conversation $conversation
	 * @param Request $request
	 *
	 * @return Collection
	 */
	public function getConversationMessages(Conversation $conversation, Request $request): Collection {
		$messages = Cache::rememberForever(sha1("{$request->user()->username}_{$conversation->id}_conversation"), function() use ($conversation,$request){
			$conversation->markAsRead($request->user());
			Cache::forget(sha1("{$request->user()->username}_conversations"));
			return $conversation->messages->each->append(['date', 'time']);
		});

		return $messages;
	}

	/**
	 * @param SendMessageRequest $request
	 * @param Conversation $conversation
	 *
	 * @return array
	 */
	public function sendMessage(SendMessageRequest $request, Conversation $conversation): array {
		$request->commit();

		return [
			'action' => null,
			'text' => $request->input('message'),
			'user_id' => $request->user()->id,
			'date' => Carbon::now()->format('d/m/y'),
			'time' => Carbon::now()->format('H:i'),
		];
	}

	/**
	 * @param Conversation $conversation
	 *
	 * @param Request $request
	 *
	 * @return array
	 */
	public function markAsRead(Conversation $conversation, Request $request): array {
		$conversation->markAsRead();
		return ['success' => true];
	}
}
