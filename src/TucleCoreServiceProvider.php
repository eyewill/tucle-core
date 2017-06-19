<?php namespace Eyewill\TucleCore;

use Eyewill\TucleCore\Console\Commands\TucleInit;
use Eyewill\TucleCore\Console\Commands\TucleMakeUser;
use Eyewill\TucleCore\Contracts\Initializer;
use Eyewill\TucleCore\Contracts\ModuleManager;
use Eyewill\TucleCore\Contracts\NavigationManager;
use Eyewill\TucleCore\Factories\Forms\PasswordFactory;
use Eyewill\TucleCore\Factories\Forms\StaticFactory;
use Eyewill\TucleCore\Factories\InitializerFactory;
use Eyewill\TucleCore\Factories\Forms\CheckboxFactory;
use Eyewill\TucleCore\Factories\Forms\DateFactory;
use Eyewill\TucleCore\Factories\Forms\DatetimeFactory;
use Eyewill\TucleCore\Factories\Forms\FileFactory;
use Eyewill\TucleCore\Factories\Forms\GroupFactory;
use Eyewill\TucleCore\Factories\Forms\ImageFactory;
use Eyewill\TucleCore\Factories\Forms\PriceFactory;
use Eyewill\TucleCore\Factories\Forms\PublishedFactory;
use Eyewill\TucleCore\Factories\Forms\RadioFactory;
use Eyewill\TucleCore\Factories\Forms\SelectFactory;
use Eyewill\TucleCore\Factories\Forms\SeparatorFactory;
use Eyewill\TucleCore\Factories\Forms\TextFactory;
use Eyewill\TucleCore\Factories\Forms\TextareaFactory;
use Illuminate\Support\ServiceProvider;

class TucleCoreServiceProvider extends ServiceProvider
{
  protected $defer = false;

  protected $commands = [
    TucleInit::class,
    TucleMakeUser::class,
  ];

  protected $formFactories = [
    'checkbox' => CheckboxFactory::class,
    'date' => DateFactory::class,
    'datetime' => DatetimeFactory::class,
    'file' => FileFactory::class,
    'group' => GroupFactory::class,
    'image' => ImageFactory::class,
    'price' => PriceFactory::class,
    'published' => PublishedFactory::class,
    'radio' => RadioFactory::class,
    'select' => SelectFactory::class,
    'separator' => SeparatorFactory::class,
    'text' => TextFactory::class,
    'textarea' => TextareaFactory::class,
    'password' => PasswordFactory::class,
    'static' => StaticFactory::class,
  ];

  protected $filterFactories = [
    'select' => \Eyewill\TucleCore\Factories\Filters\SelectFactory::class,
    'checkbox' => \Eyewill\TucleCore\Factories\Filters\CheckboxFactory::class,
    'radio' => \Eyewill\TucleCore\Factories\Filters\RadioFactory::class,
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

    $this->app['view']->share('tucle',
      $this->app->make('Eyewill\TucleCore\Http\Presenters\TuclePresenter')
    );
  }

  /**
   * Register the application services.
   *
   * @return void
   */
  public function register()
  {
    $this->app->singleton(Initializer::class, InitializerFactory::class);
    $this->app->singleton(ModuleManager::class, function () {
      return new \Eyewill\TucleCore\ModuleManager(config('tucle.modules', []));
    });
    $this->app->singleton(NavigationManager::class, function () {
      return new \Eyewill\TucleCore\NavigationManager(config('tucle.navigation', []));
    });
    foreach ($this->formFactories as $type => $concrete)
    {
      $this->app->bind('form.'.$type, $concrete);
    }
    foreach ($this->filterFactories as $type => $concrete)
    {
      $this->app->bind('filter.'.$type, $concrete);
    }

    $this->commands($this->commands);
  }

  public function provides()
  {

  }
}
