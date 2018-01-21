<?php

namespace Tests\Feature\Conversation;

use App\Models\Conversation;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MessageTest extends TestCase
{
    use RefreshDatabase;
    public function setUp() {

        parent::setUp();
        $this->manager = factory(User::class)->create();
        $this->player = factory(User::class)->create();
        $this->conversation = factory(Conversation::class)->create();
        $this->conversation->users()->attach([$this->player->id, $this->manager->id]);

    }

    /**
     * @return void
     * @group conversation
     * @group message
     */
    public function test_can_send_message_on_existing_conversation(): void {
        $this->actingAs($this->player)->post(action('User\ConversationController@sendMessage', $this->conversation->id),['message' => 'test message']);
        $this->assertDatabaseHas('messages', ['text' => 'test message', 'conversation_id' => $this->conversation->id]);
    }

    /**
     * @return void
     * @group conversation
     * @group message
     */
    public function test_shows_message_after_message_sent_on_existing_conversation(): void {
        $this->actingAs($this->player)->post(action('User\ConversationController@sendMessage', $this->conversation->id),['message' => 'test message']);
        $response = $this->actingAs($this->player)->get(action('User\ConversationController@showConversations'));
        $response->assertSee($this->manager->username);

    }
}
