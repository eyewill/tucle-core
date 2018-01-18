<?php namespace Eyewill\TucleCore;

use Illuminate\Container\Container;
use Symfony\Component\Process\Process;

class ComposerManager
{
  protected $app;
  protected $path;
  protected $filesystem;
  protected $log;

  protected $updates = false;

  public function __construct(Container $container)
  {
    $this->app = $container;
    $this->filesystem = $container['files'];
    $this->log = $container['log'];
    $this->path = base_path('composer.json');
  }

  public function add($name, $version, $dev = false)
  {
    if (!$this->filesystem->exists($this->path))
    {
      return 'composer.json not exists.';
    }

    $composerJson = json_decode($this->filesystem->get($this->path), true);
    $prefix = $dev ? 'require-dev.' : 'require.';
    if (array_has($composerJson, $prefix.$name))
    {
      return $name.':'.$version.' already exists.';
    }

    array_set($composerJson, $prefix.$name, $version);
    $this->updates = true;
    $this->filesystem->put($this->path, json_encode($composerJson, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES));
    return 'add '.$name.':'.$version.' to composer.json';
  }

  public function addAutoload($name, $value, $dev = false)
  {
    if (!$this->filesystem->exists($this->path))
    {
      return 'composer.json not exists.';
    }

    $composerJson = json_decode($this->filesystem->get($this->path), true);
    $baseKey = $dev ? 'autoload-dev' : 'autoload';
    $values = [];
    if (!is_array($value))
    {
      $values[] = $value;
    }
    else
    {
      $values = $value;
    }

    if (!array_key_exists($baseKey, $composerJson))
    {
      $composerJson[$baseKey] = [];
    }
    if (!array_key_exists($name, $composerJson[$baseKey]))
    {
      $composerJson[$baseKey][$name] = [];
    }
    if (array_values($values) === $values) // 配列(連想配列ではない)
    {
      foreach ($values as $value)
      {
        $composerJson[$baseKey][$name][] = $value;
      }
    }
    else
    {
      foreach ($values as $key => $value)
      {
        $composerJson[$baseKey][$name][$key] = $value;
      }
    }

    $this->updates = true;
    $this->filesystem->put($this->path, json_encode($composerJson, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES));
    return 'add values to '.$baseKey.':'.$name.' in composer.json';
  }


  public function scripts($command, $index = 1, $event = 'post-update-cmd')
  {
    if (!$this->filesystem->exists($this->path))
    {
      return 'composer.json not exists.';
    }

    $composerJson = json_decode($this->filesystem->get($this->path), true);
    $commandList = array_get($composerJson, 'scripts.'.$event);
    if (in_array($command, $commandList))
    {
      return $command. ' already exists.';
    }

    array_splice($commandList, $index, 0, $command);
    array_set($composerJson, 'scripts.'.$event, $commandList);
    $this->filesystem->put($this->path, json_encode($composerJson, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES));

    return 'add '.$command.' to '.$event;
  }

  public function update()
  {
    if (!$this->updates)
    {
      return 'composer.json not updated.';
    }

    $command = 'composer update';
    $process = new Process($command);
    $process->setTimeout(0);
    $process->start();
    yield '> '.$command;
    foreach ($process as $type => $data)
    {
      yield trim($data);
    }
    $this->updates = false;
  }

  public function dumpAutoload()
  {
    $command = 'composer dumpautoload';
    $process = new Process($command);
    $process->setTimeout(0);
    $process->start();
    yield '> '.$command;
    foreach ($process as $type => $data)
    {
      yield trim($data);
    }
    $this->updates = false;
  }

  public function setUpdate()
  {
    $this->updates = true;
  }
}