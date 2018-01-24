<?php

namespace Tests\Unit\Models;

use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ConversationTest extends TestCase {
	use RefreshDatabase;

	protected $conversation;
	protected $user;
	protected $user2;

	public function setUp() {
		parent::setUp();

		$this->user = factory(User::class)->create();
		$this->user2 = factory(User::class)->create();
		$this->conversation = $this->user->getConversationWith($this->user2);
	}


	/**
	 * @test
	 * @group conversation
	 */
	public function test_add_message_works(): void {
		$this->assertFalse($this->user2->hasUnreadConversations());

		$message = factory(Message::class)->make([
			'user_id' => $this->user->id,
			'conversation_id' => null,
		]);

		$this->conversation->addMessage($message);

		$this->assertContains($message->fresh()->id, $this->conversation->messages->pluck('id')->toArray());
		$this->assertTrue($this->user2->hasUnreadConversations());
	}

	/**
	 * @test
	 * @group conversation
	 * @return void
	 */
	public function test_message_relationship_works(): void {
		$messages = factory(Message::class, 3)->create()->each(function ($message) {
			$this->conversation->addMessage($message);
		});

		$this->assertInstanceOf(HasMany::class, $this->conversation->messages());
		$this->assertInstanceOf(Collection::class, $this->conversation->messages);
		$this->assertArraySubset($messages->pluck('id'), $this->conversation->messages->pluck('id'));
		$this->assertCount(3, $this->conversation->messages);
	}

	/**
	 * @test
	 * @group conversation
	 */
	public function test_mark_as_read_works(): void {
		$message = factory(Message::class)->make([
			'user_id' => $this->user->id,
			'conversation_id' => null,
		]);
		$this->conversation->addMessage($message);

		$this->assertTrue($this->user2->hasUnreadConversations());

		$this->conversation->markAsRead($this->user2);

		$this->assertFalse($this->user2->hasUnreadConversations());
	}

	/**
	 * @test
	 * @group conversation
	 */
	public function test_mark_as_unread_works(): void {
		$message = factory(Message::class)->make([
			'user_id' => $this->user->id,
			'conversation_id' => null,
		]);
		$this->conversation->addMessage($message);

		$this->assertTrue($this->user2->hasUnreadConversations());

		$this->conversation->markAsRead($this->user2);

		$this->assertFalse($this->user2->hasUnreadConversations());

		$this->conversation->markAsUnRead($this->user2);

		$this->assertTrue($this->user2->hasUnreadConversations());
	}
}
