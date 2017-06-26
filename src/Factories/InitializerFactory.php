<?php namespace Eyewill\TucleCore\Factories;

use Eyewill\TucleCore\ComposerManager;
use Eyewill\TucleCore\Database\Migrations\MigrationCreator;
use Eyewill\TucleCore\Initializer;
use Illuminate\Container\Container;

class InitializerFactory
{
  protected $container;
  protected $composer;
  protected $migrationCreator;

  public function __construct(Container $container, ComposerManager $composer, MigrationCreator $migrationCreator)
  {
    $this->container = $container;
    $this->composer = $composer;
    $this->migrationCreator = $migrationCreator;
  }

  public function make($force = false, $only = null)
  {
    return new Initializer(
      $this->container,
      $this->composer,
      $this->migrationCreator,
      $force,
      $only
    );
  }
}