<?php

namespace App\Http\Controllers\User;

use App\Http\Requests\User\SendMessageRequest;
use App\Models\Conversation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ConversationController extends Controller {

	public function showConversations(Request $request) {
		$conversations = $request->user()->conversations()->with(['users'])->orderBy('updated_at','desc')->get();
		if ($conversation = $conversations->shift()) {
			$conversation->markAsRead($request->user());
			$conversation->pivot->read = 1;
			$conversations->prepend($conversation);
		}

		return view('user.conversations.show', compact('conversations'));
	}

	public function getConversationMessages(Conversation $conversation, Request $request) {
		$conversation->markAsRead($request->user());

		return $conversation->messages->each->append(['date','time']);
	}

	public function sendMessage(SendMessageRequest $request, Conversation $conversation) {
		$request->commit();

		return [
			'action' => null,
			'text' => $request->input('message'),
			'user_id' => $request->user()->id,
			'date' => Carbon::now()->format('d/m/y'),
			'time' => Carbon::now()->format('H:i'),
		];
	}

	public function markAsRead(Conversation $conversation): array {
		$conversation->markAsRead();

		return ['success' => true];
	}
}
