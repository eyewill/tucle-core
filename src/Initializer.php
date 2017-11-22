<?php namespace Eyewill\TucleCore;

use Exception;
use Eyewill\TucleCore\Contracts\Initializer as InitializerContracts;
use Eyewill\TucleCore\Database\Migrations\MigrationCreator;
use Illuminate\Container\Container;

class Initializer implements InitializerContracts
{
  protected $app;
  protected $basePath;
  protected $publicPath;
  protected $resourcePath;
  protected $configPath;
  protected $providerPath;
  protected $databasePath;
  protected $exceptionPath;
  protected $composer;
  protected $migrationCreator;
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
    'providers',
    'layout',
    'lang',
    'kernel',
    'eventlog',
    'exception',
  ];

  protected $tasks = [];

  public function __construct(Container $container, ComposerManager $composer, MigrationCreator $migrationCreator, $force = false, $only = null)
  {
    $this->app = $container;
    $this->composer = $composer;
    $this->migrationCreator = $migrationCreator;
    $this->basePath = $container->basePath();
    $this->publicPath = $container['path.public'];
    $this->resourcePath = $container->basePath().'/resources';
    $this->configPath = $container->basePath().'/config';
    $this->providerPath = $container['path'].'/Providers';
    $this->databasePath = $container->databasePath();
    $this->exceptionPath = $container['path'].'/Exceptions';
    $this->setForce($force);
    $this->setTasks($only);
  }

  public function setForce($force)
  {
    if (!$this->app['files']->exists($this->basePath.'/.tucle'))
    {
      $this->force = true;
      return;
    }

    $this->force = $force;
  }

  public function setTasks($only = null)
  {
    if (is_null($only))
    {
      $this->tasks = $this->allTasks;
      return;
    }

    if (is_array($only))
    {
      $this->tasks = $only;
      return;
    }

    $this->tasks = explode(',', $only);
  }

  public function getAllTasks()
  {
    return $this->allTasks;
  }

  public function generator()
  {
    if (in_array('composer', $this->tasks))
    {
      yield $this->composer->add('laravelcollective/html', '5.2.*');
      yield $this->composer->add('codesleeve/laravel-stapler', '1.0.*');
      yield $this->composer->add('barryvdh/laravel-debugbar', '^2.3');
      yield $this->composer->add('barryvdh/laravel-ide-helper', '^2.2');
      yield $this->composer->scripts('php artisan ide-helper:generate', 1);
      yield $this->composer->scripts('php artisan ide-helper:meta', 2);
      yield $this->composer->add('primalbase/laravel5-migrate-build', 'dev-master');
      yield $this->composer->add('primalbase/view-builder', 'dev-master');
      yield $this->composer->add('eyewill/tucle-builder', 'dev-master');
      yield $this->composer->add('bugsnag/bugsnag-laravel', '^2.0');
    }

    if (in_array('config', $this->tasks))
    {
      yield $this->makeFromStub(
        __DIR__.'/../files/config/tucle.stub',
        $this->configPath.'/tucle.php'
      );
      yield $this->makeFromStub(
        __DIR__.'/../files/config/app.stub',
        $this->configPath.'/app.php'
      );
      yield $this->makeFromStub(
        __DIR__.'/../files/config/.env.stub',
        $this->basePath.'/.env.local'
      );
    }

    if (in_array('composer', $this->tasks))
    {
      yield $this->composer->update();
    }

    if (in_array('assets', $this->tasks))
    {
      yield $this->makeAssetsSass();
      yield $this->copyAssets('ckeditor');
      yield $this->copyAssets('datatables');
      yield $this->copyAssets('datatables-i18n');
      yield $this->copyAssets('jquery-datatables-checkboxes');
      yield $this->copyFilesDirectory('dummy_files');
      yield $this->copyFilesDirectory('dummy_images');
    }

    if (in_array('packages', $this->tasks))
    {
      yield $this->copyBower();
      yield $this->copyGulpfile();
    }

    if (in_array('auth', $this->tasks))
    {
      yield $this->makeFromStub(
        __DIR__.'/../files/auth/views',
        $this->resourcePath.'/'.'views/auth'
      );

      yield $this->makeFromStub(
        __DIR__.'/../files/auth/Http/Controllers/AuthController.stub',
        $this->app['path'].'/Http/Controllers/Auth/AuthController.php'
      );
    }

    if (in_array('routes', $this->tasks))
    {
      yield $this->makeHttpRoutes();
    }

    if (in_array('providers', $this->tasks))
    {
      yield $this->makeFromStub(
        __DIR__.'/../files/Providers/EventServiceProvider.stub',
        $this->providerPath.'/EventServiceProvider.php'
      );
      yield $this->makeFromStub(
        __DIR__.'/../files/Providers/AuthServiceProvider.stub',
        $this->providerPath.'/AuthServiceProvider.php'
      );
      yield $this->makeFromStub(
        __DIR__.'/../files/Providers/RouteServiceProvider.stub',
        $this->providerPath.'/RouteServiceProvider.php'
      );
    }

    if (in_array('layout', $this->tasks))
    {
      yield $this->copyLayout();
    }

    if (in_array('lang', $this->tasks))
    {
      yield $this->copyLang();
    }

    if (in_array('kernel', $this->tasks))
    {
      yield $this->makeFromStub(
        __DIR__.'/../files/Http/Middleware/Role.stub',
        $this->app['path'].'/Http/Middleware/Role.php'
      );
      yield $this->makeFromStub(
        __DIR__.'/../files/Http/Middleware/Expires.stub',
        $this->app['path'].'/Http/Middleware/Expires.php'
      );
      yield $this->makeFromStub(
        __DIR__.'/../files/Http/Middleware/Authenticate.stub',
        $this->app['path'].'/Http/Middleware/Authenticate.php'
      );
      yield $this->makeFromStub(
        __DIR__.'/../files/Http/Kernel.stub',
        $this->app['path'].'/Http/Kernel.php'
      );
    }

    if (in_array('eventlog', $this->tasks))
    {
      yield $this->makeEventLogMigrationFile();
      yield $this->makeEventLogModel();
      yield $this->makeEventLogPresenter();
      yield $this->makeEventLogRoutes();
      yield $this->makeEventLogViews();
    }

    if (in_array('exception', $this->tasks))
    {
      yield $this->makeExceptionHandler();
      yield $this->makeErrorView();
    }
    
    $this->app['files']->put($this->basePath.'/.tucle', 'installed.');
  }

  public function makeAssetsSass()
  {
    $path = $this->resourcePath.'/assets/sass/app.scss';
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

    file_put_contents($path, $scss);

    return $path.' created.';
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

  public function copyFilesDirectory($dirname)
  {
    if (!$this->force && $this->app['files']->exists($this->resourcePath.'/'.$dirname))
    {
      return $this->resourcePath.'/'.$dirname.' already exists';
    }

    $this->app['files']->copyDirectory(__DIR__.'/../files/'.$dirname, $this->resourcePath.'/'.$dirname);

    return $dirname.' copied.';
  }

  public function copyLayout()
  {
    if (!$this->force && $this->app['files']->exists($this->resourcePath.'/views/layout.blade.php'))
    {
      return $this->resourcePath.'/views/layout.blade.php already exists';
    }

    $this->app['files']->copy(__DIR__.'/../files/layout.blade.php', $this->resourcePath.'/views/layout.blade.php');

    return 'layout copied.';
  }

  public function copyLang()
  {
    if (!$this->force && $this->app['files']->exists($this->resourcePath.'/lang'))
    {
      return $this->resourcePath.'/lang already exists';
    }

    $this->app['files']->copyDirectory(__DIR__.'/../files/lang', $this->resourcePath.'/'.'lang');

    return 'lang copied.';
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

  public function makeHttpRoutes()
  {
    $filePath = $this->app['path'].'/Http/routes.php';
    if (!$this->force && $this->app['files']->exists($filePath)) {
      return $filePath . ' already exists.';
    }

    $this->app['files']->makeDirectory(dirname($filePath), 02755, true, true);
    $templatePath = __DIR__.'/../files/routes.stub';
    $template = $this->app['files']->get($templatePath);
    $this->app['files']->put($filePath, $template);

    return $filePath.' generated.';
  }

  public function makeEventLogMigrationFile()
  {
    try {
      $this->migrationCreator->create(
        'create_event_logs_table',
        $this->databasePath.DIRECTORY_SEPARATOR.'migrations',
        'event_logs',
        'event_logs');
      $this->composer->dumpAutoload();
      return 'event log migration file copied.';
    } catch (Exception $e) {
      return 'event log migration file can\'t copied.';
    }

  }

  public function makeEventLogModel()
  {
    $filePath = $this->app['path'].'/EventLog.php';
    if (!$this->force && $this->app['files']->exists($filePath)) {
      return $filePath . ' already exists.';
    }

    $this->app['files']->makeDirectory(dirname($filePath), 02755, true, true);
    $templatePath = __DIR__.'/../files/eventlog/EventLog.stub';
    $template = $this->app['files']->get($templatePath);
    $this->app['files']->put($filePath, $template);

    return $filePath.' generated.';
  }

  public function makeEventLogPresenter()
  {
    $filePath = $this->app['path'].'/Http/Presenters/EventLogPresenter.php';
    if (!$this->force && $this->app['files']->exists($filePath)) {
      return $filePath . ' already exists.';
    }

    $this->app['files']->makeDirectory(dirname($filePath), 02755, true, true);
    $templatePath = __DIR__.'/../files/eventlog/EventLogPresenter.stub';
    $template = $this->app['files']->get($templatePath);
    $this->app['files']->put($filePath, $template);

    return $filePath.' generated.';
  }

  public function makeEventLogRoutes()
  {
    $filePath = $this->app['path'].'/Http/routes/eventlog.php';
    if (!$this->force && $this->app['files']->exists($filePath)) {
      return $filePath . ' already exists.';
    }

    $this->app['files']->makeDirectory(dirname($filePath), 02755, true, true);
    $templatePath = __DIR__.'/../files/eventlog/routes.stub';
    $template = $this->app['files']->get($templatePath);
    $this->app['files']->put($filePath, $template);

    return $filePath.' generated.';
  }

  public function makeEventLogViews()
  {
    $filePath = $this->resourcePath.'/views/event_log/index.blade.php';
    if (!$this->force && $this->app['files']->exists($filePath)) {
      return $filePath . ' already exists.';
    }

    $this->app['files']->makeDirectory(dirname($filePath), 02755, true, true);
    $templatePath = __DIR__.'/../files/eventlog/views/index.blade.stub';
    $template = $this->app['files']->get($templatePath);
    $this->app['files']->put($filePath, $template);

    return $filePath.' generated.';
  }
  
  public function makeExceptionHandler()
  {
    $filePath = $this->exceptionPath.'/Handler.php';
    if (!$this->force && $this->app['files']->exists($filePath)) {
      return $filePath . ' already exists.';
    }

    $this->app['files']->makeDirectory(dirname($filePath), 02755, true, true);
    $templatePath = __DIR__.'/../files/Exceptions/Handler.stub';
    $template = $this->app['files']->get($templatePath);
    $this->app['files']->put($filePath, $template);

    return $filePath.' generated.';
  }
  
  public function makeErrorView()
  {
    $filePath = $this->resourcePath.'/views/errors/common.blade.php';
    if (!$this->force && $this->app['files']->exists($filePath)) {
      return $filePath . ' already exists.';
    }

    $this->app['files']->makeDirectory(dirname($filePath), 02755, true, true);
    $templatePath = __DIR__.'/../files/Exceptions/views/common.blade.stub';
    $template = $this->app['files']->get($templatePath);
    $this->app['files']->put($filePath, $template);

    return $filePath.' generated.';
  }

  protected function makeFromStub($src, $dest, $name = null)
  {
    $name = $name ?: $dest;

    if (!$this->force && $this->app['files']->exists($dest))
    {
      return $dest.' already exists';
    }

    if ($this->app['files']->isDirectory($src))
    {
      $this->app['files']->copyDirectory($src, $dest);
    }
    else
    {
      $this->app['files']->copy($src, $dest);
    }

    return $name.' generated.';
  }
}