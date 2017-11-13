<?php

namespace Tests\Browser\Pages\Match;

use App\Models\Match;
use Laravel\Dusk\Browser;
use Laravel\Dusk\Page as BasePage;

class ShowPage extends BasePage {
	protected $match;

	public function __construct(Match $match) {
		$this->match = $match;
	}

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
		return action('Match\MatchController@showMatch', $this->match, false);
	}

	/**
	 * Get the element shortcuts for the page.
	 *
	 * @return array
	 */
	public function elements() {
		return [
			'@player-remove' => '.list-group-item > .btn.btn-danger',
			'@message-modal' => '#listsModal',
			'@delete-button' => '.btn.btn-danger.col-12.col-md-6.col-lg-4',
			'@join-button' => '.btn.btn-success.sm-btn-block > .fa-plus-circle',
			'@leave-button' => '.btn.btn-warning.sm-btn-block',
			'@accept-button' => '.list-group-item > .btn-group > .btn.btn-success',
			'@reject-button' => '.list-group-item > .btn-group > .btn.btn-danger',
			'@repeat-button' => '.btn.btn-success.sm-btn-block > .fa-repeat',
			'@dropdown-button' => '.dropdown-toggle.btn',
			'@cancel-join-button' => '.dropdown-toggle.btn + .dropdown-menu  .dropdown-item'
		];
	}

	/**
	 * @param Browser $browser
	 * @param string $message
	 */
	public function fillModalMessage(Browser $browser, string $message): void {
		$browser->whenAvailable('@message-modal', function ($modal) use($message){
			$modal->type('.form-control', $message)
				->press('.btn.btn-block');
		});
	}
}
