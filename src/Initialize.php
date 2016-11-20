<?php namespace Eyewill\TucleCore;

use File;
use Route;
use Symfony\Component\Process\Process;

class Initialize
{
  public function generator()
  {
    yield $this->copyAuthView();

    yield $this->installLaravelDebugbar();

    yield $this->updateHttpRoutes();
  }

  public function installLaravelDebugbar()
  {
    if (class_exists('Barryvdh\Debugbar\LaravelDebugbar'))
    {
      return 'LaravelDebugbar already exists.';
    }

    $process = new Process('composer require barryvdh/laravel-debugbar');
    $process->setTimeout(300);
    $process->run();
    if ($process->isSuccessful()) {
      return 'installed laravel-debugbar';
    } else {
      return $process->getOutput();
    }
  }

  public function updateHttpRoutes()
  {
    $homeRoute = Route::getRoutes()->getByName('home');
    $routesPath = app_path().'/Http/routes.php';
    if (is_null($homeRoute))
    {
      File::put($routesPath, <<<__PHP___
<?php

/**
 * Index
 * route GET /
 * name home
*/
Route::get('/', function () {
  return view('tucle::tucle.index');
})->middleware('auth')->name('home');
__PHP___
      );
      return $routesPath.' generated.';
    }
    else {
      return $routesPath.' already exists.';
    }
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

}