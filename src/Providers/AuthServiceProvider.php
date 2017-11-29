<?php

namespace Eyewill\TucleCore\Providers;

use Eyewill\TucleCore\Module;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

/**
 * Class AuthServiceProvider
 * @package Eyewill\TucleCore\Providers
 * @deprecated Make from files/Providers/AuthServiceProvider.stub in tucle:init
 */
class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
    ];

    /**
     * Register any application authentication / authorization services.
     *
     * @param  \Illuminate\Contracts\Auth\Access\Gate  $gate
     * @return void
     */
    public function boot(GateContract $gate)
    {
      $this->registerPolicies($gate);

      $gate->before(function ($user, $ability) {

        if ($user->role == 'admin')
        {
          return true;
        }
      });

      /** @var Module $module */
      foreach (module()->all() as $module)
      {
        $gate->define('show-'.$module->name(), function ($user) use ($module) {

          if (is_null($module->model))
          {
            return true;
          }

          return in_array($user->role, explode(',', $module->allows()));
        });
      }

    }
}
