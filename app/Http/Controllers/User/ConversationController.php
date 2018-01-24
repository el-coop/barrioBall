<?php

namespace App\Http\Controllers\User;

use App\Http\Requests\User\SendMessageRequest;
use App\Models\Conversation;
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
		$conversations = $request->user()->conversations()->whereHas('messages')->with(['users'])->orderBy('updated_at','desc')->get();
		if ($conversation = $conversations->shift()) {
			$conversation->markAsRead($request->user());
			$conversation->pivot->read = 1;
			$conversations->prepend($conversation);
		}

		return view('user.conversations.show', compact('conversations'));
	}

	/**
	 * @param Conversation $conversation
	 * @param Request $request
	 *
	 * @return Collection
	 */
	public function getConversationMessages(Conversation $conversation, Request $request): Collection {
		$conversation->markAsRead($request->user());

		return $conversation->messages->each->append(['date','time']);
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
	 * @return array
	 */
	public function markAsRead(Conversation $conversation): array {
		$conversation->markAsRead();

		return ['success' => true];
	}
}
