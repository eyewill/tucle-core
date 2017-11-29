<?php namespace Eyewill\TucleCore\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

/**
 * Class EventServiceProvider
 * @package Eyewill\TucleCore\Providers
 * @deprecated Make from files/Providers/EventServiceProvider.stub in tucle:init
 */
class EventServiceProvider extends ServiceProvider
{
  protected $listen = [
    'Illuminate\Auth\Events\Login' => [
      'Eyewill\TucleCore\Listeners\LoggedIn',
    ],
    'Illuminate\Auth\Events\Logout' => [
      'Eyewill\TucleCore\Listeners\LoggedOut',
    ],
  ];

  /**
   * Register any other events for your application.
   *
   * @param  \Illuminate\Contracts\Events\Dispatcher  $events
   * @return void
   */
  public function boot(DispatcherContract $events)
  {
    parent::boot($events);

    //
  }
}
