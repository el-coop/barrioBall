<?php

namespace App\Providers;

use App\Models\Admin;
use App\Models\Errors\Error;
use App\Models\Match;
use App\Policies\AdminPolicy;
use App\Policies\ErrorPolicy;
use App\Policies\MatchPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Match::class => MatchPolicy::class,
		Error::class => ErrorPolicy::class,
		Admin::class => AdminPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
