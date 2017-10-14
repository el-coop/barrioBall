<?php

namespace Tests\Browser\Pages\Match;

use Illuminate\Support\Collection;
use Laravel\Dusk\Browser;
use Laravel\Dusk\Page as BasePage;

class SearchPage extends BasePage {
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
		return action('Match\MatchController@showSearch', [], false);
	}

	/**
	 * Get the element shortcuts for the page.
	 *
	 * @return array
	 */
	public function elements() {
		return [
			'@map' => '.leaflet-map-pane',
			'@search-results' => '.card-header',
			'@front-shown' => '.flipper:not(.flipped)',
			'@back-shown' => '.flipper.flipped',
			'@flip-button' => '.search-toggle > .btn',
		];
	}

	/**
	 * @param Browser $browser
	 * @param array $data
	 */
	public function submitForm(Browser $browser, array $data): void {
		foreach ($data as $key => $value) {
			$browser->type($key, $value);
		}
		$browser->click('.sm-btn-block');
	}

	/**
	 * @param Browser $browser
	 * @param Collection $matches
	 */
	public function assertSeeMatches(Browser $browser, Collection $matches): void {
		$i = 0;
		foreach ($matches as $match) {
			$browser->assertSee($match->name)
				->assertVisible(".icon-result-{$i}");
			$i++;
		}
	}
}
