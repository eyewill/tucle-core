<?php namespace Eyewill\TucleCore;

use File;
use Route;

class Initializer
{
  protected $composer;

  public function __construct($force, $only)
  {
    $this->composer = new ComposerManager;
  }

  public function generator()
  {
    yield $this->copyAssetsSass();

    yield $this->copyAuthView();

    yield $this->composer->add('laravelcollective/html', '5.2.*');

    yield $this->composer->add('codesleeve/stapler', '1.0.*');

    yield $this->composer->add('barryvdh/laravel-debugbar', '^2.3');

    yield $this->composer->add('barryvdh/laravel-ide-helper', '^2.2');
    yield $this->composer->scripts('php artisan ide-helper:generate', 1);
    yield $this->composer->scripts('php artisan ide-helper:meta', 2);

    yield $this->composer->add('primalbase/laravel5-migrate-build', '0.0.0.*');

    yield $this->composer->update();

    yield $this->makeConfigFile();

    yield $this->updateHttpRoutes();
  }

  public function copyAssetsSass()
  {
    if (File::exists(resource_path('assets/sass')))
    {
      return resource_path('assets/sass').' already exists';
    }

    File::copyDirectory(__DIR__.'/../files/assets/sass', resource_path('assets/sass'));

    return 'assets/sass copied.';
  }

  public function copyAuthView()
  {
    if (File::exists(resource_path('views/auth')))
    {
      return resource_path('views/auth').' already exists';
    }

    File::copyDirectory(__DIR__.'/../files/auth', resource_path('views/auth'));

    return 'auth view copied.';
  }

  public function updateHttpRoutes()
  {
    $homeRoute = Route::getRoutes()->getByName('home');
    $routesPath = app_path().'/Http/routes.php';
    if (is_null($homeRoute))
    {
      File::put($routesPath, <<<__PHP__
<?php

/**
 * Index
 * route GET /
 * name home
*/
Route::get('/', function () {
  return view('tucle::tucle.index');
})->middleware('auth')->name('home');
__PHP__
      );
      return $routesPath.' generated.';
    }
    else {
      return $routesPath.' already exists.';
    }
  }

  public function makeConfigFile()
  {
    $configFilePath = base_path('config/tucle.php');
    if (File::exists($configFilePath)) {
      return $configFilePath . ' already exists.';
    }

    File::put($configFilePath, <<<__PHP__
<?php

return [
  
  'modules' => [],
  
];
__PHP__
    );

    return $configFilePath.' generated.';
  }
}