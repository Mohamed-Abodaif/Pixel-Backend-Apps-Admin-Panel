<?php

namespace App\Providers;

use App\Observers\userObserver;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use App\Models\WorkSector\UsersModule\User;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        User::observe(userObserver::class);
    }
}
