<?php

namespace Tests\Browser\Pages\Misc;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Page as BasePage;

class ContactUsPage extends BasePage {
	/**
	 * Assert that the browser is on the page.
	 *
	 * @param  Browser $browser
	 *
	 * @return void
	 */
	public function assert(Browser $browser): void {
		$browser->assertPathIs($this->url());
	}

	/**
	 * Get the URL for the page.
	 *
	 * @return string
	 */
	public function url(): string {
		return action('HomeController@showContactUs', [], false);
	}

	/**
	 * Get the element shortcuts for the page.
	 *
	 * @return array
	 */
	public function elements(): array {
		return [
			'@submit' => '.btn.btn-logo',
			'@sending' => '.fa.fa-spinner.fa-spin',
			'@tryAgain' => 'h5.text-center > a',
		];
	}

	/**
	 * @param Browser $browser
	 * @param array $data
	 *
	 * @return void
	 */
	public function submitForm(Browser $browser, array $data): void {
		foreach ($data as $key => $value) {
			$browser->type($key, $value);
		}
		$browser->click('@submit');
	}
}
