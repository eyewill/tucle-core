<?php namespace Eyewill\TucleCore;

class Initializer extends Generator
{
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
    'seeds',
    'helpers',
  ];

  public function generator()
  {
    if (in_array('composer', $this->tasks))
    {
      yield $this->composer->add('laravelcollective/html', '5.3.*');
      yield $this->composer->add('codesleeve/laravel-stapler', '1.0.*');
      yield $this->composer->add('barryvdh/laravel-debugbar', '~2.4');
      yield $this->composer->add('barryvdh/laravel-ide-helper', '^2.2');
      yield $this->composer->scripts('php artisan ide-helper:generate', 1);
      yield $this->composer->scripts('php artisan ide-helper:meta', 2);
      yield $this->composer->add('primalbase/laravel5-migrate-build', 'dev-master');
      yield $this->composer->add('primalbase/view-builder', 'dev-master');
      yield $this->composer->add('eyewill/tucle-builder', 'dev-master');
      yield $this->composer->add('bugsnag/bugsnag-laravel', '^2.0');
      yield $this->composer->addAutoload('files', 'app/helpers.php');
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
        __DIR__ . '/../files/.env.local.stub',
        $this->basePath.'/.env.local'
      );
      yield $this->makeFromStub(
        __DIR__ . '/../files/.gitignore.stub',
        $this->basePath.'/.gitignore'
      );
    }

    if (in_array('helpers', $this->tasks))
    {
      yield $this->makeFromStub(
        __DIR__.'/../files/helpers.stub',
        $this->app['path'].'/helpers.php'
      );
    }

    if (in_array('composer', $this->tasks))
    {
      yield $this->composer->update();
    }

    if (in_array('assets', $this->tasks))
    {
      yield $this->makeFromStub(
        __dir__.'/../files/assets/app.stub',
        $this->resourcePath.'/assets/sass/app.scss'
      );
      yield $this->makeFromStub(
        __dir__.'/../files/assets/app-dev.stub',
        $this->resourcePath.'/assets/sass/app-dev.scss'
      );
      yield $this->makeFromStub(
        __dir__.'/../files/assets/.gitignore.stub',
        $this->publicPath.'/assets/.gitignore'
      );
    }

    if (in_array('packages', $this->tasks))
    {
      yield $this->makeFromStub(
        __DIR__ . '/../files/packages/package.stub',
        $this->basePath.'/'.'package.json'
      );
      yield $this->makeFromStub(
        __DIR__ . '/../files/packages/bower.stub',
        $this->basePath.'/'.'bower.json'
      );
      yield $this->makeFromStub(
        __DIR__ . '/../files/packages/gulpfile.stub',
        $this->basePath.'/'.'gulpfile.js'
      );
    }

    if (in_array('auth', $this->tasks))
    {
      yield $this->makeMigrationFromStub('users');

      yield $this->makeFromStub(
        __DIR__.'/../files/auth/views',
        $this->resourcePath.'/'.'views/auth'
      );

      yield $this->makeFromStub(
        __DIR__.'/../files/auth/Http/Controllers/LoginController.stub',
        $this->app['path'].'/Http/Controllers/Auth/LoginController.php'
      );
    }

    if (in_array('routes', $this->tasks))
    {
      yield $this->makeFromStub(
        __DIR__ . '/../files/routes/admin.stub',
        $this->basePath.'/routes/admin.php'
      );

      yield $this->makeFromStub(
        __DIR__ . '/../files/routes/web.stub',
        $this->basePath.'/routes/web.php'
      );
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
      yield $this->makeMigrationFromStub('event_logs');
      yield $this->makeEventLogModel();
      yield $this->makeEventLogPresenter();
      yield $this->makeFromStub(
        __DIR__.'/../files/eventlog/routes.stub',
        $this->basePath.'/routes/admin/eventlog.php'
      );
      yield $this->makeEventLogViews();

      $this->composer->dumpAutoload();
    }

    if (in_array('exception', $this->tasks))
    {
      yield $this->makeFromStub(
        __DIR__.'/../files/Exceptions/Handler.stub',
        $this->exceptionPath.'/Handler.php'
      );
      yield $this->makeFromStub(
        __DIR__.'/../files/Exceptions/views/common.blade.stub',
        $this->resourcePath.'/views/errors/common.blade.php'
      );
    }

    if (in_array('seeds', $this->tasks))
    {
      yield $this->makeFromStub(
        __DIR__.'/../files/database/seeds/DatabaseSeeder.stub',
        $this->databasePath.'/seeds/DatabaseSeeder.php'
      );
      yield $this->makeFromStub(
        __DIR__.'/../files/database/seeds/UsersTableSeeder.stub',
        $this->databasePath.'/seeds/UsersTableSeeder.php'
      );
      $this->composer->dumpAutoload();
    }

    $this->app['files']->put($this->basePath.'/.tucle', 'installed.');
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
}