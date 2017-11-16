<?php

namespace Tests\Browser\Pages\User;

use Illuminate\Support\Collection;
use Laravel\Dusk\Browser;
use Laravel\Dusk\Page as BasePage;

class DashboardPage extends BasePage {
	/**
	 * Assert that the browser is on the page.
	 *
	 * @param  Browser $browser
	 *
	 * @return void
	 */
	public function assert(Browser $browser) {
		$browser->assertPathIs($this->url());
	}

	/**
	 * Get the URL for the page.
	 *
	 * @return string
	 */
	public function url() {
		return action('HomeController@index', [], false);
	}

	/**
	 * Get the element shortcuts for the page.
	 *
	 * @return array
	 */
	public function elements() {
		return [
			'@tables-loaded' => '.btn-dark',
			'@element' => '#selector',
		];
	}

	/**
	 * @param Browser $browser
	 * @param Collection $matches
	 */
	public function assertSeeMatches(Browser $browser, Collection $matches): void {
		foreach ($matches as $match) {
			$browser->assertSee($match->name);
		}
	}
}
