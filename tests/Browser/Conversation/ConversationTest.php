<?php

namespace Tests\Browser\Conversation;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Tests\Browser\Pages\User\ConversationPage;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ConversationTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function setUp()
    {

        parent::setUp();
        $this->user = factory(User::class)->create([
            'language' => 'en'
        ]);
        $this->user2 = factory(User::class)->create([
            'language' => 'en'
        ]);
        $this->conversation = factory(Conversation::class)->create();
        $this->conversation->users()->attach([$this->user->id, $this->user2->id]);
        $this->message = factory(Message::class)->create([
            'user_id' => $this->user->id,
            'conversation_id' => $this->conversation->id,
            'text' => 'Bla'
        ]);

    }

    /**
     * @test
     * @group conversation
     */
    public function test_shows_first_conversation(): void
    {
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
    public function test_can_change_conversations(): void
    {
        $user3 = factory(User::class)->create([
            'language' => 'en'
        ]);
        $conversation2 = factory(Conversation::class)->create();
        $conversation2->users()->attach([$this->user->id, $user3->id]);
        $message2 = factory(Message::class)->create([
            'user_id' => $this->user->id,
            'conversation_id' => $conversation2->id,
            'text' => 'Test Message'
        ]);

        $this->browse(function (Browser $browser) use ($user3, $message2) {
            $browser->resize(1200, 1200);

            $browser->loginAs($this->user)
                ->visit(new ConversationPage)
                ->press($user3->username)
                ->pause(500)
                ->assertSee($message2->text);
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
                ->pause(500)
                ->assertSee('New Test Message');
        });
    }
}
