<?php

namespace App\Providers;

use App\Events\Match\JoinRequest;
use App\Events\Match\UserJoined;
use App\Listeners\Match\SendJoinRequestAcceptedNotification;
use App\Listeners\Match\SendJoinRequestNotification;
use App\Listeners\Match\SendUserJoinedNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        JoinRequest::class => [
            SendJoinRequestNotification::class,
        ],
		UserJoined::class => [
			SendUserJoinedNotification::class,
			SendJoinRequestAcceptedNotification::class
		]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
