<?php namespace Eyewill\TucleCore;

use Eyewill\TucleCore\Contracts\Presenter\ModelEditPresenter as EditModelPresenterContracts;
use Eyewill\TucleCore\Http\Presenters\ModelEditPresenter;
use Eyewill\TucleCore\Http\Presenters\TucleIndexPresenter;
use File;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

class TucleCoreServiceProvider extends ServiceProvider
{
  protected $defer = false;

  protected $commands = [
    'Eyewill\TucleCore\Console\Commands\TucleInit',
  ];

  /**
   * Bootstrap the application services.
   *
   * @return void
   */
  public function boot()
  {
    \View::addNamespace('tucle', [
      __DIR__.'/../views',
    ]);

    if (class_exists('Barryvdh\Debugbar\ServiceProvider') && $this->app->config->get('app.debug'))
    {
      $this->app->register(new \Barryvdh\Debugbar\ServiceProvider($this->app));
    }

    $this->app->make('view')->share('tucle', $this->app->make('Eyewill\TucleCore\Http\Presenters\TuclePresenter'));

    if (!$this->app->routesAreCached())
    {
      $this->app->router->group([
        'middleware' => 'web',
        'namespace' => 'App\Http\Controllers',
      ], function (Router $router) {
        $router->auth();
      });

      $this->app->router->group([
        'middleware' => ['web', 'auth'],
      ], function (Router $router) {
        foreach (File::glob(app_path('Http/routes/*.php')) as $file)
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
    $this->app->bind('Eyewill\TucleCore\Contracts\Renderer\FormElementRenderer', 'Eyewill\TucleCore\Renderer\FormElementRenderer');
    $this->app->singleton(EditModelPresenterContracts::class, ModelEditPresenter::class);
    $this->app->singleton('TucleIndexPresenter', TucleIndexPresenter::class);

    $this->commands($this->commands);
  }

  public function provides()
  {

  }
}
