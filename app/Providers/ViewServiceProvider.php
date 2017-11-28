<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use View;

class ViewServiceProvider extends ServiceProvider {
	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot(): void {
		View::composer('*', function ($view) {
			$view->with('user', $this->app->request->user());
		});

	}

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register(): void {
		//
	}
}
