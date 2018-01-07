<?php

namespace Tests\Browser\Pages\User;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Page as BasePage;

class ConversationPage extends BasePage
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return action('User\UserController@showConversations', [], false);
    }

    /**
     * Assert that the browser is on the page.
     *
     * @param  Browser  $browser
     * @return void
     */
    public function assert(Browser $browser)
    {
        $browser->assertPathIs($this->url());
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array
     */
    public function elements()
    {
        return [
            '@submit-button' => '.float-lg-right',
            '@text' => '#message',
            '@conversation-loaded' => '.message'
        ];
    }
}
