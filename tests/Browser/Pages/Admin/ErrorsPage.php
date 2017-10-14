<?php

namespace Tests\Browser\Pages\Admin;

use Illuminate\Support\Collection;
use Laravel\Dusk\Browser;
use Laravel\Dusk\Page as BasePage;

class ErrorsPage extends BasePage {
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
		return action('Admin\ErrorController@show', [], false);
	}

	/**
	 * Get the element shortcuts for the page.
	 *
	 * @return array
	 */
	public function elements() {
		return [
			'@tables-loaded' => '.vuetable-slot',
			'@resolve-button' => '.btn.btn-success',
		];
	}

	/**
	 * @param Browser $browser
	 * @param $errors
	 */
	public function assertSeePhpErrors(Browser $browser, $errors): void {
		foreach ($errors as $error) {
			$browser->assertSee($error->page)
				->assertSee($error->errorable->message);
		}
	}


	/**
	 * @param Browser $browser
	 * @param Collection $errors
	 */
	public function assertSeeJsErrors(Browser $browser, Collection $errors): void {
		foreach ($errors as $error) {
			$browser->assertSee($error->page)
				->assertSee($error->errorable->class);
		}
	}
}
