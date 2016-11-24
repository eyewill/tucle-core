<?php namespace Eyewill\TucleCore;

use File;
use Route;

class Initializer
{
  protected $composer;
  protected $force;
  protected $registeredTasks = [
    'assets',
    'packages',
    'auth',
    'composer',
    'config',
    'routes',
  ];

  protected $tasks = [];

  public function __construct($force = false, $only = null)
  {
    if (!File::exists(base_path('.tucle')))
    {
      $this->force = true;
    }
    else
    {
      $this->force = $force;
    }
    if (is_null($only))
    {
      $this->tasks = $this->registeredTasks;
    }
    else
    {
      $this->tasks = explode(',', $only);
    }

    $this->composer = new ComposerManager;
  }

  public function getRegisteredTasks()
  {
    return $this->registeredTasks;
  }

  public function generator()
  {
    if (in_array('assets', $this->tasks))
    {
      yield $this->copyAssetsSass();
      yield $this->copyAssetsCKEditor();
    }

    if (in_array('packages', $this->tasks))
    {
      yield $this->copyBower();
      yield $this->copyGulpfile();
    }

    if (in_array('auth', $this->tasks))
    {
      yield $this->copyAuthView();
    }

    if (in_array('composer', $this->tasks))
    {
      yield $this->composer->add('laravelcollective/html', '5.2.*');
      yield $this->composer->add('codesleeve/stapler', '1.0.*');
      yield $this->composer->add('barryvdh/laravel-debugbar', '^2.3');
      yield $this->composer->add('barryvdh/laravel-ide-helper', '^2.2');
      yield $this->composer->scripts('php artisan ide-helper:generate', 1);
      yield $this->composer->scripts('php artisan ide-helper:meta', 2);
      yield $this->composer->add('primalbase/laravel5-migrate-build', '0.0.0.*');
      yield $this->composer->update();
    }

    if (in_array('config', $this->tasks))
    {
      yield $this->makeConfigFile();
    }

    if (in_array('routes', $this->tasks))
    {
      yield $this->updateHttpRoutes();
    }

    File::put(base_path('.tucle'), 'installed.');
  }

  public function copyAssetsSass()
  {
    $path = resource_path('assets/sass/app.scss');
    $dest = __DIR__.'/../files/assets/sass/app.scss';
    if (!$this->force && File::exists($path))
    {
      return $path.' already exists';
    }

    $scss = '';
    $pos = strpos(__DIR__, 'packages');
    if ($pos === false)
      $pos = strpos(__DIR__, 'vendor');
    $dir = substr(__DIR__, $pos).'/../resources/assets/sass';
    $scss.= '@import "'.$dir.'/tucle";'.PHP_EOL;

    $scss.= File::get($dest);
    File::put($path, $scss);

    return $path.' copied.';
  }

  public function copyAssetsCKEditor()
  {
    if (!$this->force && File::exists(resource_path('assets/ckeditor')))
    {
      return resource_path('assets/ckeditor').' already exists';
    }

    File::copyDirectory(__DIR__.'/../files/assets/ckeditor', resource_path('assets/ckeditor'));

    return 'assets/ckeditor copied.';
  }

  public function copyAuthView()
  {
    if (!$this->force && File::exists(resource_path('views/auth')))
    {
      return resource_path('views/auth').' already exists';
    }

    File::copyDirectory(__DIR__.'/../files/auth', resource_path('views/auth'));

    return 'auth view copied.';
  }

  public function copyBower()
  {
    $path = base_path('bower.json');
    if (!$this->force && File::exists($path))
    {
      return $path.' already exists.';
    }

    File::copy(__DIR__.'/../files/bower.json', $path);

    return $path.' copied.';
  }

  public function copyGulpfile()
  {
    $path = base_path('gulpfile.js');
    if (!$this->force && File::exists($path))
    {
      return $path.' already exists.';
    }

    File::copy(__DIR__.'/../files/gulpfile.js', $path);

    return $path.' copied.';
  }

  public function updateHttpRoutes()
  {
    $homeRoute = Route::getRoutes()->getByName('home');
    $routesPath = app_path().'/Http/routes.php';
    if (!$this->force && !is_null($homeRoute))
    {
      return $routesPath.' already exists.';
    }

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

  public function makeConfigFile()
  {
    $configFilePath = base_path('config/tucle.php');
    if (!$this->force && File::exists($configFilePath)) {
      return $configFilePath . ' already exists.';
    }

    File::put($configFilePath, <<<__PHP__
<?php

return [
  
  'brand' => 'TUCLE5',
  
  'modules' => [],
  
];
__PHP__
    );

    return $configFilePath.' generated.';
  }
}