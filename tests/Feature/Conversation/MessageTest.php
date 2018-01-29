<?php

namespace Tests\Feature\Conversation;

use App\Models\Conversation;
use App\Models\Match;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MessageTest extends TestCase {
	use RefreshDatabase;

	protected $manager;
	protected $player;
	protected $match;


	public function setUp() {

		parent::setUp();
		$this->manager = factory(User::class)->create();
		$this->player = factory(User::class)->create();
		$this->match = factory(Match::class)->create();
		$this->match->addManager($this->manager);
	}

	/**
	 * @group messages
	 * @return void
	 */
	public function test_send_message_on_join_request(): void {
		$this->actingAs($this->player)->post(action('Match\MatchUserController@joinMatch', $this->match), [
			'message' => 'bla',
		]);
		$conversation = $this->manager->getConversationWith($this->player);
		$this->assertCount(1,$conversation->messages);
		$this->assertTrue($this->manager->hasUnreadConversations());
		$this->assertFalse($this->player->hasUnreadConversations());
		$this->assertEquals(__('conversations/conversation.join',[
			'url' => $this->match->url,
			'name' => $this->match->name
		],$this->manager->language),$conversation->messages()->first()->action);
	}

	/**
	 * @group messages
	 * @return void
	 */
	public function test_send_message_on_join_request_accepted(): void {
		$this->match->addJoinRequest($this->player);
		$this->actingAs($this->manager)->post(action('Match\MatchUserController@acceptJoin', $this->match), [
			'user' => $this->player->id,
		]);
		$conversation = $this->manager->getConversationWith($this->player);
		$this->assertCount(1,$conversation->messages);
		$this->assertTrue($this->player->hasUnreadConversations());
		$this->assertfalse($this->manager->hasUnreadConversations());
		$this->assertEquals(__('conversations/conversation.accepted',[
			'url' => $this->match->url,
			'name' => $this->match->name
		],$this->player->language),$conversation->messages()->first()->action);
	}

	/**
	 * @group messages
	 * @return void
	 */
	public function test_send_message_on_join_request_rejected(): void {
		$this->match->addJoinRequest($this->player);
		$this->actingAs($this->manager)->delete(action('Match\MatchUserController@rejectJoin', $this->match), [
			'user' => $this->player->id,
		]);
		$conversation = $this->manager->getConversationWith($this->player);
		$this->assertCount(1,$conversation->messages);
		$this->assertTrue($this->player->hasUnreadConversations());
		$this->assertfalse($this->manager->hasUnreadConversations());
		$this->assertEquals(__('conversations/conversation.rejected',[
			'url' => $this->match->url,
			'name' => $this->match->name
		],$this->player->language),$conversation->messages()->first()->action);
	}


	/**
	 * @group messages
	 * @return void
	 */
	public function test_send_message_on_manage_invite(): void {
		$this->actingAs($this->manager)->post(action('Match\MatchUserController@inviteManagers', $this->match), [
			'invite_managers' => [$this->player->id],
		]);
		$conversation = $this->manager->getConversationWith($this->player);
		$this->assertCount(1,$conversation->messages);
		$this->assertTrue($this->player->hasUnreadConversations());
		$this->assertfalse($this->manager->hasUnreadConversations());
		$this->assertEquals(__('conversations/conversation.manageInvite',[
			'url' => $this->match->url,
			'name' => $this->match->name
		],$this->player->language),$conversation->messages()->first()->action);
	}

	/**
	 * @group messages
	 * @return void
	 */
	public function test_send_message_on_remove_player(): void {
		$this->actingAs($this->manager)->delete(action('Match\MatchUserController@removePlayer', $this->match), [
			'user' => $this->player->id,
		]);
		$conversation = $this->manager->getConversationWith($this->player);
		$this->assertCount(1,$conversation->messages);
		$this->assertTrue($this->player->hasUnreadConversations());
		$this->assertfalse($this->manager->hasUnreadConversations());
		$this->assertEquals(__('conversations/conversation.removed',[
			'url' => $this->match->url,
			'name' => $this->match->name,
			'player' => $this->player->username
		],$this->player->language),$conversation->messages()->first()->action);
	}
}
