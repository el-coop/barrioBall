<?php

namespace App\Providers;

use App\Models\Admin;
use App\Models\Errors\JsError;
use App\Models\Errors\PhpError;
use App\Models\Player;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Laravel\Dusk\DuskServiceProvider;

class AppServiceProvider extends ServiceProvider {
	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot() {
		Relation::morphMap([
			'PHPError' => PhpError::class,
			'JSError' => JsError::class,
			'Admin' => Admin::class,
			'Player' => Player::class
		]);
	}

	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register() {
		if ($this->app->environment('local')) {
			$this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
			$this->app->register(DuskServiceProvider::class);
			$this->app->register(\Barryvdh\Debugbar\ServiceProvider::class);
		}

		if($this->app->environment('testing')){
			$this->app->register(DuskServiceProvider::class);
		}
	}
}
