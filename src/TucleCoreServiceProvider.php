<?php namespace Eyewill\TucleCore;

use Eyewill\TucleCore\Factories\InitializerFactory;
use Illuminate\Support\ServiceProvider;

class TucleCoreServiceProvider extends ServiceProvider
{
  protected $defer = false;

  protected $commands = [
    'Eyewill\TucleCore\Console\Commands\TucleInit',
  ];

  protected $providers = [
    'Collective\Html\HtmlServiceProvider',
    'Codesleeve\LaravelStapler\Providers\L5ServiceProvider',
  ];

  protected $providersLocal = [
    'Barryvdh\Debugbar\ServiceProvider',
    'Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider',
    'Primalbase\Migrate\MigrateServiceProvider',
  ];

  /**
   * Bootstrap the application services.
   *
   * @return void
   */
  public function boot()
  {
    $this->app['view']->addNamespace('tucle', [
      __DIR__ . '/../resources/views',
    ]);

    foreach ($this->providers as $provider)
    {
      if (class_exists($provider))
      {
        $this->app->register($provider);
      }
    }

    if ($this->app->environment('local'))
    {
      foreach ($this->providersLocal as $provider)
      {
        if (class_exists($provider))
        {
          $this->app->register($provider);
        }
      }
    }

    $this->app['view']->share('tucle',
      $this->app->make('Eyewill\TucleCore\Http\Presenters\TuclePresenter')
    );

    if (!$this->app->routesAreCached())
    {
      $this->app['router']->group([
        'middleware' => 'web',
        'namespace' => 'App\Http\Controllers',
      ], function ($router) {
        $router->auth();
      });

      $this->app['router']->group([
        'middleware' => ['web', 'auth'],
      ], function ($router) {
        foreach ($this->app['files']->glob(app_path('Http/routes/*.php')) as $file)
        {
          include $file;
        }
      });
    }
  }

  /**
   * Register the application services.
   *
   * @return void
   */
  public function register()
  {
    $this->app->singleton(\Eyewill\TucleCore\Contracts\Initializer::class, InitializerFactory::class);

    $this->commands($this->commands);
  }

  public function provides()
  {

  }
}
