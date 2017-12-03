<?php

namespace Tests\Browser\Pages\Match;

use App\Models\Match;
use Laravel\Dusk\Browser;
use Laravel\Dusk\Page as BasePage;

class EditPage extends BasePage
{
	protected $match;

	public function __construct(Match $match) {
		$this->match = $match;
	}

    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return action('Match\MatchController@showEditForm',$this->match,false);
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
            '@submit' => '.btn.btn-primary.btn-block',
        ];
    }
}
