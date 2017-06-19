<?php namespace Eyewill\TucleCore\Factories;

use Eyewill\TucleCore\ComposerManager;
use Eyewill\TucleCore\UserModule;
use Illuminate\Container\Container;

class UserModuleFactory
{
  protected $container;
  protected $composer;

  public function __construct(Container $container, ComposerManager $composer)
  {
    $this->container = $container;
    $this->composer = $composer;
  }

  public function make($force = false, $only = null)
  {
    return new UserModule(
      $this->container,
      $this->composer,
      $force,
      $only
    );
  }
}