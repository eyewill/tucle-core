<?php namespace Eyewill\TucleCore;

use Exception;
use Eyewill\TucleBuilder\Factories\RequestsBuilderFactory;
use Eyewill\TucleCore\Contracts\Initializer as InitializerContracts;
use Eyewill\TucleCore\Database\Migrations\MigrationCreator;
use Illuminate\Container\Container;
use Illuminate\Foundation\Providers\ArtisanServiceProvider;

class Initializer implements InitializerContracts
{
  protected $app;
  protected $basePath;
  protected $publicPath;
  protected $resourcePath;
  protected $configPath;
  protected $providerPath;
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
    'eventlog',
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
    }

    if (in_array('config', $this->tasks))
    {
      yield $this->makeConfigFile();
      yield $this->makeAppConfigFile();
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
      yield $this->copyAuthView();
    }

    if (in_array('routes', $this->tasks))
    {
      yield $this->makeHttpRoutes();
    }

    if (in_array('providers', $this->tasks))
    {
      yield $this->makeEventServiceProvider();
      yield $this->makeAuthServiceProvider();
      yield $this->makeRouteServiceProvider();
    }

    if (in_array('layout', $this->tasks))
    {
      yield $this->copyLayout();
    }

    if (in_array('lang', $this->tasks))
    {
      yield $this->copyLang();
    }

    if (in_array('eventlog', $this->tasks))
    {
      yield $this->makeEventLogMigrationFile();
      yield $this->makeEventLogModel();
      yield $this->makeEventLogPresenter();
      yield $this->makeEventLogRoutes();
      yield $this->makeEventLogViews();
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

  public function makeConfigFile()
  {
    $configFilePath = $this->configPath.'/tucle.php';
    if (!$this->force && $this->app['files']->exists($configFilePath)) {
      return $configFilePath . ' already exists.';
    }

    $templatePath = __DIR__.'/../files/config.php';
    $template = $this->app['files']->get($templatePath);
    $this->app['files']->put($configFilePath, $template);

    return $configFilePath.' generated.';
  }


  public function makeAppConfigFile()
  {
    $configFilePath = $this->configPath.'/app.php';
    if (!$this->force && $this->app['files']->exists($configFilePath)) {
      return $configFilePath . ' already exists.';
    }

    $this->app['files']->put($configFilePath, <<<__PHP__
<?php
return [

  /*
  |--------------------------------------------------------------------------
  | Application Environment
  |--------------------------------------------------------------------------
  |
  | This value determines the "environment" your application is currently
  | running in. This may determine how you prefer to configure various
  | services your application utilizes. Set this in your ".env" file.
  |
  */

  'env' => env('APP_ENV', 'production'),

  /*
  |--------------------------------------------------------------------------
  | Application Debug Mode
  |--------------------------------------------------------------------------
  |
  | When your application is in debug mode, detailed error messages with
  | stack traces will be shown on every error that occurs within your
  | application. If disabled, a simple generic error page is shown.
  |
  */

  'debug' => env('APP_DEBUG', false),

  /*
  |--------------------------------------------------------------------------
  | Application URL
  |--------------------------------------------------------------------------
  |
  | This URL is used by the console to properly generate URLs when using
  | the Artisan command line tool. You should set this to the root of
  | your application so that it is used when running Artisan tasks.
  |
  */

  'url' => env('APP_URL', 'http://localhost'),

  'hosts' => env('APP_HOSTS', [
    'www' => 'tucle5.local',
    'cms' => 'cms.tucle5.local',
  ]),

  /*
  |--------------------------------------------------------------------------
  | Application Timezone
  |--------------------------------------------------------------------------
  |
  | Here you may specify the default timezone for your application, which
  | will be used by the PHP date and date-time functions. We have gone
  | ahead and set this to a sensible default for you out of the box.
  |
  */

  'timezone' => 'Asia/Tokyo',

  /*
  |--------------------------------------------------------------------------
  | Application Locale Configuration
  |--------------------------------------------------------------------------
  |
  | The application locale determines the default locale that will be used
  | by the translation service provider. You are free to set this value
  | to any of the locales which will be supported by the application.
  |
  */

  'locale' => 'ja',

  /*
  |--------------------------------------------------------------------------
  | Application Fallback Locale
  |--------------------------------------------------------------------------
  |
  | The fallback locale determines the locale to use when the current one
  | is not available. You may change the value to correspond to any of
  | the language folders that are provided through your application.
  |
  */

  'fallback_locale' => 'en',

  /*
  |--------------------------------------------------------------------------
  | Encryption Key
  |--------------------------------------------------------------------------
  |
  | This key is used by the Illuminate encrypter service and should be set
  | to a random, 32 character string, otherwise these encrypted strings
  | will not be safe. Please do this before deploying an application!
  |
  */

  'key' => env('APP_KEY'),

  'cipher' => 'AES-256-CBC',

  /*
  |--------------------------------------------------------------------------
  | Logging Configuration
  |--------------------------------------------------------------------------
  |
  | Here you may configure the log settings for your application. Out of
  | the box, Laravel uses the Monolog PHP logging library. This gives
  | you a variety of powerful log handlers / formatters to utilize.
  |
  | Available Settings: "single", "daily", "syslog", "errorlog"
  |
  */

  'log' => env('APP_LOG', 'single'),

  'log_level' => env('APP_LOG_LEVEL', 'debug'),

  /*
  |--------------------------------------------------------------------------
  | Autoloaded Service Providers
  |--------------------------------------------------------------------------
  |
  | The service providers listed here will be automatically loaded on the
  | request to your application. Feel free to add your own services to
  | this array to grant expanded functionality to your applications.
  |
  */

  'providers' => [
    
    /*
     * Laravel Framework Service Providers...
     */
    Illuminate\Auth\AuthServiceProvider::class,
    Illuminate\Broadcasting\BroadcastServiceProvider::class,
    Illuminate\Bus\BusServiceProvider::class,
    Illuminate\Cache\CacheServiceProvider::class,
    Illuminate\Foundation\Providers\ConsoleSupportServiceProvider::class,
    Illuminate\Cookie\CookieServiceProvider::class,
    Illuminate\Database\DatabaseServiceProvider::class,
    Illuminate\Encryption\EncryptionServiceProvider::class,
    Illuminate\Filesystem\FilesystemServiceProvider::class,
    Illuminate\Foundation\Providers\FoundationServiceProvider::class,
    Illuminate\Hashing\HashServiceProvider::class,
    Illuminate\Mail\MailServiceProvider::class,
    Illuminate\Pagination\PaginationServiceProvider::class,
    Illuminate\Pipeline\PipelineServiceProvider::class,
    Illuminate\Queue\QueueServiceProvider::class,
    Illuminate\Redis\RedisServiceProvider::class,
    Illuminate\Auth\Passwords\PasswordResetServiceProvider::class,
    Illuminate\Session\SessionServiceProvider::class,
    Illuminate\Translation\TranslationServiceProvider::class,
    Illuminate\Validation\ValidationServiceProvider::class,
    Illuminate\View\ViewServiceProvider::class,

    /*
     * Application Service Providers...
     */
    App\Providers\AppServiceProvider::class,
    App\Providers\AuthServiceProvider::class,
    App\Providers\EventServiceProvider::class,
    App\Providers\RouteServiceProvider::class,

    Barryvdh\Debugbar\ServiceProvider::class,
    Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class,
    Collective\Html\HtmlServiceProvider::class,
    Codesleeve\LaravelStapler\Providers\L5ServiceProvider::class,

    Eyewill\TucleBuilder\TucleBuilderServiceProvider::class,
    Eyewill\TucleCore\TucleCoreServiceProvider::class,
    
    Primalbase\Migrate\MigrateServiceProvider::class,
    Primalbase\ViewBuilder\ViewBuilderServiceProvider::class,
  ],

  /*
  |--------------------------------------------------------------------------
  | Class Aliases
  |--------------------------------------------------------------------------
  |
  | This array of class aliases will be registered when this application
  | is started. However, feel free to register as many as you wish as
  | the aliases are "lazy" loaded so they don't hinder performance.
  |
  */

  'aliases' => [

    'App' => Illuminate\Support\Facades\App::class,
    'Artisan' => Illuminate\Support\Facades\Artisan::class,
    'Auth' => Illuminate\Support\Facades\Auth::class,
    'Blade' => Illuminate\Support\Facades\Blade::class,
    'Cache' => Illuminate\Support\Facades\Cache::class,
    'Config' => Illuminate\Support\Facades\Config::class,
    'Cookie' => Illuminate\Support\Facades\Cookie::class,
    'Crypt' => Illuminate\Support\Facades\Crypt::class,
    'DB' => Illuminate\Support\Facades\DB::class,
    'Eloquent' => Illuminate\Database\Eloquent\Model::class,
    'Event' => Illuminate\Support\Facades\Event::class,
    'File' => Illuminate\Support\Facades\File::class,
    'Gate' => Illuminate\Support\Facades\Gate::class,
    'Hash' => Illuminate\Support\Facades\Hash::class,
    'Lang' => Illuminate\Support\Facades\Lang::class,
    'Log' => Illuminate\Support\Facades\Log::class,
    'Mail' => Illuminate\Support\Facades\Mail::class,
    'Password' => Illuminate\Support\Facades\Password::class,
    'Queue' => Illuminate\Support\Facades\Queue::class,
    'Redirect' => Illuminate\Support\Facades\Redirect::class,
    'Redis' => Illuminate\Support\Facades\Redis::class,
    'Response' => Illuminate\Support\Facades\Response::class,
    'Route' => Illuminate\Support\Facades\Route::class,
    'Schema' => Illuminate\Support\Facades\Schema::class,
    'Session' => Illuminate\Support\Facades\Session::class,
    'Storage' => Illuminate\Support\Facades\Storage::class,
    'URL' => Illuminate\Support\Facades\URL::class,
    'Validator' => Illuminate\Support\Facades\Validator::class,
    'View' => Illuminate\Support\Facades\View::class,

    'Html' => Collective\Html\HtmlFacade::class,
    'Form' => Collective\Html\FormFacade::class,
  ],
];
__PHP__
    );

    return $configFilePath.' generated.';
  }

  public function makeAuthServiceProvider()
  {
    $filePath = $this->providerPath.'/AuthServiceProvider.php';
    if (!$this->force && $this->app['files']->exists($filePath)) {
      return $filePath . ' already exists.';
    }

    $templatePath = __DIR__.'/../files/Providers/AuthServiceProvider.php';
    $template = $this->app['files']->get($templatePath);
    $this->app['files']->put($filePath, $template);

    return $filePath.' generated.';
  }

  public function makeEventServiceProvider()
  {
    $filePath = $this->providerPath.'/EventServiceProvider.php';
    if (!$this->force && $this->app['files']->exists($filePath)) {
      return $filePath . ' already exists.';
    }

    $templatePath = __DIR__.'/../files/Providers/EventServiceProvider.php';
    $template = $this->app['files']->get($templatePath);
    $this->app['files']->put($filePath, $template);

    return $filePath.' generated.';
  }

  public function makeRouteServiceProvider()
  {
    $filePath = $this->providerPath.'/RouteServiceProvider.php';
    if (!$this->force && $this->app['files']->exists($filePath)) {
      return $filePath . ' already exists.';
    }

    $templatePath = __DIR__.'/../files/Providers/RouteServiceProvider.php';
    $template = $this->app['files']->get($templatePath);
    $this->app['files']->put($filePath, $template);

    return $filePath.' generated.';
  }

  public function makeEventLogMigrationFile()
  {
    try {
      $this->migrationCreator->create('create_event_logs_table', $this->databasePath.DIRECTORY_SEPARATOR.'migrations', 'event_logs', 'event_logs');
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
}