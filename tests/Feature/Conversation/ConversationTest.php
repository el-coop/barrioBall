<?php

namespace Tests\Feature\Conversation;

use App\Models\Conversation;
use App\Models\Match;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ConversationTest extends TestCase
{
    use RefreshDatabase;

    public function setUp() {

        parent::setUp();
        $this->manager = factory(User::class)->create();
        $this->match = factory(Match::class)->create();
        $this->player = factory(User::class)->create();
        $this->match->addManager($this->manager);
    }


    /**
     * @return void
     * @group conversation
     */
    public function test_no_conversations_when_no_join_requests(): void{
        $response = $this->actingAs($this->player)->get(action('User\UserController@showConversations'));
        $response->assertSee(__('conversations/conversation.noConversations',[],$this->player->language));
    }

    /**
     * @group conversation
     * @return void
     */
    public function test_create_conversation_on_join_request(): void
    {
        $this->actingAs($this->player)->post(action('Match\MatchUserController@joinMatch', $this->match), [
            'message' => 'bla',
            ]);
        $this->assertDatabaseHas('conversation_user', ['user_id' => $this->player->id, 'conversation_id' => 1]);
        $this->assertDatabaseHas('conversation_user', ['user_id' => $this->manager->id, 'conversation_id' => 1]);
        $this->assertDatabaseHas('messages', ['text' => 'bla', 'conversation_id' => 1, 'action_match_id' => $this->match->id]);

    }

    /**
     * @group conversation
     * @return void
     */
    public function test_show_conversation_after_join_request(): void {
        $this->actingAs($this->player)->post(action('Match\MatchUserController@joinMatch', $this->match), [
            'message' => 'bla',
        ]);
        $response = $this->actingAs($this->player)->get(action('User\UserController@showConversations'));
        $response->assertSee('bla');
        $response->assertSee($this->manager->username);

    }
}
