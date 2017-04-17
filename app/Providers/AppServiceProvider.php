<?php

namespace App\Providers;

use App\Models\Errors\JsError;
use App\Models\Errors\PhpError;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
		Relation::morphMap([
			'PHPError' => PhpError::class,
			'JSError' => JsError::class,
		]);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
		if ($this->app->environment() !== 'production') {
			$this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
		}
    }
}
