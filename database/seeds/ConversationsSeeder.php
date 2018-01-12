<?php

use App\Models\Admin;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\Player;
use Illuminate\Database\Seeder;

class ConversationsSeeder extends Seeder {
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run() {
		$admin = Admin::first()->user;
		factory(Conversation::class, 5)->create()->each(function ($conversation, $key) use ($admin) {
			$player = Player::find($key+1)->user;
			$conversation->users()->attach([$admin->id, $player->id]);
			$conversation->markAsUnread($admin);
			factory(Message::class)->create([
				'user_id' => $player->id,
				'conversation_id' => $conversation->id,
			]);
		});
	}
}
