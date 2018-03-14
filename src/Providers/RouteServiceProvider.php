<?php

namespace Eyewill\TucleCore\Providers;

use File;
use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

/**
 * Class RouteServiceProvider
 * @package Eyewill\TucleCore\Providers
 * @deprecated Make from files/Providers/RouteServiceProvider.stub in tucle:init
 */
class RouteServiceProvider extends ServiceProvider
{
  /**
   * This namespace is applied to your controller routes.
   *
   * In addition, it is set as the URL generator's root namespace.
   *
   * @var string
   */
  protected $namespace = 'App\Http\Controllers';

  /**
   * Define your route model bindings, pattern filters, etc.
   *
   * @param  \Illuminate\Routing\Router  $router
   * @return void
   */
  public function boot(Router $router)
  {
    $router->middleware('json', \Eyewill\TucleCore\Http\Middleware\Json::class);

    parent::boot($router);
  }

  /**
   * Define the routes for the application.
   *
   * @param  \Illuminate\Routing\Router  $router
   * @return void
   */
  public function map(Router $router)
  {
    $this->mapWebRoutes($router);
  }

  /**
   * Define the "web" routes for the application.
   *
   * These routes all receive session state, CSRF protection, etc.
   *
   * @param  \Illuminate\Routing\Router  $router
   * @return void
   */
  protected function mapWebRoutes(Router $router)
  {
    $router->group([
      'namespace' => $this->namespace,
      'middleware' => 'cms',
    ], function ($router) {
      require app_path('Http/routes.php');
      foreach ($this->app['files']->glob($this->app['path'].'/Http/routes/*.php') as $file)
      {
        include $file;
      }
    });

    $router->group([
      'namespace' => $this->namespace,
      'middleware' => 'web',
    ], function ($router) {
      $router->auth();
    });
  }

}