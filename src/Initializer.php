<?php namespace Eyewill\TucleCore;

use File;
use Route;
use Symfony\Component\Process\Process;

class Initializer
{
  protected $composerUpdate = false;

  public function generator()
  {
    yield $this->copyAssetsSass();

    yield $this->copyAuthView();

    yield $this->addLaravelIdeHelper();

    yield $this->addLaravelDebugbar();

    yield $this->addMigrateBuild();

    if ($this->composerUpdate)
    {
      yield $this->composerUpdate();
    }

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

  public function addLaravelIdeHelper()
  {
    $path = base_path('composer.json');
    if (!File::exists($path))
    {
      return 'composer.json not exists.';
    }

    $composerJson = json_decode(File::get($path), true);
    if (!array_has($composerJson, 'require.barryvdh/laravel-ide-helper'))
    {
      array_set($composerJson, 'require.barryvdh/laravel-ide-helper', '^2.2');
      $this->composerUpdate = true;
    }
    $postUpdateCmd = array_get($composerJson, 'scripts.post-update-cmd');
    if (!in_array('php artisan ide-helper:generate', $postUpdateCmd))
    {
      array_splice($postUpdateCmd, 1, 0, 'php artisan ide-helper:generate');
      $this->composerUpdate = true;
    }
    if (!in_array('php artisan ide-helper:meta', $postUpdateCmd))
    {
      array_splice($postUpdateCmd, 2, 0, 'php artisan ide-helper:meta');
      $this->composerUpdate = true;
    }
    array_set($composerJson, 'scripts.post-update-cmd', $postUpdateCmd);

    File::put($path, json_encode($composerJson, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES));

    return 'add laravel-ide-helper to composer.json';
  }

  public function addLaravelDebugbar()
  {
    $path = base_path('composer.json');
    if (!File::exists($path))
    {
      return 'composer.json not exists.';
    }

    $composerJson = json_decode(File::get($path), true);
    if (!array_has($composerJson, 'require.barryvdh/laravel-debugbar'))
    {
      array_set($composerJson, 'require.barryvdh/laravel-debugbar', '^2.3');
      $this->composerUpdate = true;
    }
    File::put($path, json_encode($composerJson, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES));

    return 'add laravel-debugbar to composer.json';
  }

  public function addMigrateBuild()
  {
    $path = base_path('composer.json');
    if (!File::exists($path))
    {
      return 'composer.json not exists.';
    }

    $composerJson = json_decode(File::get($path), true);
    if (!array_has($composerJson, 'require.primalbase/laravel5-migrate-build'))
    {
      array_set($composerJson, 'require.primalbase/laravel5-migrate-build', '0.0.0.*');
      $this->composerUpdate = true;
    }
    File::put($path, json_encode($composerJson, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES));

    return 'add laravel5-migrate-build to composer.json';
  }


  public function composerUpdate()
  {
    $process = new Process('composer update');
    $process->setTimeout(0);
    $process->run();
    return $process->getOutput();
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

}