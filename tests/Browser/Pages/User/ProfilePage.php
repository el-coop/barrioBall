<?php

namespace Tests\Browser\Pages\User;

use App\Models\User;
use Illuminate\Support\Collection;
use Laravel\Dusk\Browser;
use Laravel\Dusk\Page as BasePage;

class ProfilePage extends BasePage
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return action('User\UserController@show', [], false);
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
            '@tables-loaded' => '.btn-dark',
			'@delete-button' => '.btn.btn-outline-danger'
        ];
    }

	/**
	 * @param Browser $browser
	 * @param Collection $matches
	 */
	public function assertSeeMatches(Browser $browser, Collection $matches): void{
		foreach ($matches as $match) {
			$browser->assertSee($match->name);
		}
	}

	/**
	 * @param Browser $browser
	 * @param User $user
	 */
	public function acceptDelete(Browser $browser, User $user): void{
		$browser->whenAvailable('.swal2-modal', function ($modal) use ($user){
			$modal->assertSee(__('profile/page.deleteYourAccount',[],$user->language))
				->press('.swal2-confirm');
		});
	}
}
