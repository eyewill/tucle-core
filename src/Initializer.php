<?php namespace Eyewill\TucleCore;

use Illuminate\Container\Container;

class Initializer
{
  protected $app;
  protected $basePath;
  protected $publicPath;
  protected $resourcePath;
  protected $composer;
  protected $filesystem;
  protected $router;
  protected $force;
  protected $only;
  protected $allTasks = [
    'assets',
    'packages',
    'auth',
    'composer',
    'config',
    'routes',
  ];

  protected $tasks = [];

  public function __construct(Container $container, ComposerManager $composer, $basePath, $publicPath, $resourcePath, $force = false, $only = null)
  {
    $this->app = $container;
    $this->composer = $composer;
    $this->basePath = $basePath;
    $this->publicPath = $publicPath;
    $this->resourcePath = $resourcePath;

    if (!$this->app['files']->exists($this->basePath.'/.tucle'))
    {
      $this->force = true;
    }
    else
    {
      $this->force = $force;
    }
    if (is_null($only))
    {
      $this->tasks = $this->allTasks;
    }
    else
    {
      $this->tasks = explode(',', $only);
    }

  }

  public function getAllTasks()
  {
    return $this->allTasks;
  }

  public function generator()
  {
    if (in_array('assets', $this->tasks))
    {
      yield $this->copyAssetsSass();
      yield $this->copyAssets('ckeditor');
      yield $this->copyAssets('datatables');
      yield $this->copyAssets('datatables-i18n');
      yield $this->copyAssets('jquery-datatables-checkboxes');
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
      yield $this->composer->add('codesleeve/laravel-stapler', '1.0.*');
      yield $this->composer->add('barryvdh/laravel-debugbar', '^2.3');
      yield $this->composer->add('barryvdh/laravel-ide-helper', '^2.2');
      yield $this->composer->scripts('php artisan ide-helper:generate', 1);
      yield $this->composer->scripts('php artisan ide-helper:meta', 2);
      yield $this->composer->add('primalbase/laravel5-migrate-build', 'dev-master');
      yield $this->composer->add('primalbase/laravel5-view-builder', 'dev-master');
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

    $this->app['files']->put($this->basePath.'/.tucle', 'installed.');
  }

  public function copyAssetsSass()
  {
    $path = $this->resourcePath.'/assets/sass/app.scss';
    $dest = __DIR__.'/../files/assets/sass/app.scss';
    if (!$this->force && $this->app['files']->exists($path))
    {
      return $path.' already exists';
    }

    $scss = '';
    $pos = strpos(__DIR__, 'packages');
    if ($pos === false)
      $pos = strpos(__DIR__, 'vendor');
    $dir = substr(__DIR__, $pos).'/../resources/assets/sass';
    $scss.= '@import "'.$dir.'/tucle";'.PHP_EOL;

    $scss.= file_get_contents($dest);
    file_put_contents($path, $scss);

    return $path.' copied.';
  }

  public function copyAssets($asset)
  {
    if (!$this->force && $this->app['files']->exists($this->resourcePath.'/'.'assets/'.$asset))
    {
      return $this->resourcePath.'/'.'assets/'.$asset.' already exists';
    }

    $this->app['files']->copyDirectory(__DIR__.'/../files/assets/'.$asset, $this->resourcePath.'/'.'assets/'.$asset);

    return 'assets/'.$asset.' copied.';
  }

  public function copyAuthView()
  {
    if (!$this->force && $this->app['files']->exists($this->resourcePath.'/'.'views/auth'))
    {
      return $this->resourcePath.'/'.'views/auth'.' already exists';
    }

    $this->app['files']->copyDirectory(__DIR__.'/../files/auth', $this->resourcePath.'/'.'views/auth');

    return 'auth view copied.';
  }

  public function copyBower()
  {
    $path = $this->basePath.'/'.'bower.json';
    if (!$this->force && $this->app['files']->exists($path))
    {
      return $path.' already exists.';
    }

    $this->app['files']->copy(__DIR__.'/../files/bower.json', $path);

    return $path.' copied.';
  }

  public function copyGulpfile()
  {
    $path = $this->basePath.'/'.'gulpfile.js';
    if (!$this->force && $this->app['files']->exists($path))
    {
      return $path.' already exists.';
    }

    $this->app['files']->copy(__DIR__.'/../files/gulpfile.js', $path);

    return $path.' copied.';
  }

  public function updateHttpRoutes()
  {
    $homeRoute = $this->app['router']->getRoutes()->getByName('home');
    $routesPath = $this->app['path'].'/Http/routes.php';
    if (!$this->force && !is_null($homeRoute))
    {
      return $routesPath.' already exists.';
    }

    $this->app['files']->put($routesPath, <<<__PHP__
<?php

/**
 * Index
 * route GET /
 * name home
*/
use Eyewill\TucleCore\Http\Presenters\TucleHomePresenter;

Route::get('/', function (TucleHomePresenter \$presenter) {

  \$modules = config('tucle.modules', []);
  \$entries = [];
  foreach (\$modules as \$module)
  {
    \$entries[] = app('App\\\\Http\\\\Presenters\\\\'.studly_case(\$module).'Presenter');
  }

  return view('tucle::home.index', [
    'entries' => \$entries,
    'presenter' => \$presenter,
  ]);
})->middleware('auth')->name('home');
__PHP__
      );

      return $routesPath.' generated.';
  }

  public function makeConfigFile()
  {
    $configFilePath = $this->basePath.'/'.'config/tucle.php';
    if (!$this->force && $this->app['files']->exists($configFilePath)) {
      return $configFilePath . ' already exists.';
    }

    $this->app['files']->put($configFilePath, <<<__PHP__
<?php

return [
  
  'brand' => 'TUCLE5',
  
  'modules' => [],
  
  'front_url' => env('FRONT_URL', 'http://localhost')
];
__PHP__
    );

    return $configFilePath.' generated.';
  }
}