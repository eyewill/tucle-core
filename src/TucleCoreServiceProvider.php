<?php namespace Eyewill\TucleCore;

use Eyewill\TucleCore\Contracts\Presenter\ModelEditPresenter as EditModelPresenterContracts;
use Eyewill\TucleCore\Http\Presenters\ModelEditPresenter;
use Eyewill\TucleCore\Http\Presenters\TucleIndexPresenter;
use Illuminate\Support\ServiceProvider;

class TucleCoreServiceProvider extends ServiceProvider
{
  protected $defer = false;

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

    $this->app->make('view')->share('tucle', $this->app->make('Eyewill\TucleCore\Http\Presenters\TuclePresenter'));
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
  }

  public function provides()
  {

  }
}
