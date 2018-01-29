<?php

namespace Tests\Feature\Conversation;

use App\Models\Conversation;
use App\Models\Match;
use App\Models\Message;
use App\Models\User;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ConversationTest extends TestCase {
	use RefreshDatabase;

	protected $manager;
	protected $player;
	protected $conversation;

	public function setUp() {
		parent::setUp();
		$this->manager = factory(User::class)->create();
		$this->player = factory(User::class)->create();
		$this->conversation = $this->manager->getConversationWith($this->player);
	}

	/**
	 * @test
	 * @return void
	 * @group conversation
	 */
	public function test_cant_Access_conversations_unlogged(): void {
		$this->get(action('User\ConversationController@showConversations'))
			->assertRedirect(action('Auth\LoginController@showLoginForm'));
	}

	/**
	 * @test
	 * @return void
	 * @group conversation
	 */
	public function test_no_conversations_when_no_messages(): void {
		$this->actingAs($this->player)->get(action('User\ConversationController@showConversations'))
			->assertSee(__('conversations/conversation.noConversations', [], $this->player->language));
	}

	/**
	 * @test
	 * @group conversation
	 * @return void
	 */
	public function test_show_conversation_when_messages(): void {
		$message = factory(Message::class)->make([
			'user_id' => $this->manager->id,
			'conversation_id' => null,
		]);
		$this->conversation->addMessage($message);
		$response = $this->actingAs($this->player)->get(action('User\ConversationController@showConversations'));
		$response->assertSee($this->manager->username);
	}

	/**
	 * @test
	 * @group conversation
	 * @return void
	 */
	public function test_get_conversation_messages(): void {
		$messages = factory(Message::class, 3)->make([
			'user_id' => $this->manager->id,
			'conversation_id' => null,
		])->each(function ($message) {
			$this->conversation->addMessage($message);
		});
		$this->assertTrue($this->player->hasUnreadConversations());

		$this->actingAs($this->player)->get(action('User\ConversationController@getConversationMessages', $this->conversation))
			->assertJsonCount(3)
			->assertJson($messages->toArray());

		$this->assertFalse($this->player->hasUnreadConversations());

	}

	/**
	 * @test
	 * @group conversation
	 * @return void
	 */
	public function test_cant_get_conversation_messages_not_logged(): void {
		$this->get(action('User\ConversationController@getConversationMessages', $this->conversation))
			->assertRedirect(action('Auth\LoginController@showLoginForm'));
	}

	/**
	 * @test
	 * @group conversation
	 * @return void
	 */
	public function test_cant_get_conversation_messages_not_participatig(): void {
		$otherUser = factory(User::class)->create();
		$this->actingAs($otherUser)->get(action('User\ConversationController@getConversationMessages', $this->conversation))
			->assertStatus(403);
	}

	/**
	 * @test
	 * @group conversation
	 */
	public function test_send_message_route(): void {
		$this->actingAs($this->manager)->post(action('User\ConversationController@sendMessage', $this->conversation), [
			'message' => 'test',
		])->assertJson([
			'action' => null,
			'text' => 'test',
			'user_id' => $this->manager->id,
			'date' => Carbon::now()->format('d/m/y'),
			'time' => Carbon::now()->format('H:i'),
		]);

		$this->assertCount(1, $this->conversation->messages);
		$this->assertTrue($this->player->hasUnreadConversations());
	}

	/**
	 * @test
	 * @group conversation
	 */
	public function test_cant_send_empty_message(): void {
		$this->actingAs($this->manager)->post(action('User\ConversationController@sendMessage', $this->conversation), [
			'message' => '',
		])->assertSessionHasErrors('message');

		$this->assertCount(0, $this->conversation->messages);
		$this->assertFalse($this->player->hasUnreadConversations());
	}

	/**
	 * @test
	 * @group conversation
	 * @return void
	 */
	public function test_cant_send_message_not_logged(): void {
		$this->post(action('User\ConversationController@sendMessage', $this->conversation), [
			'message' => 'test',
		])->assertRedirect(action('Auth\LoginController@showLoginForm'));
	}


	/**
	 * @test
	 * @group conversation
	 * @return void
	 */
	public function test_cant_send_messages_not_participatig(): void {
		$otherUser = factory(User::class)->create();
		$this->actingAs($otherUser)->post(action('User\ConversationController@sendMessage', $this->conversation), [
			'message' => 'test',
		])->assertStatus(403);
	}

	public function test_mark_as_read_route(): void {
		$messages = factory(Message::class, 3)->make([
			'user_id' => $this->manager->id,
			'conversation_id' => null,
		])->each(function ($message) {
			$this->conversation->addMessage($message);
		});
		$this->assertTrue($this->player->hasUnreadConversations());

		$this->actingAs($this->player)->post(action('User\ConversationController@markAsRead', $this->conversation))
			->assertJson(['success' => true]);

		$this->assertFalse($this->player->hasUnreadConversations());

	}

	/**
	 * @test
	 * @group conversation
	 * @return void
	 */
	public function test_cant_mark_as_read_unlogged(): void {
		$this->post(action('User\ConversationController@markAsRead', $this->conversation))
			->assertRedirect(action('Auth\LoginController@showLoginForm'));
	}

	/**
	 * @test
	 * @group conversation
	 * @return void
	 */
	public function test_cant_mark_as_read_not_participating(): void {
		$otherUser = factory(User::class)->create();
		$this->actingAs($otherUser)->post(action('User\ConversationController@markAsRead', $this->conversation))
			->assertStatus(403);
	}
}
