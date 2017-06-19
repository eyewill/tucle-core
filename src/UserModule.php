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
    $this->app['view']->addNamespace('Template', __DIR__.'/../resources/templates');
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

    $code = '';
    $code.= '<?php namespace App;'.PHP_EOL;
    $code.= view()->make('Template::User')->render();
    $this->app['files']->put($filePath, $code);

    return $filePath.' generated.';
  }

  public function makeUserPresenter()
  {
    $filePath = $this->basePath.'/app/Http/Presenters/UserPresenter.php';
    if (!$this->force && $this->app['files']->exists($filePath)) {
      return $filePath . ' already exists.';
    }

    $this->app['files']->makeDirectory(dirname($filePath), 02755, true, true);
    $code = '';
    $code.= '<?php namespace App\Http\Presenters;'.PHP_EOL;
    $code.= view()->make('Template::UserPresenter')->render();
    $this->app['files']->put($filePath, $code);

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
          'name' => 'required',
          'email' => 'required|unique:users',
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
        $code.= "'name' => 'required',".PHP_EOL;
        $code.= "'email' => 'required|unique:users,email,'.\$user->id,".PHP_EOL;
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