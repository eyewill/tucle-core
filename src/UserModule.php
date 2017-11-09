<?php namespace Eyewill\TucleCore;

use Exception;
use Eyewill\TucleBuilder\Factories\RequestsBuilderFactory;
use Eyewill\TucleCore\Contracts\Initializer as InitializerContracts;
use Illuminate\Container\Container;

class UserModule implements InitializerContracts
{
  protected $app;
  protected $basePath;
  protected $publicPath;
  protected $resourcePath;
  protected $configPath;
  protected $providerPath;
  protected $composer;
  protected $filesystem;
  protected $router;
  protected $force;
  protected $only;
  protected $allTasks = [
    'model',
    'presenter',
    'routes',
    'views',
    'requests',
  ];

  protected $tasks = [];

  public function __construct(Container $container, ComposerManager $composer, $force = false, $only = null)
  {
    $this->app = $container;
    $this->composer = $composer;
    $this->basePath = $container->basePath();
    $this->publicPath = $container['path.public'];
    $this->resourcePath = $container->basePath().'/resources';
    $this->configPath = $container->basePath().'/config';
    $this->providerPath = $container['path'].'/Providers';
    $this->setForce($force);
    $this->setTasks($only);
  }

  public function setForce($force)
  {
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
    if (in_array('model', $this->tasks))
    {
      yield $this->makeUserModel();
    }
    if (in_array('presenter', $this->tasks))
    {
      yield $this->makeUserPresenter();
    }
    if (in_array('routes', $this->tasks))
    {
      yield $this->makeUserRoutes();
    }
    if (in_array('views', $this->tasks))
    {
      yield $this->makeUserViews();
    }
    if (in_array('requests', $this->tasks))
    {
      yield $this->makeUserRequests();
    }
  }

  public function makeUserModel()
  {
    $filePath = $this->basePath.'/app/User.php';
    if (!$this->force && $this->app['files']->exists($filePath)) {
      return $filePath . ' already exists.';
    }

    $this->app['files']->makeDirectory(dirname($filePath), 02755, true, true);
    $templatePath = __DIR__.'/../files/user/User.stub';
    $template = $this->app['files']->get($templatePath);
    $this->app['files']->put($filePath, $template);

    return $filePath.' generated.';
  }

  public function makeUserPresenter()
  {
    $filePath = $this->basePath.'/app/Http/Presenters/UserPresenter.php';
    if (!$this->force && $this->app['files']->exists($filePath)) {
      return $filePath . ' already exists.';
    }

    $this->app['files']->makeDirectory(dirname($filePath), 02755, true, true);
    $templatePath = __DIR__.'/../files/user/UserPresenter.stub';
    $template = $this->app['files']->get($templatePath);
    $this->app['files']->put($filePath, $template);

    return $filePath.' generated.';
  }

  public function makeUserRoutes()
  {
    $this->app->make('Illuminate\Contracts\Console\Kernel')
      ->call('make:module', [
        'module' => 'user',
        '--only' => 'routes',
        '--force' => $this->force,
      ]);

    return 'make:module user --only=routes called.';
  }

  public function makeUserViews()
  {
    $this->app->make('Illuminate\Contracts\Console\Kernel')
      ->call('make:module', [
        'module' => 'user',
        '--only' => 'views',
        '--force' => $this->force,
    ]);

    return 'make:module user --only=views called.';
  }

  public function makeUserRequests()
  {
    try {
      $module = new \Eyewill\TucleBuilder\Module($this->app, 'user');
      $factory = new RequestsBuilderFactory($this->app);
      $path = $this->basePath.'/app/Http/Requests/User';
      $builder = $factory->make($module, $path, $this->force);
      $builder->setRule('store', function ($builder) {
        $code = '';
        $code.= 'return ['.PHP_EOL;
        $rules = [
          'user_name' => 'required',
          'login_id' => 'required|unique:users',
          'password' => 'required',
          'role' => 'required',
        ];
        foreach ($rules as $column => $rule)
        {
          $code.= sprintf("'%s' => '%s',", $column, $rule).PHP_EOL;
        }
        $code.= '];';

        return $code;
      });

      $builder->setRule('update', function ($builder) {
        $code = '';
        $code.= '$user = $this->route(\'user\');'.PHP_EOL;
        $code.= 'return ['.PHP_EOL;
        $code.= "'user_name' => 'required',".PHP_EOL;
        $code.= "'login_id' => 'required|unique:users,login_id,'.\$user->id,".PHP_EOL;
        $code.= "'role' => 'required',".PHP_EOL;
        $code.= '];';

        return $code;
      });

      $builder->make();

      return $path.' generated.';
    } catch (Exception $e) {

      return $e->getMessage();
    }
  }
}