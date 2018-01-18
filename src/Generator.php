<?php namespace Eyewill\TucleCore;

use Exception;
use Eyewill\TucleCore\Database\Migrations\MigrationCreator;
use Illuminate\Container\Container;
use Eyewill\TucleCore\Contracts\Generator as GeneratorContracts;

class Generator implements GeneratorContracts
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
  protected $filesystem;
  protected $router;
  protected $force;
  protected $only;
  protected $migrationCreator;
  protected $allTasks = [];
  protected $tasks = [];

  public function __construct(Container $container, ComposerManager $composer, MigrationCreator $migrationCreator, $force = false, $only = null)
  {
    $this->app = $container;
    $this->composer = $composer;
    $this->filesystem = $container['files'];
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
    if (!$this->filesystem->exists($this->basePath.'/.tucle'))
    {
      $this->force = true;
      return;
    }

    $this->force = $force;
  }

  public function getAllTasks()
  {
    return $this->allTasks;
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


  protected function makeFromStub($src, $dest, $name = null)
  {
    $name = $name ?: $dest;

    if (!$this->force && $this->filesystem->exists($dest))
    {
      return $dest.' already exists';
    }

    $this->filesystem->makeDirectory(dirname($dest), 02755, true, true);

    if ($this->filesystem->isDirectory($src))
    {
      $this->filesystem->copyDirectory($src, $dest);
    }
    else
    {
      $this->filesystem->copy($src, $dest);
    }

    return $name.' generated.';
  }

  protected function makeMigrationFromStub($table, $prefix = 'create_')
  {
    $name = $prefix.$table.'_table';
    $migrationsPath = $this->databasePath.'/migrations';

    $glob = glob($migrationsPath.'/*_'.$name.'.php');
    if ($glob && count($glob) > 0)
    {
      if (!$this->force)
      {
        return $table.' already exists';
      }
      unlink($glob[0]);
    }

    try {
      $this->migrationCreator->create(
        $name,
        $migrationsPath,
        $table,
        $table);
      return $table.' migration file generated.';
    } catch (Exception $e) {
      return $table.' migration file can\'t generated.'.$e->getMessage();
    }
  }
}