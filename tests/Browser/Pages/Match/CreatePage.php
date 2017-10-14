<?php

namespace Tests\Browser\Pages\Match;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Page as BasePage;

class CreatePage extends BasePage {
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
		return action('Match\MatchController@showCreate', [], false);
	}

	/**
	 * Get the element shortcuts for the page.
	 *
	 * @return array
	 */
	public function elements() {
		return [
			'@front-shown' => '.flipper:not(.flipped)',
			'@back-shown' => '.flipper.flipped',
			'@flip-button' => '.search-toggle > .btn',
			'@info-tooltip' => '.fa.fa-info-circle',
			'@map' => '.leaflet-proxy.leaflet-zoom-animated',
			'@address-modal' => '.swal2-container.swal2-shown',
			'@submit-button' => '.btn.btn-primary.btn-block',
		];
	}

	/**
	 * @param Browser $browser
	 * @param string $address
	 */
	public function fillAddressModal(Browser $browser, string $address): void {
		$browser->whenAvailable('@address-modal', function ($modal) use ($address) {
			$modal->type('.swal2-input', $address)
				->click('.swal2-confirm.swal2-styled');
		});
	}

	/**
	 * @param Browser $browser
	 * @param array $data
	 */
	public function submitForm(Browser $browser, array $data): void {
		foreach ($data as $key => $value) {
			$browser->type($key, $value);
		}
		$browser->click('@submit-button');
	}

}
