<?php

namespace Tests\Browser\Conversation;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Tests\Browser\Pages\User\ConversationPage;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ConversationTest extends DuskTestCase {
	use DatabaseMigrations;

	protected $user;
	protected $user2;
	protected $conversation;
	protected $message;

	public function setUp() {

		parent::setUp();
		$this->user = factory(User::class)->create();
		$this->user2 = factory(User::class)->create();
		$this->conversation = $this->user->getConversationWith($this->user2);
		$this->message = factory(Message::class)->create([
			'user_id' => $this->user->id,
		]);
		$this->conversation->addMessage($this->message);

	}

	/**
	 * @test
	 * @group conversation
	 */
	public function test_shows_first_conversation(): void {
		$this->browse(function (Browser $browser) {
			$browser->resize(1200, 1200);

			$browser->loginAs($this->user)
				->visit(new ConversationPage)
				->waitFor('@conversation-loaded')
				->assertSee($this->user2->username)
				->assertSee($this->message->text);
		});
	}

	/**
	 * @test
	 * @group conversation
	 */
	public function test_can_change_conversations(): void {
		$user3 = factory(User::class)->create();
		$conversation = $this->user->getConversationWith($user3);
		$message = factory(Message::class)->create([
			'user_id' => $this->user->id,
			'text' => 'different message',
		]);
		$conversation->addMessage($message);

		$this->browse(function (Browser $browser) use ($user3, $message) {
			$browser->resize(1200, 1200);

			$browser->loginAs($this->user)
				->visit(new ConversationPage)
				->clickLink($user3->username)
				->waitForText($message->text);
		});
	}

	/**
	 * @test
	 * @group conversation
	 */
	public function test_show_message_after_sending(): void {
		$this->browse(function (Browser $browser) {
			$browser->resize(1200, 1200);

			$browser->loginAs($this->user)
				->visit(new ConversationPage)
				->waitFor('@conversation-loaded')
				->type('@text', 'New Test Message')
				->press('@submit-button')
				->waitForText('New Test Message');
		});
	}

	/**
	 * @test
	 * @group conversation
	 */
	public function test_message_recieved_on_other_browser(): void {
		$this->browse(function (Browser $browser, Browser $browser1) {
			$browser->resize(1200, 1200);
			$browser1->resize(1200, 1200);
			$user2Page = $browser1->loginAs($this->user2)->visit(new ConversationPage);

			$browser->loginAs($this->user)
				->visit(new ConversationPage)
				->waitFor('@conversation-loaded')
				->type('@text', 'New Test Message')
				->press('@submit-button')
				->waitForText('New Test Message');

			$user2Page->waitForText('New Test Message');
		});
	}

	/**
	 * @test
	 * @group conversation
	 */
	public function test_message_markes_new_notification_as_new(): void {
		$this->conversation->markAsRead($this->user);
		$user3 = factory(User::class)->create();
		$conversation = $this->user->getConversationWith($user3);
		$message = factory(Message::class)->create([
			'user_id' => $this->user->id,
			'text' => 'Different Message',
		]);
		$conversation->addMessage($message);
		$conversation->markAsRead($this->user);

		$this->browse(function (Browser $browser, Browser $browser1) use ($user3, $message) {
			$browser->resize(1200, 1200);
			$browser1->resize(1200, 1200);

			$user1page = $browser->loginAs($this->user)
				->visit(new ConversationPage)
				->clickLink($user3->username)
				->waitForText($message->text);


			$browser1->loginAs($this->user2)
				->visit(new ConversationPage)
				->waitFor('@conversation-loaded')
				->type('@text', 'second message')
				->press('@submit-button')
				->waitForText('second message');

			$user1page->waitForText('New')
				->assertSeeIn('.badge.badge-info.small-badge','1')
				->clickLink($this->user2->username)
				->waitForText('second message')
				->assertDontSee('New')
				->assertDontSeeIn('.badge.badge-info.small-badge','1');
		});
	}
}
